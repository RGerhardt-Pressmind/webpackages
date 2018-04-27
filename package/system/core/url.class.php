<?php
/**
 *  Copyright (C) 2010 - 2017  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2018.0
 * @filesource
 */

namespace package\core;

use package\exceptions\urlException;
use package\implement\IStatic;
use package\system\core\initiator;

/**
 * URL Klasse
 *
 * Mittels der URL Klasse kann man die aktuelle URL herausbekommen, kontrollieren ob die PHP Extension mod_rewrite
 * installiert ist oder einfache eine Ansammlung von Strings zu einer validen URL zusammenbauen lassen. All das und
 * vieles mehr kann die URL Klasse.
 *
 * @method static void set_use_mod_rewrite(bool $mod)
 * @method static void set_use_file_extension(string $extension)
 * @method static void loc_url_simple(array $parameters)
 * @method static string get_url_simple(string $httpRoot, array $parameters) @deprecated
 * @method static string get_modify_url(array $parameters, string $http_host = null)
 * @method static string createValidUrlString(string $url)
 * @method static mixed get_host_from_url(string $url)
 * @method static string getCurrentUrl()
 * @method static void loc(string $url)
 * @method static void reload()
 * @method static void back()
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       url
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class url extends initiator implements IStatic
{
	public static $isModRewriteActiv = false, $useModRewrite = false, $useFileExtension = 'html';

	public static function init()
	{
		if(function_exists('apache_get_modules'))
		{
			$allModules = apache_get_modules();

			if(in_array('mod_rewrite', $allModules))
			{
				self::$isModRewriteActiv = true;
			}
		}
		else
		{
			self::$isModRewriteActiv = true;
		}

		self::set_use_mod_rewrite(USE_MOD_REWRITE);
	}

	/**
	 * Setzt ModRewrite auf aktiv oder inaktiv
	 *
	 * @param bool $mod Setzt das mod_rewrite auf true oder false (aktiv oder inaktiv)
	 *
	 * @throws urlException
	 */
	protected static function _set_use_mod_rewrite($mod)
	{
		self::$useModRewrite = (bool)$mod;

		if(self::$useModRewrite && !self::$isModRewriteActiv)
		{
			throw new urlException('apache has mod_rewrite not activated');
		}
	}

	/**
	 * Setzt eine Dateiendung
	 *
	 * @param string $extension Setzt die Dateiendung für die mod_rewrite URL's
	 *
	 * @throws urlException
	 */
	protected function _set_use_file_extension($extension)
	{
		$extension = trim($extension, '.');
		$extension = trim($extension);

		if(empty($extension))
		{
			throw new urlException('set_use_file_extension file extension is empty');
		}

		self::$useFileExtension = $extension;
	}

	/**
	 * Leitet auf eine Webseite direkt weiter.
	 * Ausgehende von der Konstante HTTP
	 *
	 * @param array $parameters Die Parameter die übergeben werden sollen.
	 * @return void
	 */
	protected static function _loc_url_simple($parameters)
	{
		self::_loc(self::get_modify_url($parameters));
	}

	/**
	 * Wandelt einen Link in ModRewrite um oder
	 * gibt Ihn als normalen GET Text zurück
	 *
	 * @deprecated
	 *
	 * @param string $httpRoot   Die HTTP-Root URL vom Webserver (beispiel von google: http://www.google.de/)
	 * @param array  $parameters Die Parameter die übergeben werden sollen.
	 *
	 * @return string $link
	 */
	protected static function _get_url_simple($httpRoot, $parameters)
	{
		return self::get_modify_url($parameters, $httpRoot);
	}

	/**
	 * Wandelt eine URL, je nach Einstellung, in eine lesbare URL um (mod_rewrite)
	 *
	 * @param array $parameters
	 * @param null $http_host
	 *
	 * @return string
	 */
	protected static function _get_modify_url($parameters, $http_host = null)
	{
		$link = (empty($http_host) ? HTTP : $http_host);

		if(!empty($parameters))
		{
			if(self::$useModRewrite)
			{
				foreach($parameters as $v)
				{
					if(is_int($v))
					{
						$link = trim($link, '/').'_'.$v.'/';
					}
					else
					{
						$link .= $v.'/';
					}
				}

				$link = trim($link, '/');
				$link .= '.'.self::$useFileExtension;
			}
			else
			{
				$firstRun = true;

				foreach($parameters as $k => $v)
				{
					$link .= (($firstRun) ? '?' : '&').$k.'='.$v;

					$firstRun = false;
				}
			}
		}

		return $link;
	}

	/**
	 * Validiert eine URL und gibt diese "Sauber" zurück
	 *
	 * @param string $url Die URL die Validiert werden soll. Erlaubt sind alle Buchstaben, Zahlen und
	 *                    $-_.+!*'{}|^~[]`#%/?@&= Zeichen
	 *
	 * @return string Gibt den validierten String zurück
	 */
	protected static function _createValidUrlString($url)
	{
		$url = str_replace(array('Ü', 'Ä', 'Ö', 'ß'), array('Ue', 'Ae', 'Oe', 'ss'), $url);
		$url = strtolower($url);
		$url = str_replace(array('ü', 'ä', 'ö', 'ß'), array('ue', 'ae', 'oe', 'ss'), $url);
		$url = preg_replace('/\s/', '-', $url);
		$url = preg_replace('/[^A-Za-z0-9\-\_]/', '', $url);
		$url = filter_var($url, FILTER_SANITIZE_URL);
		$url = preg_replace('/(-+)/', '-', $url);

		return $url;
	}

	/**
	 * Gibt die aktuelle URL zurück
	 *
	 * @return string Gibt die aktuelle URL zurück
	 */
	protected static function _getCurrentUrl()
	{
		return ((isset($_SERVER['HTTPS'])) ? 'https' : 'http').'://'.(!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost').''.((isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '');
	}

	/**
	 * Leitet den Browser auf die übergeben URL weiter
	 *
	 * @param string $url Die URL an denn der Aufruf weitergeleitet werden soll
	 *
	 * @return void
	 */
	protected static function _loc($url)
	{
		header('Location: '.$url);
		exit;
	}

	/**
	 * Gibt aus einer URL denn Host zurück
	 *
	 * @param string $url
	 *
	 * @return mixed
	 */
	protected static function _get_host_from_url($url)
	{
		return parse_url($url, PHP_URL_HOST);
	}

	/**
	 * Aktualisiert die Seite
	 *
	 * @return void
	 */
	protected static function _reload()
	{
		self::loc(self::getCurrentUrl());
	}

	/**
	 * Schickt einen zur letzten Seite zurück (Javascript oder PHP)
	 *
	 * @return void
	 */
	protected static function _back()
	{
		if(!empty($_SERVER['HTTP_REFERER']))
		{
			self::loc($_SERVER['HTTP_REFERER']);
		}
		else
		{
			echo '
			<script type="text/javascript">
				history.back();
			</script>
			';
			exit;
		}
	}
}