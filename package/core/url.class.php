<?php
/*
    Copyright (C) 2016  <Robbyn Gerhardt>

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
    
    @category   url.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class url implements IStatic
{
	public static $isModRewriteActiv = false, $useModRewrite = false, $useFileExtension = 'html';

	public static function init()
	{
		if(function_exists('apache_get_modules'))
		{
			$allModules	=	apache_get_modules();

			if(in_array('mod_rewrite', $allModules))
			{
				self::$isModRewriteActiv	=	true;
			}
		}
		else
		{
			self::$isModRewriteActiv	=	true;
		}

		self::set_use_mod_rewrite(USE_MOD_REWRITE);
	}

	/**
	 * Setzt ModRewrite auf aktiv oder inaktiv
	 *
	 * @param $mod
	 * @throws \Exception
	 */
	public static function set_use_mod_rewrite($mod)
	{
		self::$useModRewrite	=	(bool)$mod;

		if(self::$useModRewrite && !self::$isModRewriteActiv)
		{
			throw new \Exception('apache has mod_rewrite not activated');
		}
	}


	/**
	 * Setzt eine Dateiendung
	 *
	 * @param string $extension
	 * @throws \Exception
	 */
	public function set_use_file_extension($extension)
	{
		$extension	=	trim($extension, '.');
		$extension	=	trim($extension);

		if(empty($extension))
		{
			throw new \Exception('mod_rewrite file extension is empty');
		}

		self::$useFileExtension	=	$extension;
	}


	/**
	 * Wandelt einen Link in ModRewrite um oder
	 * gibt Ihn als normalen GET Text zurück
	 *
	 * @param string $httpRoot
	 * @param array $parameters
	 * @return string $link
	 */
	public static function get_url_simple($httpRoot, $parameters)
	{
		$link	=	$httpRoot;

		if(!empty($parameters))
		{
			if(self::$useModRewrite === true)
			{
				foreach($parameters as $v)
				{
					if(is_int($v))
					{
						$link	=	trim($link, '/').'_'.$v.'/';
					}
					else
					{
						$link	.=	$v.'/';
					}
				}

				$link	=	trim($link, '/');
				$link	.=	'.'.self::$useFileExtension;
			}
			else
			{
				$firstRun	=	true;

				foreach($parameters as $k => $v)
				{
					$link	.=	(($firstRun) ? '?' : '&').$k.'='.$v;

					$firstRun	=	false;
				}
			}
		}

		return $link;
	}


	/**
	 * Validiert eine URL und gibt diese "Sauber" zurück
	 *
	 * @param string $url Die URL die Validiert werden soll. Erlaubt sind alle Buchstaben, Zahlen und $-_.+!*'{}|^~[]`#%/?@&= Zeichen
	 * @return string
	 */
	public static function createValidUrlString($url)
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'template', 'createValidURL', array($url));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$url	=	strtolower($url);
		$url	=	preg_replace('/\s/', '-', $url);
		$url 	=	filter_var($url, FILTER_SANITIZE_URL);

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'template', 'createValidURL', array($url));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $url;
	}


	/**
	 * Gibt die aktuelle URL zurück
	 *
	 * @return string Gibt die aktuelle URL zurück
	 */
	public static function getCurrentUrl()
	{
		if(isset($_SERVER['HTTP_HOST']) === true)
		{
			return (isset($_SERVER['HTTPS']) === true ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		else
		{
			return ((isset($_SERVER['HTTPS']) === true) ? 'https' : 'http').'://localhost'.((isset($_SERVER['REQUEST_URI']) === true) ? $_SERVER['REQUEST_URI'] : '');
		}
	}

	/**
	 * Leitet den Browser auf die übergeben URL weiter
	 *
	 * @param string $url Die URL die aufgerufen werden soll.
	 * @return void
	 */
	public static function loc($url)
	{
		header('Location: '.$url);
		exit;
	}


	/**
	 * Aktualisiert die Seite
	 *
	 * @return void
	 */
	public static function reload()
	{
		self::loc(self::getCurrentUrl());
		exit;
	}

	/**
	 * Schickt einen zur letzten Seite zurück (Javascript oder PHP)
	 *
	 * @return void
	 */
	public static function back()
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