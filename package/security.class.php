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

    @category   security.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


class security 
{
	public static $allowedImages	=	array('image/gif', 'image/jpeg', 'image/png');
	public static $allowedFiles		=	array('application/zip');
	public static $_xss_hash		= 	'';

	/**
	 * @var array Liste von erlaubten Dateitypen (Mimes)
	 */
	public static $mimes 			= 	array(
		'hqx'	=>	'application/mac-binhex40',
		'cpt'	=>	'application/mac-compactpro',
		'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
		'bin'	=>	'application/macbinary',
		'dms'	=>	'application/octet-stream',
		'lha'	=>	'application/octet-stream',
		'lzh'	=>	'application/octet-stream',
		'exe'	=>	array('application/octet-stream', 'application/x-msdownload'),
		'class'	=>	'application/octet-stream',
		'psd'	=>	'application/x-photoshop',
		'so'	=>	'application/octet-stream',
		'sea'	=>	'application/octet-stream',
		'dll'	=>	'application/octet-stream',
		'oda'	=>	'application/oda',
		'pdf'	=>	array('application/pdf', 'application/x-download'),
		'ai'	=>	'application/postscript',
		'eps'	=>	'application/postscript',
		'ps'	=>	'application/postscript',
		'smi'	=>	'application/smil',
		'smil'	=>	'application/smil',
		'mif'	=>	'application/vnd.mif',
		'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
		'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
		'wbxml'	=>	'application/wbxml',
		'wmlc'	=>	'application/wmlc',
		'dcr'	=>	'application/x-director',
		'dir'	=>	'application/x-director',
		'dxr'	=>	'application/x-director',
		'dvi'	=>	'application/x-dvi',
		'gtar'	=>	'application/x-gtar',
		'gz'	=>	'application/x-gzip',
		'php'	=>	'application/x-httpd-php',
		'php4'	=>	'application/x-httpd-php',
		'php3'	=>	'application/x-httpd-php',
		'phtml'	=>	'application/x-httpd-php',
		'phps'	=>	'application/x-httpd-php-source',
		'js'	=>	'application/x-javascript',
		'swf'	=>	'application/x-shockwave-flash',
		'sit'	=>	'application/x-stuffit',
		'tar'	=>	'application/x-tar',
		'tgz'	=>	array('application/x-tar', 'application/x-gzip-compressed'),
		'xhtml'	=>	'application/xhtml+xml',
		'xht'	=>	'application/xhtml+xml',
		'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
		'mid'	=>	'audio/midi',
		'midi'	=>	'audio/midi',
		'mpga'	=>	'audio/mpeg',
		'mp2'	=>	'audio/mpeg',
		'mp3'	=>	array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
		'aif'	=>	'audio/x-aiff',
		'aiff'	=>	'audio/x-aiff',
		'aifc'	=>	'audio/x-aiff',
		'ram'	=>	'audio/x-pn-realaudio',
		'rm'	=>	'audio/x-pn-realaudio',
		'rpm'	=>	'audio/x-pn-realaudio-plugin',
		'ra'	=>	'audio/x-realaudio',
		'rv'	=>	'video/vnd.rn-realvideo',
		'wav'	=>	array('audio/x-wav', 'audio/wave', 'audio/wav'),
		'bmp'	=>	array('image/bmp', 'image/x-windows-bmp'),
		'gif'	=>	'image/gif',
		'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
		'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
		'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
		'png'	=>	array('image/png',  'image/x-png'),
		'tiff'	=>	'image/tiff',
		'tif'	=>	'image/tiff',
		'css'	=>	'text/css',
		'html'	=>	'text/html',
		'htm'	=>	'text/html',
		'shtml'	=>	'text/html',
		'txt'	=>	'text/plain',
		'text'	=>	'text/plain',
		'log'	=>	array('text/plain', 'text/x-log'),
		'rtx'	=>	'text/richtext',
		'rtf'	=>	'text/rtf',
		'xml'	=>	'text/xml',
		'xsl'	=>	'text/xml',
		'mpeg'	=>	'video/mpeg',
		'mpg'	=>	'video/mpeg',
		'mpe'	=>	'video/mpeg',
		'qt'	=>	'video/quicktime',
		'mov'	=>	'video/quicktime',
		'avi'	=>	'video/x-msvideo',
		'movie'	=>	'video/x-sgi-movie',
		'doc'	=>	'application/msword',
		'docx'	=>	array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'),
		'xlsx'	=>	array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'),
		'word'	=>	array('application/msword', 'application/octet-stream'),
		'xl'	=>	'application/excel',
		'eml'	=>	'message/rfc822',
		'json' 	=> 	array('application/json', 'text/json')
	);


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
	public static $ipMethodes	=	array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
	);



	/**
	 * @var array Liste nicht erlaubter Schnippsel in Strings
	 */
	protected static $_never_allowed_str = array(
		'document.cookie'	=> '[removed]',
		'document.write'	=> '[removed]',
		'.parentNode'		=> '[removed]',
		'.innerHTML'		=> '[removed]',
		'window.location'	=> '[removed]',
		'-moz-binding'		=> '[removed]',
		'<!--'				=> '&lt;!--',
		'-->'				=> '--&gt;',
		'<![CDATA['			=> '&lt;![CDATA[',
		'<comment>'			=> '&lt;comment&gt;'
	);

	/**
	 * @var array Liste nicht erlaubter Regex Funde in Strings
	 */
	protected static $_never_allowed_regex = array(
		'javascript\s*:',
		'expression\s*(\(|&\#40;)', // CSS and IE
		'vbscript\s*:', // IE, surprise!
		'Redirect\s+302',
		"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
	);

	/**
	 * Kontrolliert eine Variable auf Sicherheit und Konvertiert diese zur Sicherheit auch in das gewünschte Format.
	 *
	 * @param mixed $variable Die Variable die überprüft werden soll.
	 * @param string $convert Der Typ in den der Rückgabewert konvertiert werden soll.
	 *
	 * @return mixed Gibt die konvertierte Variable zurück
	 */
	public static function control($variable, $convert = null)
	{
		$variable	=	trim($variable);

		return self::_controllSecurity($variable, $convert);
	}

	/**
	 * Sicherer Weg um $_GET, $_POST, $_SESSION, $_COOKIE, $_SERVER, $_ENV und $_REQUEST aufzufangen,
	 * man sollte immer über diese Funktion Daten abrufen und prüfen lassen.
	 *
	 * @param string $variable Zu überprüfender Wert in $input
	 * @param string $input Die Quelle in der der Wert von $variable liegt.
	 * @param string $convert Der Typ in den der Rückgabewert konvertiert werden soll.
	 *
	 * @return mixed Gibt den überprüften Wert konvertiert zurück.
	 */
	public static function url($variable, $input = null, $convert = null)
	{
		$request	=	'';

		switch(strtolower($input))
		{
			case 'post':

				if(!empty($_POST[$variable]))
				{
					$request	=	filter_var($_POST[$variable]);
				}

			break;
			case 'get':

				if(!empty($_GET[$variable]))
				{
					$request	=	filter_var($_GET[$variable]);
				}

			break;
			case 'session':

				if(!empty($_SESSION[$variable]))
				{
					$request	=	filter_var($_SESSION[$variable]);
				}

			break;
			case 'cookie':

				if(!empty($_COOKIE[$variable]))
				{
					$request	=	filter_var($_COOKIE[$variable]);
				}

			break;
			case 'server':

				if(!empty($_SERVER[$variable]))
				{
					$request	=	filter_var($_SERVER[$variable]);
				}

			break;
			case 'env':

				if(!empty($_ENV[$variable]))
				{
					$request	=	filter_var($_ENV[$variable]);
				}

			break;
			case 'request':
			default:

				if(!empty($_REQUEST[$variable]))
				{
					$request	=	filter_var($_REQUEST[$variable]);
				}

			break;
		}

		return self::_controllSecurity($request, $convert);
	}


	/**
	 * Sicherheitskontrolle einer Variable
	 *
	 * @param mixed $request
	 * @param string $convert
	 * @return mixed
	 */
	protected static function _controllSecurity($request, $convert)
	{
		$param	=	self::xss_clean($request);
		$param	=	strip_tags($param, '<br><br />,<br/>');

		switch(strtolower($convert))
		{
			case 'mail':
			case 'email':
			case 'e':

				if(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$/i', $param) || strlen($param) > 50 || !filter_var($param, FILTER_VALIDATE_EMAIL))
				{
					return false;
				}

			break;
			case 'number':
			case 'num':
			case 'dec':
			case 'decimal':
			case 'double':
			case 'float':
			case 'floatval':
			case 'n':

				$param	=	floatval($param);

				if(Validater::isFloat($param) === false)
				{
					return false;
				}

			break;
			case 'integer':
			case 'int':
			case 'long':
			case 'i':

				$param	=	(int)$param;

				if(Validater::isInteger($param) === false)
				{
					return false;
				}

			break;
			case 'boolean':
			case 'bool':
			case 'b':

				$param	=	(bool)$param;

				if(Validater::isBoolean($param) === false)
				{
					return null;
				}

			break;
			default:
			case 'str':
			case 'string':
			case 's':

				$param	=	(string)$param;
				$param	=	trim($param);

				if(Validater::isString($param) === false)
				{
					return false;
				}

			break;
		}

		return $param;
	}



	/**
	 * Gibt den aktuellen MIME-Type zurück
	 *
	 * @param $path Der relative Pfad zur übeprüfenden Datei.
	 * @throws \Exception
	 * @return bool|mixed Gibt MIME-Type zurück.
	 */
	public static function get_mime_type($path)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'security', 'getMimeType', array($path));
			$plugins	=	plugins::hookCall('before', 'security', 'getMimeType', array($path));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(function_exists('finfo_open') === false)
		{
			throw new \Exception('finfo extensio not loaded');
		}

		$finfo 		= 	finfo_open(FILEINFO_MIME_TYPE);
		$mime_type 	= 	finfo_file($finfo, $path);

		finfo_close($finfo);

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'security', 'getMimeType', array($mime_type));
			$plugins	=	plugins::hookCall('after', 'security', 'getMimeType', array($mime_type));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $mime_type;
	}


	/**
	 * Gibt die Dateiendung zurück die für diese Datei die richtige ist
	 *
	 * @param string $path Der realative Pfad zur Datei.
	 * @throws \Exception
	 * @return mixed Gibt die Dateierweiterung zurück.
	 */
	public static function getFileType($path)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'security', 'getFileType', array($path));
			$plugins	=	plugins::hookCall('before', 'security', 'getFileType', array($path));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(function_exists('finfo_open') === false)
		{
			throw new \Exception('finfo extension not loaded');
		}

		$finfo 		= 	finfo_open(FILEINFO_MIME_TYPE);
		$mime_type 	= 	finfo_file($finfo, $path);

		finfo_close($finfo);

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'security', 'getFileType', array($mime_type));
			$plugins	=	plugins::hookCall('after', 'security', 'getFileType', array($mime_type));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		foreach(self::$mimes as $mime => $value)
		{
			if(is_array($value) === true && count($value) > 1)
			{
				foreach($value as $f)
				{
					if($f == $mime_type)
					{
						return $mime;
					}
				}
			}
			else
			{
				if($value == $mime_type)
				{
					return $mime;
				}
			}
		}

		return '';
	}

	/**
	 * Verschlüsselt einen String
	 *
	 * @param string $string Der String der Verschlüsselt werden soll.
	 * @throws \Exception
	 * @return string Gibt den SHA512 Verschlüsselten String zurück.
	 */
	public static function shaSec($string)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'security', 'shaSec', array($string));
			$plugins	=	plugins::hookCall('before', 'security', 'shaSec', array($string));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(function_exists('hash_hmac') === false)
		{
			throw new \Exception('hash extension not loaded');
		}

		$crypt	=	hash_hmac("sha512", $string, SECURITY_KEY);

		return $crypt;
	}


	/**
	 * Säubert einen String vor schädlichen XSS Code
	 *
	 * @param string $str
	 * @return string $str
	 */
	protected static function xss_clean($str)
	{
		if(is_array($str) === true)
		{
			while(list($key) = each($str))
			{
				$str[$key]	=	self::xss_clean($str[$key]);
			}

			return $str;
		}

		$str	=	self::remove_invisible_characters($str);
		$str	= 	self::_validate_entities($str);

		/*
		 * URL Decode
		 *
		 * Just in case stuff like this is submitted:
		 *
		 * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
		 *
		 * Note: Use rawurldecode() so it does not remove plus signs
		 *
		 */
		$str	=	rawurldecode($str);

		/*
		 * Convert character entities to ASCII
		 *
		 * This permits our tests below to work reliably.
		 * We only convert entities that are within tags since
		 * these are the ones that will pose security problems.
		 *
		 */
		$str	=	preg_replace_callback("/[a-z]+=([\'\"]).*?\\1/si", array('self', '_convert_attribute'), $str);
		$str	=	preg_replace_callback("/<\w+.*?(?=>|<|$)/si", array('self', '_decode_entity'), $str);

		/*
		 * Remove Invisible Characters Again!
		 */
		$str	=	self::remove_invisible_characters($str);

		/*
		 * Convert all tabs to spaces
		 *
		 * This prevents strings like this: ja	vascript
		 * NOTE: we deal with spaces between characters later.
		 * NOTE: preg_replace was found to be amazingly slow here on
		 * large blocks of data, so we use str_replace.
		 */
		if(strpos($str, "\t") !== false)
		{
			$str	=	str_replace("\t", ' ', $str);
		}


		// Remove Strings that are never allowed
		$str	=	self::_do_never_allowed($str);

		return $str;
	}


	/**
	 * Nicht erlaubte Regex Funde werden hier durchgespielt
	 *
	 * @param string
	 * @return string
	 */
	protected static function _do_never_allowed($str)
	{
		$neverAllowedStr	=	self::$_never_allowed_str;

		$str	=	str_replace(array_keys($neverAllowedStr), $neverAllowedStr, $str);

		$neverAllowedRegex	=	self::$_never_allowed_regex;

		foreach($neverAllowedRegex as $regex)
		{
			$str	=	preg_replace('#'.$regex.'#is', '[removed]', $str);
		}

		return $str;
	}

	/**
	 * HTML Entity Decode Callback
	 *
	 * @param array
	 * @return string
	 */
	protected static function _decode_entity($match)
	{
		return self::entity_decode($match[0], strtoupper('UTF-8'));
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
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public static function entity_decode($str, $charset='UTF-8')
	{
		if(stristr($str, '&') === false)
		{
			return $str;
		}

		$str	=	html_entity_decode($str, ENT_COMPAT, $charset);
		$str	= 	preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);

		return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
	}

	/**
	 * Attribute Conversion
	 *
	 * Used as a callback for XSS Clean
	 *
	 * @param	array
	 * @return	string
	 */
	protected static function _convert_attribute($match)
	{
		return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
	}


	/**
	 * Validiert die Zeichen in einem String
	 *
	 * @param string $str
	 * @return mixed
	 */
	protected static function _validate_entities($str)
	{
		/*
		 * Protect GET variables in URLs
		 */
		 // 901119URL5918AMP18930PROTECT8198
		$str	=	preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-]+)|i', self::xss_hash()."\\1=\\2", $str);

		/*
		 * Validate standard character entities
		 *
		 * Add a semicolon if missing.  We do this to enable
		 * the conversion of entities to ASCII later.
		 *
		 */
		$str	=	preg_replace('#(&\#?[0-9a-z]{2,})([\x00-\x20])*;?#i', "\\1;\\2", $str);

		/*
		 * Validate UTF16 two byte encoding (x00)
		 *
		 * Just as above, adds a semicolon if missing.
		 *
		 */
		$str	=	preg_replace('#(&\#x?)([0-9A-F]+);?#i',"\\1\\2;",$str);

		/*
		 * Un-Protect GET variables in URLs
		 */
		$str	=	str_replace(self::xss_hash(), '&', $str);

		return $str;
	}


	/**
	 * Erzeugt einen XSS-Hash
	 *
	 * @return string
	 */
	public static function xss_hash()
	{
		if(empty(self::$_xss_hash))
		{
			mt_srand();
			self::$_xss_hash = md5(time() + mt_rand(0, 1999999999));
		}

		return self::$_xss_hash;
	}


	/**
	 * Entfernt leere Zeichen aus einem String
	 *
	 * @param string $str
	 * @param bool $url_encoded
	 * @return mixed
	 */
	public static function remove_invisible_characters($str, $url_encoded = true)
	{
		$non_displayables	=	array();

		if($url_encoded === true)
		{
			$non_displayables[]	=	'/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = 	'/%1[0-9a-f]/';		// url encoded 16-31
		}

		$non_displayables[]	=	'/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do{
			$str	=	preg_replace($non_displayables, '', $str, -1, $count);
		}while($count);

		return $str;
	}


	/**
	 * Erstellt ein Zufallspasswort
	 *
	 * @param string $length Die Länge des Zufallsstrings
	 * @return string Gibt den generierten Zufallsstring zurück
	 */
	public static function random_string($length = '8')
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'security', 'randomString', array($length));
			$plugin	=	plugins::hookCall('before', 'security', 'randomString', array($length));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$rand	=	"";
		$c		= 	"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@&*$";

		srand((double)microtime() * 1000000);

		for($i = -1; ++$i < $length;)
		{
			$rand	.= $c[rand()%strlen($c)];
		}

		return $rand;
	}


	/**
	 * Kontrolliert ob es sich um den
	 * Benutzer um einen Bot handelt.
	 *
	 * @return array Gibt ein assoziatives Array zurück mit Informationen über die Auswertung.
	 */
	public static function is_bot()
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('before', 'security', 'isBot');

			if($plugins != null)
			{
				return $plugins;
			}
		}

		foreach(self::$botlist as $bot)
		{
			if(strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
			{
				return array('isBot' => true, 'version' => $bot);
			}
		}

		return array('isBot' => false, 'version' => '');
	}


	/**
	 * Gibt die aktuelle IP-Adresse des Benutzers zurück
	 *
	 * @return mixed Gibt die Ip-Adresse zurück oder ein false wenn diese nicht ermittelt werden konnte.
	 */
	public static function get_ip_address()
	{
		if(class_exists('\package\plugins'))
		{
			$plugin	=	plugins::hookCall('before', 'security', 'getIpAddress');

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$ipMethodes	=	self::$ipMethodes;

		foreach($ipMethodes as $key)
		{
			if(empty($_SERVER[$key]) === false)
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
			elseif(getenv($key) !== false)
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


	/**
	 * Gibt die MAC-Adresse zurück
	 *
	 * @return string Gibt bei UNIX Servern die MAC-Adresse zurück. Bei Windows Servern einen leeren String.
	 */
	public function getMacAdress()
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'security', 'getMacAdress');

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(OS == 'WIN')
		{
			return '';
		}

		ob_start();
		system('ipconfig /all');
		$mycom	=	ob_get_contents();
		ob_clean();

		$findme = 	"Physical";
		$pmac 	= 	strpos($mycom, $findme);
		$mac	=	substr($mycom,($pmac+36),17);

		return $mac;
	}
} 