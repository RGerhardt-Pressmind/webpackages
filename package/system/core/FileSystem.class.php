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
 * @subpackage    controllers
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\implement\IStatic;

/**
 * Greift aus das Dateisystem zu
 *
 * Durch die FileSystem Klasse kann man Daten auf dem Webserver verschieben, kopieren oder ganze Strukturen einfach
 * löschen.
 *
 * @package        Webpackages
 * @subpackage     controllers
 * @category       Filesystem
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class FileSystem implements IStatic
{
	/**
	 * Zum initialisieren von Daten
	 */
	public static function init()
	{
	}

	/**
	 * Kontrolliert ob eine Datei beschreibbar ist oder nicht.
	 * Unter Unix System kein Problem, aber Windows Systeme
	 * können den falschen Wert zurück geben wenn safe_mode aktiv
	 * ist.
	 *
	 * @param string $file Der Datei-/Ordnername der kontrolliert werden soll.
	 *
	 * @return bool
	 */
	public static function is_really_writable($file)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'is_really_writable', array($file));
			$plugins = plugins::hookCall('before', 'FileSystem', 'is_really_writable', array($file));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(OS === 'UNIX' && ini_get('safe_mode') === false)
		{
			return is_writable($file);
		}

		if(is_dir($file) === true)
		{
			$file = rtrim($file, '/').'/'.md5(mt_rand());

			if(($fp = @fopen($file, 'ab')) === false)
			{
				return false;
			}

			fclose($fp);
			@chmod($file, 0777);
			@unlink($file);

			return true;
		}
		elseif(is_file($file) === false || ($fp = @fopen($file, 'ab')) === false)
		{
			return false;
		}

		fclose($fp);

		return true;
	}

	/**
	 * Gibt den Inhalt eines Ordners zurück
	 *
	 * @param string $path      Der Pfad zum Ordner von dem der Inhalt zurück gegeben werden soll
	 * @param int    $orderBack Die Sortierung des Rückgabewertes aus der Klasse RecursiveIteratorIterator
	 * @param bool   $withData  Ob der Rückgabewert auch detailierte Informationen der einzelnen Dateien / Ordner
	 *                          enthalten soll
	 *
	 * @return array|bool
	 */
	public static function get_all_files($path, $orderBack = \RecursiveIteratorIterator::SELF_FIRST, $withData = false)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'get_all_files', array($path, $orderBack, $withData));
			$plugins = plugins::hookCall('before', 'FileSystem', 'get_all_files', array($path, $orderBack, $withData));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(is_dir($path) === false || file_exists($path) === false)
		{
			return false;
		}

		$directory = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator  = new \RecursiveIteratorIterator($directory, (int)$orderBack);

		$back = array();

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $file)
			{
				if($file instanceof \SplFileInfo)
				{
					$toString = $file->__toString();
					$getPath  = $file->getPath();

					if($withData === false)
					{
						if($file->isDir() === true)
						{
							$back[$toString]['filepath'] = $toString;
						}
						else
						{
							$back[$getPath]['childs'][]['filepath'] = $toString;
						}
					}
					else
					{
						if($file->isDir() === true)
						{
							$back[$toString]['filepath'] = $toString;
							$back[$toString]['path']     = $getPath;
							$back[$toString]['basename'] = $file->getBasename();
							$back[$toString]['filename'] = $file->getFilename();
							$back[$toString]['realPath'] = $file->getRealPath();
						}
						else
						{
							$add                  = array();
							$add['filepath']      = $toString;
							$add['path']          = $getPath;
							$add['filename']      = $file->getFilename();
							$add['extension']     = $file->getExtension();
							$add['basename']      = $file->getBasename();
							$add['realPath']      = $file->getRealPath();
							$add['size']          = $file->getSize();
							$add['modified_time'] = $file->getMTime();

							$back[$toString]['childs'][] = $add;
						}
					}
				}
			}

			$newSort = array();

			foreach($back as $file)
			{
				$newSort[] = $file;
			}

			$back = $newSort;
		}

		if(class_exists('\package\core\plugins') === true)
		{
			$plugins = plugins::hookCall('after', 'FileSystem', 'get_all_files', array($back));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $back;
	}

	/**
	 * Löcht den Inhalt eines Ordners und gegebenfalls den Ordner selber
	 *
	 * @param string $path       Der Pfad zum Verzeichnis dessen Inhalt gelöscht werden soll
	 * @param bool   $delete_dir Ob das Verzeichnis anschließend auch gelöscht werden soll. Standartmäßig false
	 *
	 * @return bool
	 */
	public static function delete_files($path, $delete_dir = false)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'delete_files', array($path, $delete_dir));
			$plugins = plugins::hookCall('before', 'FileSystem', 'delete_files', array($path, $delete_dir));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(is_dir($path) === false || file_exists($path) === false)
		{
			return false;
		}

		$directory = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $file)
			{
				if($file instanceof \SplFileInfo)
				{
					if($file->isFile() === true)
					{
						if(unlink($file->__toString()) === false)
						{
							return false;
						}
					}
					elseif($file->isDir() === true)
					{
						if(rmdir($file->__toString()) === false)
						{
							return false;
						}
					}
				}
			}
		}

		if($delete_dir === true)
		{
			if(rmdir($path) === false)
			{
				return false;
			}
		}

		if(class_exists('\package\core\plugins') === true)
		{
			$plugins = plugins::hookCall('after', 'FileSystem', 'delete_files', array($path, $delete_dir));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return true;
	}

	/**
	 * Kopiert ein ganzes Verzeichnis
	 *
	 * @param string $source Relativer Pfad zum Verzeichnis das kopiert werden soll.
	 * @param string $dest   Relativer Pfad zum Zielverzeichnis wohin es kopiert werden soll.
	 * @param int    $chmod  Ändert anschließend die Zugriffsrechte im Zielverzeichnis, wenn erlaubt. Standartmäßig
	 *                       "0755"
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public static function copyDirectory($source, $dest, $chmod = 0755)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'copyDirectory', array($source, $dest, $chmod));
			$plugins = plugins::hookCall('before', 'FileSystem', 'copyDirectory', array($source, $dest, $chmod));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(file_exists($dest) === false)
		{
			mkdir($dest, $chmod);
		}

		if(is_dir($source) === true)
		{
			$directory = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
			$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);

			if(iterator_count($iterator) > 0)
			{
				foreach($iterator as $file)
				{
					if($file instanceof \SplFileInfo)
					{
						if($file->isDir() === true)
						{
							$newPath = str_replace($source, $dest, $file->__toString());

							if(file_exists($newPath) === false)
							{
								if(mkdir($newPath, $chmod, true) === false)
								{
									return false;
								}
							}
						}
						else
						{
							$newPath = str_replace($source, $dest, $file->__toString());
							$copy    = copy($file->__toString(), $newPath);

							if($copy === false)
							{
								return false;
							}

							$chmod = chmod($newPath, $chmod);

							if($chmod === false)
							{
								return false;
							}
						}
					}
				}
			}
		}
		else
		{
			$copy = copy($source, $dest);

			if($copy === false)
			{
				return false;
			}

			$chmod = chmod($dest, $chmod);

			if($chmod === false)
			{
				return false;
			}
		}

		if(class_exists('\package\core\plugins') === true)
		{
			$plugins = plugins::hookCall('after', 'FileSystem', 'copyDirectory', array($source, $dest, $chmod));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return true;
	}

	/**
	 * Verschiebt ein ganzes Verzeichnis
	 *
	 * @param string $source Relativer Pfad zum Ursprungsverzeichnis
	 * @param string $dest   Relativer Pfad zum Zielverzeichnis
	 * @param int    $chmod  Ändert Anschließend die Zugriffsrechte, wenn erlaubt. Standartmäßig "0755"
	 *
	 * @return bool Gibt true bei Erfolg und false bei einem Fehler zurück.
	 */
	public static function renameDirectory($source, $dest, $chmod = 0755)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'renameDirectory', array($source, $dest, $chmod));
			$plugins = plugins::hookCall('before', 'FileSystem', 'renameDirectory', array($source, $dest, $chmod));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(file_exists($dest) === false)
		{
			mkdir($dest, $chmod, true);
		}

		if(is_dir($source) === true)
		{
			$directory = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
			$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::SELF_FIRST);

			if(iterator_count($iterator) > 0)
			{
				foreach($iterator as $file)
				{
					if($file instanceof \SplFileInfo)
					{
						if($file->isFile() === true)
						{
							if(file_exists($file->getPath()) === false)
							{
								if(mkdir($file->getPath(), $chmod, true) === false)
								{
									return false;
								}
							}

							$newPath = str_replace($source, $dest, $file->__toString());
							$rename  = rename($file->__toString(), $newPath);

							if($rename === false)
							{
								return false;
							}

							$chmod = chmod($newPath, $chmod);

							if($chmod === false)
							{
								return false;
							}
						}
						elseif($file->isDir() === true)
						{
							$newPath = str_replace($source, $dest, $file->__toString());

							if(file_exists($newPath) === false)
							{
								if(mkdir($newPath, $chmod, true) === false)
								{
									return false;
								}
							}
						}
					}
				}
			}

			$rmdir = self::delete_files($source, true);

			if($rmdir === false)
			{
				return false;
			}
		}
		else
		{
			$rename = rename($source, $dest);

			if($rename === false)
			{
				return false;
			}

			$chmod = chmod($dest, $chmod);

			if($chmod === false)
			{
				return false;
			}
		}

		if(class_exists('\package\core\plugins') === true)
		{
			$plugins = plugins::hookCall('after', 'FileSystem', 'renameDirectory', array($source, $dest, $chmod));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return true;
	}
}