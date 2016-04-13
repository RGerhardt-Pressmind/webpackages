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
 * @subpackage    core
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\curlException;
use package\implement\IStatic;
use package\system\core\initiator;

/**
 * Kommunizieren mit cURL
 *
 * Wenn man eine bestimmte URL aufrufen möchte oder den aktuellen HTTP-Code einer Webseite haben möchte, nutzt man
 * die PHP Extension cURL. Mit der Klasse curl ist es deutlich einfacher mit der Extension zu Kommunizieren. Bereits
 * vordefinierte Methoden helfen Ihnen einfach bestimmte Bereiche abzufragen.
 *
 * @method static bool downloadFile(string $url, string $destination)
 * @method static bool curl_extension_exists()
 * @method static mixed get_data(string $url, $postfields = array())
 * @method static int get_status(string $url)
 * @method static array get_city_coordinates(string $city, $resultArray = false)
 * @method static string get_city_name_by_ip()
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       cURL
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class curl extends initiator implements IStatic
{
	/**
	 * @var bool Ist Cookie bei der cURL Anfrage Aktiv oder nicht
	 */
	public static $cookieActive = false;

	/**
	 * @var string Der Pfad zur Cookie Datei
	 */
	private static $COOKIE_FILE = 'cookie.txt';

	/**
	 * Zum initialisieren von Daten
	 */
	public static function init()
	{
	}

	/**
	 * Kontrolliert ob die cURL Extension existiert
	 *
	 * @return bool Gibt true zurück wenn die cURL Extension installiert ist und false wenn nicht
	 */
	protected static function _curl_extension_exists()
	{
		return function_exists('curl_init');
	}

	/**
	 * Lädt eine Datei herunter und speichert sie lokal auf den Webserver ab
	 *
	 * @param string $url Die URL von der Datei die heruntergeladen werden soll
	 * @param string $destination Der absolute Pfad zum Speicherort
	 *
	 * @return bool
	 * @throws curlException
	 */
	protected static function _downloadFile($url, $destination)
	{
		if(!self::curl_extension_exists())
		{
			throw new curlException('Error: curl extension not loaded');
		}

		$fp = fopen($destination, 'w+');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FILE, $fp);

		curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		return (filesize($destination) > 0);
	}

	/**
	 * Gibt Daten mittels cURL zurück
	 *
	 * @param string $url        Die HTTP Adresse die cURL aufrufen soll
	 * @param array  $postfields Irgendwelche Post Felder die übermittelt werden sollen
	 * @param bool   $ssl        Wenn es sich um eine SSL Verbindung handelt kann man dies hier noch angeben
	 *
	 * @return mixed
	 * @throws curlException Wenn die Extension nicht installiert ist.
	 */
	protected static function _get_data($url, $postfields = array(), $ssl = false)
	{
		if(!self::curl_extension_exists())
		{
			throw new curlException('Error: curl extension not loaded');
		}

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $ssl);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $ssl);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 9);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36');
		curl_setopt($curl, CURLOPT_COOKIEFILE, self::$COOKIE_FILE);
		curl_setopt($curl, CURLOPT_COOKIEJAR, self::$COOKIE_FILE);
		curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);

		if(!empty($postfields))
		{
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		}

		$data = curl_exec($curl);
		curl_close($curl);

		return $data;
	}

	/**
	 * Gibt den HTTP-Statuscode einer URL zurück
	 *
	 * @param string $url Die HTTP Adresse die cURL aufrufen soll und der HTTP-Statuscode überprüft werden soll
	 *
	 * @return int Gibt den HTTP-Statuscode zurück
	 * @throws curlException Wenn die Extension nicht installiert ist oder im Fehlerfall
	 */
	protected static function _get_status($url)
	{
		if(!self::curl_extension_exists())
		{
			throw new curlException('Error: curl extension not loaded');
		}

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_ENCODING, '');
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_exec($ch);

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return (int)$httpcode;
	}

	/**
	 * Gibt die Koordinaten einer Stadt zurück
	 *
	 * @param string $city        Den Stadtnamen
	 * @param bool   $resultArray Kann man einstellen ob das Ergebnis als Array zurück kommen soll oder als Objekt
	 *
	 * @return array Gibt Längen und Breitengrade der Stadt zurück
	 * @throws curlException Wenn die Extension nicht installiert oder im Fehlerfall.
	 */
	protected static function _get_city_coordinates($city, $resultArray = false)
	{
		if(!self::curl_extension_exists())
		{
			throw new curlException('Error: curl extension not loaded');
		}

		$city = urlencode($city);

		$url = "http://maps.google.com/maps/api/geocode/json?address=".$city."&sensor=false";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

		$response = curl_exec($ch);
		curl_close($ch);

		$response_a = json_decode($response, $resultArray);

		$data = array();

		if(!$resultArray)
		{
			if(!empty($response_a->results[0]))
			{
				$data = $response_a->results[0]->geometry->location;
			}
		}
		else
		{
			if(!empty($response_a['results'][0]))
			{
				$data = $response_a['results'][0]['geometry']['location'];
			}
		}

		return $data;
	}

	/**
	 * Gibt den Namen der Stadt, Anhand der aktuellen IP-Adresse, zurück.
	 *
	 * @return string Gibt, Anhand der IP-Adresse, den Namen der Stadt zurück oder "Not found" wenn keine Stadt
	 *                gefunden wurde
	 */
	protected static function _get_city_name_by_ip()
	{
		$ip = security::get_ip_address();

		if(empty($ip))
		{
			return 'Not found';
		}

		$getData = self::get_data('http://ip-api.com/php/'.$ip);
		$query   = unserialize($getData);

		if(!empty($query['status']) && $query['status'] == 'success')
		{
			return $query['city'];
		}
		else
		{
			return 'Not found';
		}
	}
} 