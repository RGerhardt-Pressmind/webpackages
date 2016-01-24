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

    @category   language.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class language implements IStatic
{
	private static $userLng, $lngPath, $defaultLng = 'de_DE', $gettext_reader;

	/**
	 * Setzt die Standard Werte
	 *
	 * language constructor.
	 */
	public static function init()
	{
		if(empty(LANGUAGE_PATH) === false)
		{
			self::set_language_path(LANGUAGE_PATH);
		}

		if(empty(DEFAULT_LANGUAGE) === false)
		{
			self::set_default_language(DEFAULT_LANGUAGE);
		}

		self::load_lang(true);
	}


	/**
	 * Setzt die Benutzer Sprache
	 *
	 * @param string $lng
	 *
	 * @return void
	 */
	public static function set_language($lng)
	{
		self::$userLng	=	$lng;
		self::load_lang(true);
	}


	/**
	 * Gibt die Benutzer Sprache zurück
	 *
	 * @return mixed
	 */
	public static function get_language()
	{
		return self::$userLng;
	}


	/**
	 * Setzt den Sprach-Haupt-Ordner
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	public static function set_language_path($path)
	{
		self::$lngPath	=	$path;
	}


	/**
	 * Gibt den Sprach-Haupt-Ordner zurück
	 *
	 * @return mixed
	 */
	public static function get_language_path()
	{
		return self::$lngPath;
	}


	/**
	 * Setzt die Standard Sprache
	 *
	 * @param string $lng
	 *
	 * @return void
	 */
	public static function set_default_language($lng)
	{
		self::$defaultLng	=	$lng;
	}


	/**
	 * Gibt die Standard Sprache zurück
	 *
	 * @return string
	 */
	public static function get_default_language()
	{
		return self::$defaultLng;
	}


	/**
	 * Lädt die Sprachfunktionalität
	 *
	 * @param bool $ignoreFileExists Soll ignorieren ob die Sprachdatei existiert oder nicht. Standartmäßig false.
	 * @return boolean
	 */
	public static function load_lang($ignoreFileExists = false)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'language', 'loadLang', array($ignoreFileExists));
			$plugins	=	plugins::hookCall('before', 'language', 'loadLang', array($ignoreFileExists));

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		if(empty(self::$lngPath))
		{
			return false;
		}

		if(empty(self::$userLng))
		{
			self::$userLng	=	self::$defaultLng;
		}

		setlocale(LC_ALL, self::$userLng);

		$folderName	=	explode('.', self::$userLng);

		if($ignoreFileExists === false && file_exists(self::$lngPath.$folderName[0].SEP.self::$userLng.'.mo') === false)
		{
			return false;
		}

		$moFile	=	self::$lngPath.$folderName[0].SEP.self::$userLng.'.mo';

		$localeFile				=	new \FileReader($moFile);
		self::$gettext_reader	=	new \gettext_reader($localeFile);

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'language', 'loadLang', array($moFile));
			$plugins	=	plugins::hookCall('after', 'language', 'loadLang', array($moFile));

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		return true;
	}

	/**
	 * Tauscht die Variablen aus
	 *
	 * @param string $text Der String der übersetzt/ersetzt werden soll
	 * @return mixed Gibt den übersetzten String zurück
	 */
	public static function translate($text)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'language', 'translate', array($text));
			$plugins	=	plugins::hookCall('before', 'language', 'translate', array($text));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return self::$gettext_reader->translate($text);
	}
} 