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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\securityException;

/**
 * Überprüft jeglichen Input eines Benutzer
 *
 * Mit der security Klasse kann man jegliche Form von Angriff über Eingaben des Benutzers aufhalten. Ob XSS oder
 * SQL Injections, die security Klasse überprüft diese und gibt sie gereinigt zurück.
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       security
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class security
{
	/**
	 * @var array Erlaubte Bildtypen
	 */
	public static $allowedImages = array('image/gif', 'image/jpeg', 'image/png');

	/**
	 * @var array Erlaubte Dateitypen
	 */
	public static $allowedFiles = array('application/zip');

	/**
	 * @var string Der XSS-Hash
	 */
	public static $_xss_hash = '';

	/**
	 * @var array Liste aller bekannten Bots (stetig in Erweiterung)
	 */
	public static $botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi", "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory", "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot", "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp", "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz", "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot", "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler", "TweetmemeBot", "Butterfly", "Twitturls", "Me.dium", "Twiceler", "bing", "microsoft", "yahoo");

	/**
	 * @var array Liste aller erlaubten IP-Methoden
	 */
	public static $ipMethodes = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');

	/**
	 * @var array Liste nicht erlaubter Schnippsel in Strings
	 */
	protected static $_never_allowed_str = array('document.cookie' => '[removed]', 'document.write' => '[removed]', '.parentNode' => '[removed]', '.innerHTML' => '[removed]', '-moz-binding' => '[removed]', '<!--' => '&lt;!--', '-->' => '--&gt;', '<![CDATA[' => '&lt;![CDATA[', '<comment>' => '&lt;comment&gt;');

	/**
	 * @var array Liste nicht erlaubter Regex Funde in Strings (stetig in Erweiterung)
	 */
	protected static $_never_allowed_regex = array('javascript\s*:', '(document|(document\.)?window)\.(location|on\w*)', 'expression\s*(\(|&\#40;)', 'vbscript\s*:', 'wscript\s*:', 'jscript\s*:', 'vbs\s*:', 'Redirect\s+30\d', "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?");

	/**
	 * Kontrolliert eine Variable auf Sicherheit und Konvertiert diese zur Sicherheit auch in das gewünschte Format.
	 *
	 * @param mixed  $variable Die Variable die überprüft werden soll.
	 * @param string $convert  Der Typ in den der Rückgabewert konvertiert werden soll.
	 *
	 * @return mixed Gibt die konvertierte Variable zurück
	 */
	public static function control($variable, $convert = null)
	{
		$variable = trim($variable);

		return self::_controllSecurity($variable, $convert);
	}

	/**
	 * Sicherer Weg um $_GET, $_POST, $_SESSION, $_COOKIE, $_SERVER, $_ENV und $_REQUEST aufzufangen,
	 * man sollte immer über diese Funktion Daten abrufen und prüfen lassen.
	 *
	 * @param string $variable Zu überprüfender Wert in $input
	 * @param string $input    Die Quelle in der der Wert von $variable liegt.
	 * @param string $convert  Der Typ in den der Rückgabewert konvertiert werden soll.
	 *
	 * @return mixed Gibt den überprüften Wert konvertiert zurück.
	 */
	public static function url($variable, $input = null, $convert = null)
	{
		$request = '';

		switch(strtolower($input))
		{
			case 'post':

				if(empty($_POST[$variable]) === false)
				{
					$request = $_POST[$variable];
				}

			break;
			case 'get':

				if(empty($_GET[$variable]) === false)
				{
					$request = $_GET[$variable];
				}

			break;
			case 'session':

				if(empty($_SESSION[$variable]) === false)
				{
					$request = $_SESSION[$variable];
				}

			break;
			case 'cookie':

				if(empty($_COOKIE[$variable]) === false)
				{
					$request = $_COOKIE[$variable];
				}

			break;
			case 'server':

				if(empty($_SERVER[$variable]) === false)
				{
					$request = $_SERVER[$variable];
				}

			break;
			case 'env':

				if(empty($_ENV[$variable]) === false)
				{
					$request = $_ENV[$variable];
				}

			break;
			case 'request':
			default:

				if(empty($_REQUEST[$variable]) === false)
				{
					$request = $_REQUEST[$variable];
				}

			break;
		}

		return self::_controllSecurity($request, $convert);
	}

	/**
	 * Sicherheitskontrolle einer Variable
	 *
	 * @param mixed  $request
	 * @param string $convert
	 *
	 * @return mixed
	 */
	protected static function _controllSecurity($request, $convert)
	{
		$param   = $request;
		$convert = strtolower($convert);

		switch($convert)
		{
			case 'ip':

				$param = filter_var($param, FILTER_VALIDATE_IP);

				if($param === false)
				{
					return false;
				}

			break;
			case 'mail':
			case 'email':
			case 'e':

				$param = filter_var($param, FILTER_VALIDATE_EMAIL);

				if($param === false)
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

				$param = filter_var($param, FILTER_VALIDATE_FLOAT);

				if($param === false)
				{
					return false;
				}

			break;
			case 'integer':
			case 'int':
			case 'long':
			case 'i':

				$param = filter_var($param, FILTER_VALIDATE_INT);

				if($param === false)
				{
					return false;
				}

			break;
			case 'boolean':
			case 'bool':
			case 'b':

				$param = filter_var($param, FILTER_VALIDATE_BOOLEAN);

				if($param === null)
				{
					return null;
				}

			break;
			default:
			case 'str':
			case 'string':
			case 's':

				$param = self::xss_clean($param);
				$param = filter_var($param, FILTER_SANITIZE_STRING);

				if($param === false)
				{
					return false;
				}

				$param = trim($param);

			break;
		}

		return $param;
	}

	/**
	 * Gibt den aktuellen MIME-Type zurück
	 *
	 * @param string $path Der relative Pfad zur übeprüfenden Datei.
	 *
	 * @throws securityException
	 * @return bool|mixed Gibt MIME-Type zurück.
	 */
	public static function get_mime_type($path)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'security', 'get_mime_type', array($path));
			$plugins = plugins::hookCall('before', 'security', 'get_mime_type', array($path));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(function_exists('finfo_open') === false)
		{
			throw new securityException('finfo extensio not loaded');
		}

		$finfo     = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($finfo, $path);

		finfo_close($finfo);

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'security', 'get_mime_type', array($mime_type));
			$plugins = plugins::hookCall('after', 'security', 'get_mime_type', array($mime_type));

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
	 *
	 * @throws securityException
	 * @return mixed Gibt die Dateierweiterung zurück.
	 */
	public static function get_file_type($path)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'security', 'get_file_type', array($path));
			$plugins = plugins::hookCall('before', 'security', 'get_file_type', array($path));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(class_exists('\SplFileInfo') === false)
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
	public static function shaSec($string)
	{
		return self::sha_sec($string);
	}

	/**
	 * Verschlüsselt einen String
	 *
	 * @param string $string Der String der Verschlüsselt werden soll.
	 *
	 * @throws securityException
	 * @return string Gibt den SHA512 Verschlüsselten String zurück.
	 */
	public static function sha_sec($string)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'security', 'sha_sec', array($string));
			$plugins = plugins::hookCall('before', 'security', 'sha_sec', array($string));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(function_exists('hash_hmac') === false)
		{
			throw new securityException('hash extension not loaded');
		}

		$crypt = hash_hmac("sha512", $string, SECURITY_KEY);

		return $crypt;
	}

	/**
	 * Säubert einen String vor schädlichen XSS Code
	 *
	 * Based on Codeigniter
	 *
	 * @link https://www.codeigniter.com/
	 *
	 * @param string $str Der String der von XSS gesäubert werden soll
	 *
	 * @return string $str
	 */
	protected static function xss_clean($str)
	{
		// Remove Invisible Characters
		$str = self::remove_invisible_characters($str);

		/*
		 * URL Decode
		 *
		 * Just in case stuff like this is submitted:
		 *
		 * <a href="http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D">Google</a>
		 *
		 * Note: Use rawurldecode() so it does not remove plus signs
		 */
		while(true)
		{
			if(preg_match('/%[0-9a-f]{2,}/i', $str) !== 1)
			{
				break;
			}

			$str = rawurldecode($str);
		}

		/*
		 * Convert character entities to ASCII
		 *
		 * This permits our tests below to work reliably.
		 * We only convert entities that are within tags since
		 * these are the ones that will pose security problems.
		 */
		$str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", array('self', '_convert_attribute'), $str);
		$str = preg_replace_callback('/<\w+.*/si', array('self', '_decode_entity'), $str);

		// Remove Invisible Characters Again!
		$str = self::remove_invisible_characters($str);

		/*
		 * Convert all tabs to spaces
		 *
		 * This prevents strings like this: ja	vascript
		 * large blocks of data, so we use str_replace.
		 */
		$str = str_replace("\t", ' ', $str);

		// Remove Strings that are never allowed
		$str = self::_do_never_allowed($str);
		$str = str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $str);

		/*
		 * Compact any exploded words
		 *
		 * This corrects words like:  j a v a s c r i p t
		 * These words are compacted back to their correct state.
		 */
		$words = array('javascript', 'expression', 'vbscript', 'jscript', 'wscript', 'vbs', 'script', 'base64', 'applet', 'alert', 'document', 'write', 'cookie', 'window', 'confirm', 'prompt', 'eval');

		foreach($words as $word)
		{
			$word = implode('\s*', str_split($word)).'\s*';
			$str  = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array('self', '_compact_exploded_words'), $str);
		}

		/*
		 * Remove disallowed Javascript in links or img tags
		 * We used to do some version comparisons and use of stripos(),
		 * but it is dog slow compared to these simplified non-capturing
		 * preg_match(), especially if the pattern exists in the string
		 *
		 * Note: It was reported that not only space characters, but all in
		 * the following pattern can be parsed as separators between a tag name
		 * and its attributes: [\d\s"\'`;,\/\=\(\x00\x0B\x09\x0C]
		 * ... however, remove_invisible_characters() above already strips the
		 * hex-encoded ones, so we'll skip them below.
		 */

		while(true)
		{
			$original = $str;

			if(preg_match('/<a/i', $str) === 1)
			{
				$str = preg_replace_callback('#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si', array('self', '_js_link_removal'), $str);
			}

			if(preg_match('/<img/i', $str) === 1)
			{
				$str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', array('self', '_js_img_removal'), $str);
			}

			if(preg_match('/script|xss/i', $str) === 1)
			{
				$str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
			}

			if($original === $str)
			{
				unset($original);
				break;
			}
		}

		/*
		 * Sanitize naughty HTML elements
		 *
		 * If a tag containing any of the words in the list
		 * below is found, the tag gets converted to entities.
		 *
		 * So this: <blink>
		 * Becomes: &lt;blink&gt;
		 */
		$pattern = '#'.'<((?<slash>/*\s*)(?<tagName>[a-z0-9]+)(?=[^a-z0-9]|$)' // tag start and name, followed by a non-tag character
			.'[^\s\042\047a-z0-9>/=]*' // a valid attribute character immediately after the tag would count as a separator
			// optional attributes
			.'(?<attributes>(?:[\s\042\047/=]*' // non-attribute characters, excluding > (tag close) for obvious reasons
			.'[^\s\042\047>/=]+' // attribute characters
			// optional attribute-value
			.'(?:\s*=' // attribute-value separator
			.'(?:[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*))' // single, double or non-quoted value
			.')?' // end optional attribute-value group
			.')*)' // end optional attributes group
			.'[^>]*)(?<closeTag>\>)?#isS';

		while(true)
		{
			$old_str = $str;
			$str     = preg_replace_callback($pattern, array('self', '_sanitize_naughty_html'), $str);

			if($old_str === $str)
			{
				unset($old_str);
				break;
			}
		}

		/*
		 * Sanitize naughty scripting elements
		 *
		 * Similar to above, only instead of looking for
		 * tags it looks for PHP and JavaScript commands
		 * that are disallowed. Rather than removing the
		 * code, it simply converts the parenthesis to entities
		 * rendering the code un-executable.
		 *
		 * For example:	eval('some code')
		 * Becomes:	eval&#40;'some code'&#41;
		 */
		$str = preg_replace('#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', '\\1\\2&#40;\\3&#41;', $str);

		// Final clean up
		$str = self::_do_never_allowed($str);

		return $str;
	}

	/**
	 * Sanitize Naughty HTML
	 *
	 * Callback method for xss_clean() to remove naughty HTML elements.
	 *
	 * @used-by    CI_Security::xss_clean()
	 *
	 * @param    array $matches
	 *
	 * @return    string
	 */
	protected static function _sanitize_naughty_html($matches)
	{
		$naughty_tags = array('alert', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound', 'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer', 'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object', 'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss');

		$evil_attributes = array('on\w+', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime');

		// First, escape unclosed tags
		if(empty($matches['closeTag']) === true)
		{
			return '&lt;'.$matches[1];
		}
		elseif(in_array(strtolower($matches['tagName']), $naughty_tags, true) === true) // Is the element that we caught naughty? If so, escape it
		{
			return '&lt;'.$matches[1].'&gt;';
		}
		elseif(isset($matches['attributes']) === true) // For other tags, see if their attributes are "evil" and strip those
		{
			$attributes = array();

			$attributes_pattern = '#(?<name>[^\s\042\047>/=]+)(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))#i';

			$is_evil_pattern = '#^('.implode('|', $evil_attributes).')$#i';

			while(true)
			{
				$matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);

				if(preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE) !== 1)
				{
					break;
				}

				if(preg_match($is_evil_pattern, $attribute['name'][0]) === 1 || (trim($attribute['value'][0]) === ''))
				{
					$attributes[] = 'xss=removed';
				}
				else
				{
					$attributes[] = $attribute[0][0];
				}

				$matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));

				if($matches['attributes'] === '')
				{
					break;
				}
			}

			$attributes = (empty($attributes) === true ? '' : ' '.implode(' ', $attributes));

			return '<'.$matches['slash'].$matches['tagName'].$attributes.'>';
		}

		return $matches[0];
	}

	/**
	 * Compact Exploded Words
	 *
	 * Callback method for xss_clean() to remove whitespace from
	 * things like 'j a v a s c r i p t'.
	 *
	 * @used-by    CI_Security::xss_clean()
	 *
	 * @param    array $matches
	 *
	 * @return    string
	 */
	protected static function _compact_exploded_words($matches)
	{
		return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
	}

	/**
	 * Nicht erlaubte Regex Funde werden hier durchgespielt
	 *
	 * @param string
	 *
	 * @return string
	 */
	protected static function _do_never_allowed($str)
	{
		$neverAllowedStr   = self::$_never_allowed_str;
		$str               = str_replace(array_keys($neverAllowedStr), $neverAllowedStr, $str);
		$neverAllowedRegex = self::$_never_allowed_regex;

		foreach($neverAllowedRegex as $regex)
		{
			$str = preg_replace('#'.$regex.'#is', '[removed]', $str);
		}

		return $str;
	}

	/**
	 * HTML Entity Decode Callback
	 *
	 * @param array
	 *
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
	 * @param    string
	 * @param    string
	 *
	 * @return    string
	 */
	public static function entity_decode($str, $charset = 'UTF-8')
	{
		if(stristr($str, '&') === false)
		{
			return $str;
		}

		$str = html_entity_decode($str, ENT_COMPAT, $charset);
		$str = preg_replace('~&#x(0*[0-9a-f]{2,5})~ei', 'chr(hexdec("\\1"))', $str);

		return preg_replace('~&#([0-9]{2,4})~e', 'chr(\\1)', $str);
	}

	/**
	 * Attribute Conversion
	 *
	 * Used as a callback for XSS Clean
	 *
	 * @param    array
	 *
	 * @return    string
	 */
	protected static function _convert_attribute($match)
	{
		return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
	}

	/**
	 * Entfernt leere Zeichen aus einem String
	 *
	 * @param string $str
	 * @param bool   $url_encoded
	 *
	 * @return mixed
	 */
	public static function remove_invisible_characters($str, $url_encoded = true)
	{
		$non_displayables = array();

		if($url_encoded === true)
		{
			$non_displayables[] = '/%0[0-8bcef]/';
			$non_displayables[] = '/%1[0-9a-f]/';
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';

		while(true)
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);

			if($count === 0)
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
	public static function is_bot()
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugins = plugins::hookCall('before', 'security', 'is_bot');

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(empty($_SERVER['HTTP_USER_AGENT']) === false)
		{
			foreach(self::$botlist as $bot)
			{
				if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
				{
					return array('isBot' => true, 'version' => $bot);
				}
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
			$plugin = plugins::hookCall('before', 'security', 'get_ip_address');

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$ipMethodes = self::$ipMethodes;

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
}