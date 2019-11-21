<?php
/**
 *  Copyright (C) 2010 - 2020  <Robbyn Gerhardt>
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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2020.0
 * @filesource
 */

namespace package\system\core;

use package\system\exceptions\securityException;

/**
 * Überprüft jeglichen Input eines Benutzer
 *
 * Mit der security Klasse kann man jegliche Form von Angriff über Eingaben des Benutzers aufhalten. Ob XSS oder
 * SQL Injections, die security Klasse überprüft diese und gibt sie gereinigt zurück.
 *
 * @method static mixed autoSecurity(array $exceptionValues)
 * @method static mixed control(string $variable, $convert = null, $removeSQLFunctions = false)
 * @method static mixed url(string $variable, $input = 'get', $convert = 'string', $removeSQLFunctions = false)
 * @method static string|bool create_csrf_token(string $token_name, $token_duration = 0);
 * @method static bool exists_csrf_token(string $token_name)
 * @method static string|bool get_csrf_token(string $token_name, bool $remove_token_after = false)
 * @method static bool remove_csrf_token(string $token_name)
 * @method static mixed get_mime_type(string $path)
 * @method static mixed get_file_type(string $path)
 * @method static string shaSec(string $string)
 * @method static string|string[] xss_clean(string|string[] $str)
 * @method static string sha_sec(string $string)
 * @method static string entity_decode(string $str, $charset = 'UTF-8')
 * @method static string remove_invisible_characters(string $str, $url_encoded = true)
 * @method static array is_bot()
 * @method static mixed get_ip_address()
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       security
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class security extends initiator
{
	/**
	 * @var string Der XSS-Hash
	 */
	public static $_xss_hash = '';

	/**
	 * @var array Liste aller bekannten Bots (stetig in Erweiterung)
	 */
	public static $botlist = array(
		"Teoma",
		"alexa",
		"froogle",
		"Gigabot",
		"inktomi",
		"looksmart",
		"URL_Spider_SQL",
		"Firefly",
		"NationalDirectory",
		"Ask Jeeves",
		"TECNOSEEK",
		"InfoSeek",
		"WebFindBot",
		"girafabot",
		"crawler",
		"www.galaxy.com",
		"Googlebot",
		"Scooter",
		"Slurp",
		"msnbot",
		"appie",
		"FAST",
		"WebBug",
		"Spade",
		"ZyBorg",
		"rabaz",
		"Baiduspider",
		"Feedfetcher-Google",
		"TechnoratiSnoop",
		"Rankivabot",
		"Mediapartners-Google",
		"Sogou web spider",
		"WebAlta Crawler",
		"TweetmemeBot",
		"Butterfly",
		"Twitturls",
		"Me.dium",
		"Twiceler",
		"bing",
		"microsoft",
		"yahoo"
	);

	/**
	 * @var array Liste aller erlaubten IP-Methoden
	 */
	public static $ipMethodes = array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
	);

	private static $hasControl = array('post' => array(), 'get' => array());

	/**
	 * Kontrolliert alle einkommenden Anfragen einmal auf schädlichen Code durch
	 *
	 * @param array $exceptionValues
	 * @return void
	 */
	protected static function _autoSecurity($exceptionValues)
	{
		$controlVariables	=	array(
			'get'		=>	'_GET',
			'post'		=>	'_POST',
			'session'	=>	'_SESSION',
			'request'	=>	'_REQUEST'
		);

		foreach($controlVariables as $key => $variable)
		{
			if(!empty($GLOBALS[$variable]))
			{
				foreach($GLOBALS[$variable] as $k => $v)
				{
					if(!in_array($k, $exceptionValues))
					{
						self::$hasControl[$key][$k]	=	gettype($v);

						$GLOBALS[$variable][$k]	=	self::control($v, self::$hasControl[$key][$k]);
					}
				}
			}
		}
	}


	/**
	 * Kontrolliert eine Variable auf Sicherheit und Konvertiert diese zur Sicherheit auch in das gewünschte Format.
	 *
	 * @param mixed   $variable           Die Variable die überprüft werden soll.
	 * @param string  $convert            Der Typ in den der Rückgabewert konvertiert werden soll.
	 * @param boolean $removeSQLFunctions Entfernt SQL Funktionen aus einem Wert
	 *
	 * @return mixed Gibt die konvertierte Variable zurück
	 */
	protected static function _control($variable, $convert = null, $removeSQLFunctions = false)
	{
		if(is_string($variable))
		{
			$variable = trim($variable);
		}

		return self::_controllSecurity($variable, $convert, $removeSQLFunctions);
	}

	/**
	 * Sicherer Weg um $_GET, $_POST, $_SESSION, $_COOKIE, $_SERVER, $_ENV und $_REQUEST aufzufangen,
	 * man sollte immer über diese Funktion Daten abrufen und prüfen lassen.
	 *
	 * @param string  $variable           Zu überprüfender Wert in $input
	 * @param string  $input              Die Quelle in der der Wert von $variable liegt.
	 * @param string  $convert            Der Typ in den der Rückgabewert konvertiert werden soll.
	 * @param boolean $removeSQLFunctions Entfernt SQL Funktionen aus einem Wert
	 *
	 * @return mixed Gibt den überprüften Wert konvertiert zurück.
	 */
	protected static function _url($variable, $input = 'get', $convert = 'string', $removeSQLFunctions = false)
	{
		$input		=	strtolower($input);

		$controlVariables	=	array(
			'get'		=>	'_GET',
			'post'		=>	'_POST',
			'session'	=>	'_SESSION',
			'request'	=>	'_REQUEST',
			'cookie'	=>	'_COOKIE',
			'server'	=>	'_SERVER',
			'env'		=>	'_ENV',
		);

		if(!isset($controlVariables[$input]))
		{
			return false;
		}

		if(AUTO_SECURE == true && isset(self::$hasControl[$input][$variable]) && self::$hasControl[$input][$variable] == $convert)
		{
			return $GLOBALS[$controlVariables[$input]][$variable];
		}

		$request = '';

		if(!empty($GLOBALS[$controlVariables[$input]][$variable]))
		{
			$request	=	$GLOBALS[$controlVariables[$input]][$variable];
		}

		return self::_controllSecurity($request, $convert, $removeSQLFunctions);
	}

	/**
	 * Erstellt einen CSRF Token, für die
	 * anschließende Kontrolle.
	 *
	 * @param string $token_name
	 * @param int $token_duration
	 *
	 * @return string|bool
	 */
	protected static function _create_csrf_token($token_name, $token_duration = 0)
	{
		if(empty($token_name))
		{
			return false;
		}

		$token	=	md5(uniqid(mt_rand(), true));

		if($token_duration <= 0)
		{
			$token_duration	=	60*60;
		}

		if(setcookie($token_name, $token, (time() + $token_duration)))
		{
			return $token;
		}

		return false;
	}

	/**
	 * Kontrolliert ob der Token beim Benutzer existiert
	 *
	 * @param string $token_name
	 *
	 * @return bool
	 */
	protected function _exists_csrf_token($token_name)
	{
		return isset($_COOKIE[$token_name]);
	}

	/**
	 * Gibt den Token zurück oder ein false wenn er nicht existiert
	 *
	 * @param string $token_name
	 * @param bool $remove_token_after
	 *
	 * @return string|bool
	 */
	protected function _get_csrf_token($token_name, $remove_token_after = false)
	{
		if(!empty($_COOKIE[$token_name]))
		{
			$token	=	$_COOKIE[$token_name];

			if($remove_token_after)
			{
				$this->_remove_csrf_token($token_name);
			}

			return $token;
		}

		return false;
	}

	/**
	 * Entfernt den Token
	 *
	 * @param $token_name
	 *
	 * @return bool
	 */
	protected function _remove_csrf_token($token_name)
	{
		if(!empty($_COOKIE[$token_name]))
		{
			unset($_COOKIE[$token_name]);
		}

		return setcookie($token_name, '', (time() - 1));
	}


	/**
	 * Sicherheitskontrolle einer Variable
	 *
	 * @param mixed   $request
	 * @param string  $convert
	 * @param boolean $removeSQLFunctions Entfernt SQL Funktionen aus einem Wert
	 *
	 * @return mixed
	 */
	protected static function _controllSecurity($request, $convert, $removeSQLFunctions = false)
	{
		$convert = strtolower($convert);

		$filterIn = array('ip' => FILTER_VALIDATE_IP, 'mail' => FILTER_VALIDATE_EMAIL, 'email' => FILTER_VALIDATE_EMAIL, 'e' => FILTER_VALIDATE_EMAIL, 'number' => FILTER_VALIDATE_FLOAT, 'num' => FILTER_VALIDATE_FLOAT, 'dec' => FILTER_VALIDATE_FLOAT, 'decimal' => FILTER_VALIDATE_FLOAT, 'double' => FILTER_VALIDATE_FLOAT, 'float' => FILTER_VALIDATE_FLOAT, 'floatval' => FILTER_VALIDATE_FLOAT, 'n' => FILTER_VALIDATE_FLOAT, 'integer' => FILTER_VALIDATE_INT, 'int' => FILTER_VALIDATE_INT, 'long' => FILTER_VALIDATE_INT, 'i' => FILTER_VALIDATE_INT, 'boolean' => FILTER_VALIDATE_BOOLEAN, 'bool' => FILTER_VALIDATE_BOOLEAN, 'b' => FILTER_VALIDATE_BOOLEAN, 'string' => FILTER_SANITIZE_STRING, 'str' => FILTER_SANITIZE_STRING, 's' => FILTER_SANITIZE_STRING);

		if(!isset($filterIn[$convert]))
		{
			return false;
		}

		$isString	=	($convert == 'string' || $convert == 'str' || $convert == 's');

		if($isString)
		{
			if(is_array($isString))
			{
				return 'array()';
			}
			elseif(is_object($isString))
			{
				return 'std()';
			}
		}

		$request	=	filter_var($request, $filterIn[$convert]);

		if($request === false || $request == null)
		{
			return false;
		}

		if($isString)
		{
			$request = self::xss_clean($request);

			if($removeSQLFunctions)
			{
				$request = preg_replace(MYSQL_FUNCTIONS, '', $request, -1);
			}

			$request = trim($request);
		}

		return $request;
	}

	/**
	 * Gibt den aktuellen MIME-Type zurück
	 *
	 * @param string $path Der relative Pfad zur übeprüfenden Datei.
	 *
	 * @throws securityException
	 * @return bool|mixed Gibt MIME-Type zurück.
	 */
	protected static function _get_mime_type($path)
	{
		if(!function_exists('finfo_open'))
		{
			throw new securityException('finfo extensio not loaded');
		}

		$finfo     = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($finfo, $path);

		finfo_close($finfo);

		return $mime_type;
	}

	/**
	 * Gibt die Dateiendung zurück die für diese Datei die richtige ist
	 *
	 * @param string $path Der realative Pfad zur Datei.
	 *
	 * @throws securityException
	 * @return mixed Gibt die Dateierweiterung zurück.
	 */
	protected static function _get_file_type($path)
	{
		if(!class_exists('\SplFileInfo'))
		{
			throw new securityException('Error: SplFileInfo in php not installed');
		}

		$file = new \SplFileInfo($path);

		return $file->getExtension();
	}

	/**
	 * Verschlüsselt einen String
	 *
	 * @deprecated
	 *
	 * @param string $string Der String der Verschlüsselt werden soll.
	 *
	 * @throws securityException
	 * @return string Gibt den SHA512 Verschlüsselten String zurück.
	 */
	protected static function _shaSec($string)
	{
		return self::_sha_sec($string);
	}

	/**
	 * Verschlüsselt einen String
	 *
	 * @param string $string Der String der Verschlüsselt werden soll.
	 *
	 * @throws securityException
	 * @return string Gibt den SHA512 Verschlüsselten String zurück.
	 */
	protected static function _sha_sec($string)
	{
		if(!function_exists('hash_hmac'))
		{
			throw new securityException('hash extension not loaded');
		}

		$crypt = hash_hmac("sha512", $string, SECURITY_KEY);

		return $crypt;
	}

	/**
	 * Säubert einen String vor schädlichen XSS Code
	 *
	 * @param string|string[] $str Der String der von XSS gesäubert werden soll
	 *
	 * @return string $str
	 */
	protected static function _xss_clean($data)
	{
		// Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
	}



	/**
	 * HTML Entities Decode
	 *
	 * This function is a replacement for html_entity_decode()
	 *
	 * The reason we are not using html_entity_decode() by itself is because
	 * while it is not technically correct to leave out the semicolon
	 * at the end of an entity most browsers will still interpret the entity
	 * correctly.  html_entity_decode() does not convert entities without
	 * semicolons, so we are left with our own little solution here. Bummer.
	 *
	 * @param    string
	 * @param    string
	 *
	 * @return    string
	 */
	protected static function _entity_decode($str, $charset = 'UTF-8')
	{
		if(!stristr($str, '&'))
		{
			return $str;
		}

		$_entities	=	array_map('strtolower', get_html_translation_table(HTML_ENTITIES, ENT_COMPAT | ENT_HTML5));

		do
		{
			$str_compare = $str;

			if(preg_match_all('/&[a-z]{2,}(?![a-z;])/i', $str, $matches))
			{
				$replace = array();
				$matches = array_unique(array_map('strtolower', $matches[0]));

				foreach($matches as &$match)
				{
					if(($char = array_search($match.';', $_entities, true)) !== false)
					{
						$replace[$match] = $char;
					}
				}

				$str = str_replace(array_keys($replace), array_values($replace), $str);
			}

			$str = html_entity_decode(
				preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str),
				ENT_COMPAT | ENT_HTML5,
				$charset
			);
		}
		while($str_compare !== $str);

		return $str;
	}


	/**
	 * Entfernt leere Zeichen aus einem String
	 *
	 * @param string $str
	 * @param bool   $url_encoded
	 *
	 * @return mixed
	 */
	protected static function _remove_invisible_characters($str, $url_encoded = true)
	{
		$non_displayables = array();

		if($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';
			$non_displayables[] = '/%1[0-9a-f]/';
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';

		while(true)
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);

			if($count == 0)
			{
				break;
			}
		}

		return $str;
	}

	/**
	 * Kontrolliert ob es sich um den
	 * Benutzer um einen Bot handelt.
	 *
	 * @return array Gibt ein assoziatives Array zurück mit Informationen über die Auswertung.
	 */
	protected static function _is_bot()
	{
		if(!empty($_SERVER['HTTP_USER_AGENT']))
		{
			$http_user_agent	=	strtolower($_SERVER['HTTP_USER_AGENT']);

			foreach(self::$botlist as $bot)
			{
				if(strpos($http_user_agent, strtolower($bot)) !== false)
				{
					return array(
						'isBot' => true,
						'version' => $bot
					);
				}
			}
		}

		return array(
			'isBot' => false,
			'version' => ''
		);
	}

	/**
	 * Gibt die aktuelle IP-Adresse des Benutzers zurück
	 *
	 * @return mixed Gibt die Ip-Adresse zurück oder ein false wenn diese nicht ermittelt werden konnte.
	 */
	protected static function _get_ip_address()
	{
		$ipMethodes = self::$ipMethodes;

		foreach($ipMethodes as $key)
		{
			if(!empty($_SERVER[$key]))
			{
				foreach(explode(',', $_SERVER[$key]) as $ip)
				{
					$ip = trim($ip);

					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
					{
						return $ip;
					}
				}
			}
			elseif(getenv($key) != false)
			{
				foreach(explode(',', getenv($key)) as $ip)
				{
					$ip = trim($ip);

					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
					{
						return $ip;
					}
				}
			}
		}

		return false;
	}
}
