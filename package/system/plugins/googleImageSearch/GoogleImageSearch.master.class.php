<?php
/**
 *  Copyright (C) 2010 - 2016  <Robbyn Gerhardt>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package       Webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\system\plugins\googleImageSearch;

$googleImageSearchDir	=	dirname(__FILE__).SEP;

require_once $googleImageSearchDir.'classes'.SEP.'simple_html_dom.php';

use package\implement\IPlugin;

class GoogleImageSearch implements IPlugin
{
	public function construct(){}
	public function setAllClasses($allClasses){}

	public function getClassName()
	{
		return 'GoogleImageSearch';
	}

	private $searchUrl		= 	'https://www.google.com/searchbyimage?image_url=';
    private $googleDomain 	=	'https://www.google.com';
    private $sleepTime 		= 	1;

    /**Get simplehtmldom object from url
     * @param $url
	 * @param mixed $post
     * @return bool|\simple_html_dom
     */
    public function getDom($url, $post = false)
    {
        $f = fopen(CACHE_PATH.'googleImageSearchLog.txt', 'a+'); // curl session log file

        $curlOptions = array(
            CURLOPT_ENCODING 			=> 	'gzip,deflate',
            CURLOPT_AUTOREFERER 		=>	1,
            CURLOPT_CONNECTTIMEOUT 		=> 	120, // timeout on connect
            CURLOPT_TIMEOUT 			=> 	120, // timeout on response
            CURLOPT_URL 				=> 	$url,
            CURLOPT_SSL_VERIFYPEER 		=> 	false,
            CURLOPT_SSL_VERIFYHOST 		=> 	false,
            CURLOPT_FOLLOWLOCATION 		=> 	true,
            CURLOPT_MAXREDIRS 			=> 	9,
            CURLOPT_RETURNTRANSFER 		=> 	1,
            CURLOPT_HEADER 				=> 	0,
            CURLOPT_USERAGENT 			=> 	"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36",
            CURLOPT_COOKIEFILE 			=> 	CACHE_PATH.'googleImageSearchCookie.txt',
            CURLOPT_COOKIEJAR 			=> 	CACHE_PATH.'googleImageSearchCookie.txt',
            CURLOPT_STDERR 				=> 	$f, // log session
            CURLOPT_VERBOSE 			=> 	true,
            CURLINFO_HEADER_OUT  		=> 	true,
        );

        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);

        if($post) // add post options
        {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }

        $data	=	curl_exec($curl);
		curl_close($curl);

		$dom 	= 	str_get_html($data);
		fwrite($f, "-----------------------------------------------------------\n\n");
        fclose($f);
        return $dom;
    }


    /**Get simple_html_dom class for url and check if there's any redirect
     * @param $url
     * @return bool|\simple_html_dom
     * @throws \Exception
     */
    public function getImageUrlSearchDom($url)
    {
        $dom	=	$this->getDom($url);

        if(stripos($dom->find('title', 0), '302 Moved') !== false) // if '302 moved' page, follow link
        {
            $a	=	$dom->find('a', 0)->href;
            $dom->clear();
            sleep($this->sleepTime);
            $dom	=	$this->getDom($a);
        }

        if(stripos($dom->find('title', 0), 'sorry') !== false) // google thinks we're bot
        {
            throw new \Exception('Error: Google thinks we\'re bot and won\'t process our requests');
        }

        return $dom;
    }
    /**Get best guess text
     * @param \simple_html_dom $dom
     * @return bool
     */
    public function getBestGuess(\simple_html_dom $dom)
    {
        foreach($dom->find('div[class=card-section] div') as $div)
        {
            if(stripos($div->innertext, 'Best guess for this image') !== false)
            {
                $a = $div->find('a', 0);

                return array($a->innertext, $this->googleDomain.$a->href);
            }
        }

        return false;
    }

    /**Get search results from current page
     * @param \simple_html_dom $dom
     * @return array
     */
    public function getSearchResults(\simple_html_dom $dom)
    {
        $result = 	array();
        $c 		= 	count($dom->find('div.srg')) > 1 ? 1 : 0; // if this is first page, we have 2 divs, first with some irrelevant

        //links, so skip the first page

        $d = $dom->find('div.srg', $c); // get second div(if this is 1st page), or first div

        if(!empty($d))
		{
			foreach($d->find('div.rc') as $div)
			{
				$a = $div->find('h3.r a', 0); // get link to the website

				//Get original image url
				$originalImg = $div->find('div.th a', 0);
				preg_match('/imgurl=(.+?)&/', $originalImg->href, $matches);

				$result[]	=	array(htmlspecialchars_decode($a->plaintext, ENT_QUOTES), $a->href, $matches[1]);

			}
		}

        return $result;
    }

    /**Upload local image to Google and get result page
     * @param $fileName
     * @return bool|\simple_html_dom
     */
    public function getLocalImageSearchDom($fileName)
    {
        list($w, $h) = getimagesize($fileName);

        $dom = $this->getDom('https://www.google.com/searchbyimage/upload', array(
                'encoded_image' => '@'.realpath($fileName),
                'image_url' => '',
                'image_content' => '',
                'filename' => '',
                'h1' => 'en',
                'bih' => $h,
                'biw' => $w
            )
        );

        return $dom;
    }

    /**Get best guess text and loop through pages to get links to images
     * @param $imageUrl
     * @param int $numPages - number of pages to scrape
     * @return array(
     * 'best_guess' => string,
     * 'search_results' => array(
     *   array(name, url),
     *   array(name, url),
     *   ...,
     *   etc
     *  )
     * )
     */
    public function search($imageUrl, $numPages = 1)
    {
        try {
            $dom 			= 	is_file($imageUrl) ? $this->getLocalImageSearchDom($imageUrl) : $this->getImageUrlSearchDom($this->searchUrl.$imageUrl); // get first page dom
           # Logger::log(print_r($dom, true), 'dom.txt', true);
            $bestGuess 		=	$this->getBestGuess($dom); // get best guess from 1st page
            $searchResults 	= 	$this->getSearchResults($dom); // get search results from 1st page
            $nextPageA 		= 	$dom->find('#nav a.pn', 0); // check if we have 'next page' link (if we don't - it's the only page)
            $dom->clear();

            for($i = 0; ++$i < $numPages && $nextPageA;) // loop through pages [2 - $numPages]
            {
                $dom 			= 	$this->getImageUrlSearchDom($this->googleDomain.htmlspecialchars_decode($nextPageA->href));
                $searchResults 	= 	array_merge($searchResults, $this->getSearchResults($dom));// get search results from page and merge with available results
                $nextPageA 		= 	$dom->find('#nav a.pn', 0); // check if we have 'next page' link (if we don't - it's last page)

                $dom->clear();

                sleep(1);
            }
            return array('best_guess' => $bestGuess, 'search_results' => $searchResults);

        } catch (\Exception $e) {
            echo 'Exception for url: ', $imageUrl, "<br />\n", $e->getMessage(), "<br />\n";
            return false;
        }
    }
}