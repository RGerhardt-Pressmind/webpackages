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
    
    @category   FileSystem.class.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class FileSystem implements IStatic
{
	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	/**
	 * Kontrolliert ob eine Datei beschreibbar ist oder nicht.
	 * Unter Unix System kein Problem, aber Windows Systeme
	 * können den falschen Wert zurück geben wenn safe_mode aktiv
	 * ist.
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function is_really_writable($file)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'is_really_writable', array($file));
			$plugins	=	plugins::hookCall('before', 'FileSystem', 'is_really_writable', array($file));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(OS == 'UNIX' && !ini_get('safe_mode'))
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
		elseif(is_file($file) == false or ($fp = @fopen($file, 'ab')) === false)
		{
			return false;
		}

		fclose($fp);

		return true;
	}


	/**
	 * Gibt den Inhalt eines Ordners zurück
	 *
	 * @param string $path
	 * @param int $orderBack
	 * @param bool $withData
	 *
	 * @return array|bool
	 */
	public static function get_all_files($path, $orderBack = \RecursiveIteratorIterator::SELF_FIRST, $withData = false)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'get_all_files', array($path, $orderBack, $withData));
			$plugins	=	plugins::hookCall('before', 'FileSystem', 'get_all_files', array($path, $orderBack, $withData));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(is_dir($path) === false || file_exists($path) === false)
		{
			return false;
		}

		$directory	=	new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator	=	new \RecursiveIteratorIterator($directory, (int)$orderBack);

		$back		=	array();

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $file)
			{
				if($file instanceof \SplFileInfo)
				{
					if($withData === false)
					{
						if($file->isDir() === true)
						{
							$back[$file->__toString()]['filepath']	=	$file->__toString();
						}
						else
						{
							$back[$file->getPath()]['childs'][]['filepath']	=	$file->__toString();
						}
					}
					else
					{
						if($file->isDir() === true)
						{
							$back[$file->__toString()]['filepath']	=	$file->__toString();
							$back[$file->__toString()]['path']		=	$file->getPath();
							$back[$file->__toString()]['basename']	=	$file->getBasename();
							$back[$file->__toString()]['filename']	=	$file->getFilename();
							$back[$file->__toString()]['realPath']	=	$file->getRealPath();
						}
						else
						{
							$add					=	array();
							$add['filepath']		=	$file->__toString();
							$add['path']			=	$file->getPath();
							$add['filename']		=	$file->getFilename();
							$add['extension']		=	$file->getExtension();
							$add['basename']		=	$file->getBasename();
							$add['realPath']		=	$file->getRealPath();
							$add['size']			=	$file->getSize();
							$add['modified_time']	=	$file->getMTime();

							$back[$file->__toString()]['childs'][]	=	$add;
						}
					}
				}
			}

			$newSort	=	array();

			foreach($back as $k => $file)
			{
				$newSort[]	=	$file;
			}

			$back	=	$newSort;
		}

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'FileSystem', 'get_all_files', array($back));

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
	 * @param string $path
	 * @param bool $delete_dir
	 *
	 * @return bool
	 */
	public static function delete_files($path, $delete_dir = false)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'FileSystem', 'delete_files', array($path, $delete_dir));
			$plugins	=	plugins::hookCall('before', 'FileSystem', 'delete_files', array($path, $delete_dir));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(is_dir($path) === false || file_exists($path) === false)
		{
			return false;
		}

		$directory	=	new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator	=	new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $file)
			{
				if($file instanceof \SplFileInfo)
				{
					if($file->isFile())
					{
						if(@unlink($file->__toString()) === false)
						{
							return false;
						}
					}
					elseif($file->isDir())
					{
						if(@rmdir($file->__toString()) === false)
						{
							return false;
						}
					}
				}
			}
		}

		if($delete_dir === true)
		{
			if(@rmdir($path) === false)
			{
				return false;
			}
		}

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'FileSystem', 'delete_files', array($path, $delete_dir));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return true;
	}
}