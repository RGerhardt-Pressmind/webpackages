<?php
/*
    Copyright (C) 2015  <Robbyn Gerhardt>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    @category   curl.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class curl implements IStatic
{
	public static $userAgent, $cookieActive = false;

	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	/**
	 * Kontrolliert ob die cURL Extension existiert
	 *
	 * @return bool Gibt true zurück wenn die cURL Extension installiert ist und false wenn nicht
	 */
	public static function existCurl()
	{
		if(class_exists('\package\plugins') === true)
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
	 * @paam string $url Die HTTP Adresse die cURL aufrufen soll
	 * @param array $postfields Irgendwelche Post Felder die übermittelt werden sollen
	 * @param bool $ssl Wenn es sich um eine SSL Verbindung handelt kann man dies hier noch angeben
	 *
	 * @throws \Exception
	 * @return mixed Gibt die aufgerufenen Daten zurück
	 */
	public static function getData($url, $postfields = array(), $ssl = false)
	{
		if(self::existCurl() === false)
		{
			throw new \Exception('curl extension not loaded');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'getData', array($url, $postfields, $ssl));
			$plugins	=	plugins::hookCall('before', 'curl', 'getData', array($url, $postfields, $ssl));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$curlPost = '';

		if(is_array($postfields) === true && empty($postfields) === false)
		{
			foreach($postfields as $key => $option)
			{
				$curlPost .=	'&'.$key.'='.$option;
			}
		}

		$curlPost	=	trim($curlPost, '&');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		if(is_array($postfields) === true)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		}

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,	120);
		curl_setopt($ch, CURLOPT_TIMEOUT,			120);
		curl_setopt($ch, CURLOPT_MAXREDIRS,			10);

		if($ssl === true)
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		}
		else
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		if(empty(self::$userAgent) === false)
		{
			curl_setopt($ch, CURLOPT_USERAGENT, self::$userAgent);
		}

		if(self::$cookieActive === true)
		{
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIEJAR, ROOT.SEP.'cache'.SEP.'cookie.txt');
		}

		$data = curl_exec($ch);
		curl_close($ch);

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'getData', array($data));
			$plugins	=	plugins::hookCall('after', 'curl', 'getData', array($data));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $data;
	}


	/**
	 * Gibt den Statuscode einer URL zurück
	 *
	 * @param string Die HTTP Adresse die cURL aufrufen soll
	 *
	 * @throws \Exception
	 * @return int Gibt die HTTP Statuscode zurück
	 */
	public static function getState($url)
	{
		if(self::existCurl() === false)
		{
			throw new \Exception('curl extension not loaded');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'getState', array($url));
			$plugins	=	plugins::hookCall('before', 'curl', 'getState', array($url));

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

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'getState', array((int)$httpcode));
			$plugins	=	plugins::hookCall('after', 'curl', 'getState', array((int)$httpcode));

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
	 *
	 * @return array Gibt Längen und Breitengrade der Stadt zurück
	 * @throws \Exception
	 */
	public static function getCityCoordinates($city)
	{
		if(self::existCurl() === false)
		{
			throw new \Exception('curl extension not loaded');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'getCityCoordinates', array($city));
			$plugins	=	plugins::hookCall('before', 'curl', 'getCityCoordinates', array($city));

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

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'getCityCoordinates', array($data));
			$plugins	=	plugins::hookCall('after', 'curl', 'getCityCoordinates', array($data));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $data;
	}


	/**
	 * Gibt den Namen der Stadt zurück
	 *
	 * @return string Gibt, Anhand der IP-Adresse, den Namen der Stadt zurück oder "Not found" wenn keine Stadt gefunden wurde
	 */
	public static function getCityNameByIp()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'curl', 'getCityNameByIp');
			$plugins	=	plugins::hookCall('before', 'curl', 'getCityNameByIp');

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(class_exists('\package\security'))
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

		$getData	=	self::getData('http://ip-api.com/php/'.$ip);
		$query		=	@unserialize($getData);

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'curl', 'getCityNameByIp', array($query));
			$plugins	=	plugins::hookCall('after', 'curl', 'getCityNameByIp', array($query));

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