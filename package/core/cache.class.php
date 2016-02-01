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
 * Cachen von Dateien oder Daten
 *
 * Wenn man Daten / Dateien für eine längere Zeit Cachen möchte, ist die Cache Klasse die richtige Anlaufstelle.
 * Sie speichert Daten aber auch z.b. Kompilierte PHP Dateien ab.
 * Diese Cache Dateien sind komprimiert und können so schneller vom Server an den Browser des Benutzers zurück
 * gesendet werden.
 *
 * @package		Webpackages
 * @subpackage	core
 * @category	Cache
 * @author		Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class cache implements IStatic
{
	/**
	 * @var string Der Pfad zum Cache Verzeichnis
	 */
	private static $cacheDir;

	/**
	 * @var bool Die Angabe ob der Cache aktiv ist oder nicht
	 */
	private static $cacheActiv;

	/**
	 * @var string Die Dateiendung einer jeden Cache Datei
	 */
	private static $cacheExtension = '.cache';

	/**
	 * Setzt die Standard Werte für den Cache
	 * Wird von load_functions.abstract.class.php verwendet
	 *
	 * cache constructor.
	 */
	public static function init()
	{
		if(empty(CACHE_PATH) === false)
		{
			self::set_cache_dir(CACHE_PATH);
		}

		if(empty(CACHE_EXTENSION) === false)
		{
			self::set_cache_extension(CACHE_EXTENSION);
		}
	}


	/**
	 * Setzt den Cache Pfad
	 *
	 * @param string $cachePath Der Ordner wo alle Gecachten Dateien abgespeichert werden sollen
	 * @return boolean Gibt im Fehlerfall ein false zurück
	 */
	public static function set_cache_dir($cachePath)
	{
		if(empty($cachePath) === true || is_dir($cachePath) === false)
		{
			return false;
		}

		if(file_exists($cachePath) === false)
		{
			self::$cacheDir	=	$cachePath;

			return mkdir(self::$cacheDir, 0777, true);
		}
		else
		{
			self::$cacheDir	=	$cachePath;
			return true;
		}
	}


	/**
	 * Aktiviert / Deaktiviert den Cache
	 *
	 * @param bool $active Aktiviert bzw Deaktiviert den Cache
	 * @return void
	 */
	public static function set_cache_active($active = false)
	{
		self::$cacheActiv	=	$active;
	}


	/**
	 * Setzt die Cache Extension
	 *
	 * @param string $extension Die Cache Extension ist die Dateiendung jeder Cache Datei
	 * @throws \Exception Wenn der Parameter leer ist.
	 * @return void
	 */
	public static function set_cache_extension($extension = 'cache')
	{
		if(empty($extension))
		{
			throw new \Exception('extension is empty');
		}

		$extension	=	trim($extension);
		$extension	=	trim($extension, '.');
		$extension	=	trim($extension);

		self::$cacheExtension = '.'.$extension;
	}


	/**
	 * Cache eine HTML Template Datei
	 *
	 * @param string $cache_name Der eindeutige Cache Name
	 * @param string $content Der Inhalt der Cache Datei
	 * @return bool
	 */
	public static function set_template_element($cache_name, $content)
	{
		$isSave	=	file_put_contents(self::$cacheDir.$cache_name.'.html', $content);

		if($isSave === false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}


	/**
	 * Gibt den HTML Link zurück wenn die Cache Datei nicht abgelaufen ist
	 *
	 * @param string $cache_name Der eindeutige Cache Name
	 * @param int $lifetime Die maximale Lebensdauer der Cache Datei
	 * @return bool|string Gibt den Link zur gecachten Datei zurück, false wenn Lebensdauer abgelaufen oder gecachte Datei nicht existiert
	 */
	public static function get_template_element($cache_name, $lifetime = 500)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'cache', 'get_template_element', array($cache_name, $lifetime));
			$plugin	=	plugins::hookCall('before', 'cache', 'setElget_template_elementement', array($cache_name, $lifetime));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$cacheFile	=	self::$cacheDir.$cache_name.'.html';

		if(file_exists($cacheFile) === false)
		{
			return false;
		}

		$filemtime	=	@filemtime($cacheFile);

		if($filemtime === false)
		{
			return false;
		}

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'cache', 'get_template_element', array($cacheFile));
			$plugin	=	plugins::hookCall('after', 'cache', 'get_template_element', array($cacheFile));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(($filemtime + $lifetime) >= time() || $lifetime === 0)
		{
			return $cacheFile;
		}
		else
		{
			@unlink($cacheFile);

			return false;
		}
	}



	/**
	 * Setzt einen Cache Eintrag
	 *
	 * @param string $cache_name Eindeutiger Cache Name
	 * @param mixed $content Inhalt der zu cachenden Datei
	 * @param int $lifetime Die maximale Lebensdauer der Cache Datei
	 * @return bool Wenn Cache erfolgreich erstellt true, wenn es Probleme gab false (z.b. Cache nicht aktiv oder wenn die Cache Datei nicht abgespeichert werden konnte)
	 * @throws \Exception Wenn der $cache_name leer ist
	 */
	public static function set_element($cache_name, $content, $lifetime = 500)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'cache', 'setElement', array($cache_name, $content, $lifetime));
			$plugin	=	plugins::hookCall('before', 'cache', 'setElement', array($cache_name, $content, $lifetime));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		if(empty($cache_name) === true)
		{
			throw new \Exception('cache name is empty');
		}

		$cache_name	=	md5($cache_name);

		if(empty($cache_name) === true || self::$cacheActiv === false)
		{
			return false;
		}

		$serialize	=	serialize(array('lifetime' => (time() + $lifetime), 'content' => $content));
		$cachePath	=	self::$cacheDir.$cache_name.self::$cacheExtension;
		$saveFile	=	file_put_contents($cachePath, $serialize);

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'cache', 'setElement', array($cachePath));
			$plugin	=	plugins::hookCall('after', 'cache', 'setElement', array($cachePath));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		if($saveFile === false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}


	/**
	 * Gibt das gültige Cache Element zurück
	 *
	 * @param string $cache_name Der eindeutige Name der gecachten Datei
	 * @return mixed Gibt den Inhalt des Caches zurück, false wenn Datei nicht existiert oder Lebensdauer abgelaufen
	 * @throws \Exception Wenn $cache_name leer ist.
	 */
	public static function get_element($cache_name)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'cache', 'getElement', array($cache_name));
			$plugin	=	plugins::hookCall('before', 'cache', 'getElement', array($cache_name));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(empty($cache_name) === true)
		{
			throw new \Exception('cache name is empty');
		}

		$cache_name	=	md5($cache_name);
		$filename	=	self::$cacheDir.$cache_name.self::$cacheExtension;

		if(is_file($filename) === true)
		{
			$getContent		=	@file_get_contents($filename);

			if(empty($getContent) === false)
			{
				$unserialize	=	@unserialize($getContent);

				if($unserialize !== false && isset($unserialize['lifetime']) === true && $unserialize['lifetime'] >= time())
				{
					return $unserialize['content'];
				}
				else
				{
					unlink($filename);
					return false;
				}
			}
			else
			{
				unlink($filename);

				return false;
			}
		}
		else
		{
			return false;
		}
	}


	/**
	 * Entfernt eine Cache Datei
	 *
	 * @param string $cache_name Eindeutiger Name der Cache Datei
	 * @return bool Nach erfolgreichem löschen des Caches wird true zurück gegeben, bei einem Fehler false.
	 * @throws \Exception Wenn $cache_name leer ist.
	 */
	public static function delete_element($cache_name)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'cache', 'deleteElement', array($cache_name));
			$plugins	=	plugins::hookCall('before', 'cache', 'deleteElement', array($cache_name));

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		if(empty($cache_name) === true)
		{
			throw new \Exception('cache name is empty');
		}

		$filename	=	self::$cacheDir.md5($cache_name).self::$cacheExtension;
		$remove		=	true;

		if(is_file($filename) === true)
		{
			$remove	=	unlink($filename);
		}

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'cache', 'deleteElement', array($filename, $remove));
			$plugins	=	plugins::hookCall('after', 'cache', 'deleteElement', array($filename, $remove));

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		return $remove;
	}
} 