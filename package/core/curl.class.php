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
 *  @package	Webpackages
 *  @subpackage core
 *  @author	    Robbyn Gerhardt <gerhardt@webpackages.de>
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace package\core;

use package\implement\IStatic;

/**
 * Kommunizieren mit cURL
 *
 * Wenn man eine bestimmte URL aufrufen möchte oder den aktuellen HTTP-Code einer Webseite haben möchte, nutzt man
 * die PHP Extension cURL. Mit der Klasse curl ist es deutlich einfacher mit der Extension zu Kommunizieren. Bereits
 * vordefinierte Methoden helfen Ihnen einfach bestimmte Bereiche abzufragen.
 *
 * @package		Webpackages
 * @subpackage	core
 * @category	cURL
 * @author		Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class curl implements IStatic
{
	/**
	 * @var bool Ist Cookie bei der cURL Anfrage Aktiv oder nicht
	 */
	public static $cookieActive = false;

	/**
	 * @var string Der Pfad zur Cookie Datei
	 */
	private static $COOKIE_FILE	=	'cookie.txt';

	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}


	/**
	 * Kontrolliert ob die cURL Extension existiert
	 *
	 * @return bool Gibt true zurück wenn die cURL Extension installiert ist und false wenn nicht
	 */
	public static function curl_extension_exists()
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugins	=	plugins::hookCall('before', 'curl', 'existCurl');

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		return function_exists('curl_init');
	}


	/**
	 * Gibt Daten mittels cURL zurück
	 *
	 * @param string $url Die HTTP Adresse die cURL aufrufen soll
	 * @param array $postfields Irgendwelche Post Felder die übermittelt werden sollen
	 * @param bool $ssl Wenn es sich um eine SSL Verbindung handelt kann man dies hier noch angeben
	 *
	 * @return mixed
	 * @throws \Exception Wenn die Extension nicht installiert ist.
	 */
	public static function get_data($url, $postfields = array(), $ssl = false)
	{
		if(self::curl_extension_exists() === false)
		{
			throw new \Exception('curl extension not loaded');
		}

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'get_data', array($url, $postfields, $ssl));
			$plugins	=	plugins::hookCall('before', 'curl', 'get_data', array($url, $postfields, $ssl));

			if($plugins != null)
			{
				return $plugins;
			}
		}

        $curlOptions = array(
            CURLOPT_ENCODING 			=> 	'gzip,deflate',
            CURLOPT_AUTOREFERER 		=>	1,
            CURLOPT_CONNECTTIMEOUT 		=> 	120, // timeout on connect
            CURLOPT_TIMEOUT 			=> 	120, // timeout on response
            CURLOPT_URL 				=> 	$url,
            CURLOPT_SSL_VERIFYPEER 		=> 	$ssl,
            CURLOPT_SSL_VERIFYHOST 		=> 	$ssl,
            CURLOPT_FOLLOWLOCATION 		=> 	true,
            CURLOPT_MAXREDIRS 			=> 	9,
            CURLOPT_RETURNTRANSFER 		=> 	1,
            CURLOPT_HEADER 				=> 	0,
            CURLOPT_USERAGENT 			=> 	'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
            CURLOPT_COOKIEFILE 			=> 	self::$COOKIE_FILE,
            CURLOPT_COOKIEJAR 			=> 	self::$COOKIE_FILE,
            CURLOPT_VERBOSE 			=> 	true,
            CURLINFO_HEADER_OUT  		=> 	true,
        );

        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);

        if(empty($postfields) === false)
        {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
        }

        $data	=	curl_exec($curl);
		curl_close($curl);

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'get_data', array($data));
			$plugins	=	plugins::hookCall('after', 'curl', 'get_data', array($data));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $data;
	}


	/**
	 * Gibt den HTTP-Statuscode einer URL zurück
	 *
	 * @param string $url Die HTTP Adresse die cURL aufrufen soll und der HTTP-Statuscode überprüft werden soll
	 * @return int Gibt den HTTP-Statuscode zurück
	 * @throws \Exception Wenn die Extension nicht installiert ist oder im Fehlerfall
	 */
	public static function get_status($url)
	{
		if(self::curl_extension_exists() === false)
		{
			throw new \Exception('curl extension not loaded');
		}

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'get_status', array($url));
			$plugins	=	plugins::hookCall('before', 'curl', 'get_status', array($url));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_HEADER, 			true);
		curl_setopt($ch, CURLOPT_NOBODY, 			true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,	1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 			60);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,	true);
		curl_setopt($ch, CURLOPT_ENCODING,			'');
		curl_setopt($ch, CURLOPT_AUTOREFERER,		true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,	120);
		curl_setopt($ch, CURLOPT_TIMEOUT,			120);
		curl_setopt($ch, CURLOPT_MAXREDIRS,			10);
		curl_exec($ch);

		$httpcode 	= 	curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'get_status', array((int)$httpcode));
			$plugins	=	plugins::hookCall('after', 'curl', 'get_status', array((int)$httpcode));

			if($plugins != null)
			{
				return (int)$plugins;
			}
		}

		return (int)$httpcode;
	}


	/**
	 * Gibt die Koordinaten einer Stadt zurück
	 *
	 * @param string $city Den Stadtnamen
	 * @return array Gibt Längen und Breitengrade der Stadt zurück
	 * @throws \Exception Wenn die Extension nicht installiert oder im Fehlerfall.
	 */
	public static function get_city_coordinates($city)
	{
		if(self::curl_extension_exists() === false)
		{
			throw new \Exception('curl extension not loaded');
		}

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'get_city_coordinates', array($city));
			$plugins	=	plugins::hookCall('before', 'curl', 'get_city_coordinates', array($city));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$url = 	"http://maps.google.com/maps/api/geocode/json?address=".$city."&sensor=false";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,	120);
		curl_setopt($ch, CURLOPT_TIMEOUT,			120);
		curl_setopt($ch, CURLOPT_MAXREDIRS,			10);

		$response = curl_exec($ch);
		curl_close($ch);

		$response_a = json_decode($response);

		if(empty($response_a->results[0]) === false)
		{
			$data	=	$response_a->results[0]->geometry->location;
		}
		else
		{
			$data	=	array();
		}

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'get_city_coordinates', array($data));
			$plugins	=	plugins::hookCall('after', 'curl', 'get_city_coordinates', array($data));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $data;
	}


	/**
	 * Gibt den Namen der Stadt, Anhand der aktuellen IP-Adresse, zurück.
	 *
	 * @return string Gibt, Anhand der IP-Adresse, den Namen der Stadt zurück oder "Not found" wenn keine Stadt gefunden wurde
	 */
	public static function get_city_name_by_ip()
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'get_city_name_by_ip');
			$plugins	=	plugins::hookCall('before', 'curl', 'get_city_name_by_ip');

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(class_exists('\package\security') === true)
		{
			$ip	=	security::get_ip_address();

			if(empty($ip))
			{
				return 'Not found';
			}
		}
		elseif(empty($_SERVER['REMOTE_ADDR']) === false)
		{
			$ip 	= 	$_SERVER['REMOTE_ADDR'];
		}
		else
		{
			return 'Not found';
		}

		$getData	=	self::get_data('http://ip-api.com/php/'.$ip);
		$query		=	unserialize($getData);

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'get_city_name_by_ip', array($query));
			$plugins	=	plugins::hookCall('after', 'curl', 'get_city_name_by_ip', array($query));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(empty($query['status']) === false && $query['status'] == 'success')
		{
			return $query['city'];
		}
		else
		{
			return 'Not found';
		}
	}
} 