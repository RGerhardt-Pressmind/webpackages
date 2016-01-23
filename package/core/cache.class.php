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

    @category   cache.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class cache implements IStatic
{
	private static $cacheDir, $cacheActiv, $cacheExtension = '.cache';

	/**
	 * Setzt die Standard Werte
	 *
	 * cache constructor.
	 */
	public static function init()
	{
		if(empty(CACHE_PATH) === false)
		{
			self::setCacheDir(CACHE_PATH);
		}

		if(empty(CACHE_EXTENSION) === false)
		{
			self::setCacheExtension(CACHE_EXTENSION);
		}
	}


	/**
	 * Setzt den Cache Pfad
	 *
	 * @param string $cachePath Der Ordner wo alle Gecachten Dateien landen
	 * @return boolean
	 */
	public static function setCacheDir($cachePath)
	{
		if(empty($cachePath))
		{
			return false;
		}

		if(is_dir($cachePath) === false)
		{
			self::$cacheDir	=	$cachePath;

			return	mkdir(self::$cacheDir, 0777, true);
		}
		elseif(file_exists($cachePath) === true)
		{
			self::$cacheDir	=	$cachePath;
			return true;
		}

		return false;
	}


	/**
	 * Aktiviert / Deaktiviert den Cache
	 *
	 * @param bool $active Aktiviert bzw Deaktiviert den Cache
	 * @return void
	 */
	public static function setCacheActive($active = false)
	{
		self::$cacheActiv	=	$active;
	}


	/**
	 * Setzt die Cache Extension
	 *
	 * @param string $extension Die Cache Extension ist die Dateiendung jeder Cache Datei
	 *
	 * @throws \Exception
	 * @return void
	 */
	public static function setCacheExtension($extension = 'cache')
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
	 * Cachte eine HTML Template Datei
	 *
	 * @param string $cache_name Der eindeutige Cache Name
	 * @param string $content Der Inhalt der Cache Datei
	 *
	 * @return bool
	 */
	public static function setTemplateElement($cache_name, $content)
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
	 * Gibt den HTML Link zurück wenn Datei nicht abgelaufen
	 *
	 * @param string $cache_name Der eindeutige Cache Name
	 * @param int $lifetime Die maximale Lebensdauer der Cache Datei
	 *
	 * @return bool|string Gibt den Link zur gecachten Datei zurück, false wenn Lebensdauer abgelaufen oder gecachte Datei nicht existiert
	 */
	public static function getTemplateElement($cache_name, $lifetime = 500)
	{
		$cacheFile	=	self::$cacheDir.$cache_name.'.html';

		if(file_exists($cacheFile) === false)
		{
			return false;
		}

		$filemtime	=	filemtime($cacheFile);

		if($filemtime === false)
		{
			return false;
		}

		if(($filemtime + $lifetime) >= time() || $lifetime == 0)
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
	 * @param int $lifetime maximale Lebensdauer der Cache Datei
	 *
	 * @throws \Exception
	 * @return bool Wenn Cache erfolgreich erstellt true, wenn es Probleme gab false
	 */
	public static function setElement($cache_name, $content, $lifetime = 500)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'cache', 'setElement', array($cache_name, $content, $lifetime));
			$plugin	=	plugins::hookCall('before', 'cache', 'setElement', array($cache_name, $content, $lifetime));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		if(empty($cache_name))
		{
			throw new \Exception('cache name is empty');
		}

		$cache_name	=	md5($cache_name);

		if(empty($cache_name) || self::$cacheActiv === false)
		{
			return false;
		}

		$serialize	=	serialize(array('lifetime' => (time() + $lifetime), 'content' => $content));
		$cachePath	=	self::$cacheDir.$cache_name.self::$cacheExtension;
		$saveFile	=	file_put_contents($cachePath, $serialize);

		if(class_exists('\package\plugins') === true)
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
	 * @param $cache_name Der eindeutige Name der gecachten Datei
	 *
	 * @throws
	 * @return mixed Gibt den Inhalt des Caches zurück, false wenn Datei nicht existiert oder Lebensdauer abgelaufen
	 */
	public static function getElement($cache_name)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'cache', 'getElement', array($cache_name));
			$plugin	=	plugins::hookCall('before', 'cache', 'getElement', array($cache_name));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(empty($cache_name))
		{
			throw new \Exception('cache name is empty');
		}

		$cache_name	=	md5($cache_name);
		$filename	=	self::$cacheDir.$cache_name.self::$cacheExtension;

		if(is_file($filename) === true)
		{
			$getContent		=	file_get_contents($filename);
			$unserialize	=	unserialize($getContent);

			if(isset($unserialize['lifetime']) === true && $unserialize['lifetime'] >= time())
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
			return false;
		}
	}


	/**
	 * Entfernt eine Cache Datei
	 *
	 * @param $cache_name Eindeutiger Name der Cache Datei
	 *
	 * @throws \Exception
	 * @return bool Nach erfolgreichem löschen des Caches wird true zurück gegeben, bei einem Fehler false.
	 */
	public static function deleteElement($cache_name)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'cache', 'deleteElement', array($cache_name));
			$plugins	=	plugins::hookCall('before', 'cache', 'deleteElement', array($cache_name));

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		if(empty($cache_name))
		{
			throw new \Exception('cache name is empty');
		}

		$filename	=	self::$cacheDir.md5($cache_name).self::$cacheExtension;
		$remove		=	true;

		if(is_file($filename) === true)
		{
			$remove	=	unlink($filename);
		}

		if(class_exists('\package\plugins') === true)
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