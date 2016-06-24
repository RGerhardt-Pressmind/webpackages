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

use package\exceptions\cacheException;
use package\implement\IStatic;
use package\system\core\initiator;

/**
 * Cachen von Dateien oder Daten
 *
 * Wenn man Daten / Dateien für eine längere Zeit Cachen möchte, ist die Cache Klasse die richtige Anlaufstelle.
 * Sie speichert Daten aber auch z.b. Kompilierte PHP Dateien ab.
 * Diese Cache Dateien sind komprimiert und können so schneller vom Server an den Browser des Benutzers zurück
 * gesendet werden.
 *
 * @method static void set_cache_extension($extension = 'cache')
 * @method static bool set_template_element(string $cache_name, string $content)
 * @method static bool|string get_template_element(string $cache_name, $lifetime = 500)
 * @method static bool set_element(string $cache_name, string $content, $lifetime = 500)
 * @method static mixed get_element(string $cache_name)
 * @method static bool delete_element(string $cache_name)
 * @method static void set_cache_active(bool $active)
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       Cache
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class cache extends initiator implements IStatic
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
		if(CACHE_PATH != '')
		{
			self::_set_cache_dir(CACHE_PATH);
		}

		if(CACHE_EXTENSION != '')
		{
			self::_set_cache_extension(CACHE_EXTENSION);
		}
	}

	/**
	 * Setzt den Cache Pfad
	 *
	 * @param string $cachePath Der Ordner wo alle Gecachten Dateien abgespeichert werden sollen
	 *
	 * @return boolean Gibt im Fehlerfall ein false zurück
	 */
	protected static function _set_cache_dir($cachePath)
	{
		if(empty($cachePath) || !is_dir($cachePath))
		{
			return false;
		}

		if(!is_file($cachePath))
		{
			self::$cacheDir = $cachePath;

			return mkdir(self::$cacheDir, 0777, true);
		}
		else
		{
			self::$cacheDir = $cachePath;

			return true;
		}
	}

	/**
	 * Aktiviert / Deaktiviert den Cache
	 *
	 * @param bool $active Aktiviert bzw Deaktiviert den Cache
	 *
	 * @return void
	 */
	protected static function _set_cache_active($active = false)
	{
		self::$cacheActiv = $active;
	}

	/**
	 * Setzt die Cache Extension
	 *
	 * @param string $extension Die Cache Extension ist die Dateiendung jeder Cache Datei
	 *
	 * @throws cacheException Wenn der Parameter leer ist.
	 * @return void
	 */
	protected static function _set_cache_extension($extension = 'cache')
	{
		if(empty($extension))
		{
			throw new cacheException('extension is empty');
		}

		$extension = trim($extension);
		$extension = trim($extension, '.');
		$extension = trim($extension);

		self::$cacheExtension = '.'.$extension;
	}

	/**
	 * Cache eine HTML Template Datei
	 *
	 * @param string $cache_name Der eindeutige Cache Name
	 * @param string $content    Der Inhalt der Cache Datei
	 *
	 * @return bool
	 */
	protected static function _set_template_element($cache_name, $content)
	{
		$cachePath	=	self::$cacheDir.$cache_name.'.html';

		$isSave = @file_put_contents($cachePath, $content);

		if($isSave == false || filesize($cachePath) == 0)
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
	 * @param int    $lifetime   Die maximale Lebensdauer der Cache Datei
	 *
	 * @return bool|string Gibt den Link zur gecachten Datei zurück, false wenn Lebensdauer abgelaufen oder gecachte
	 *                     Datei nicht existiert
	 */
	protected static function _get_template_element($cache_name, $lifetime = 500)
	{
		$cacheFile = self::$cacheDir.$cache_name.'.html';

		if(!is_file($cacheFile))
		{
			return false;
		}

		$filemtime = @filemtime($cacheFile);

		if($filemtime == false)
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
	 * @param mixed  $content    Inhalt der zu cachenden Datei
	 * @param int    $lifetime   Die maximale Lebensdauer der Cache Datei
	 *
	 * @return bool Wenn Cache erfolgreich erstellt true, wenn es Probleme gab false (z.b. Cache nicht aktiv oder wenn
	 *              die Cache Datei nicht abgespeichert werden konnte)
	 * @throws cacheException Wenn der $cache_name leer ist
	 */
	protected static function _set_element($cache_name, $content, $lifetime = 500)
	{
		if(empty($cache_name))
		{
			throw new cacheException('Error: cache name is empty');
		}

		$cache_name = md5($cache_name);

		if(empty($cache_name) || !self::$cacheActiv)
		{
			return false;
		}

		$serialize = @serialize(array(
			'lifetime' => (time() + $lifetime),
			'content' => $content
		));

		$cachePath = self::$cacheDir.$cache_name.self::$cacheExtension;
		$saveFile  = @file_put_contents($cachePath, $serialize);

		if($saveFile == false || filesize($cachePath) == 0)
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
	 *
	 * @return mixed Gibt den Inhalt des Caches zurück, false wenn Datei nicht existiert oder Lebensdauer abgelaufen
	 * @throws cacheException Wenn $cache_name leer ist.
	 */
	protected static function _get_element($cache_name)
	{
		if(empty($cache_name))
		{
			throw new cacheException('Error: cache name is empty');
		}

		$cache_name = md5($cache_name);
		$filename   = self::$cacheDir.$cache_name.self::$cacheExtension;

		if(is_file($filename) && filesize($filename) > 0)
		{
			$getContent = @file_get_contents($filename);

			if(!empty($getContent))
			{
				$unserialize = @unserialize($getContent);

				if($unserialize != false && isset($unserialize['lifetime']) && $unserialize['lifetime'] >= time())
				{
					return $unserialize['content'];
				}
				else
				{
					@unlink($filename);

					return false;
				}
			}
			else
			{
				@unlink($filename);

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
	 *
	 * @return bool Nach erfolgreichem löschen des Caches wird true zurück gegeben, bei einem Fehler false.
	 * @throws cacheException Wenn $cache_name leer ist.
	 */
	protected static function _delete_element($cache_name)
	{
		if(empty($cache_name))
		{
			throw new cacheException('Error: cache name is empty');
		}

		$filename = self::$cacheDir.md5($cache_name).self::$cacheExtension;
		$remove   = true;

		if(is_file($filename))
		{
			$remove = @unlink($filename);
		}

		return $remove;
	}
} 