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

use package\exceptions\zipException;
use package\system\core\initiator;

/**
 * Zip Archive
 *
 * Mit der zip Klasse kann man ein ZIP-Archiv erstellen oder auslesen.
 *
 * @method bool createZipArchive(string $folder, string $destination, string $zipName)
 * @method bool removeFileFromZipArchive(string $file, string $zipArchive, string $zipArchivePassword = null)
 * @method bool renameFileInZipArchive(string $oldName, string $newName, string $zipArchive, string $zipArchivePassword = null)
 * @method bool addFileToZipArchive(string $rootFolder, string $file, string $zipArchive, string $zipArchivePassword = null)
 * @method bool extractZipArchive(string $zipArchive, string $destinationFolder, $removeZipArchiveAfterExtract = false, string $zipArchivePassword = null)
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       zip
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class zip extends initiator
{
	/**
	 * Erstellt ein ZipArchive
	 *
	 * @param string $folder      Ordner der verpackt werden soll
	 * @param string $destination Zielordner wo die Zip Datei abgespeichert werden soll (nicht der Name nur der Pfad)
	 * @param string $zipName     Der Name der Zip Datei (nicht der Pfad nur der Name)
	 *
	 * @return bool
	 * @throws zipException
	 */
	protected function _createZipArchive($folder, $destination, $zipName)
	{
		if(!file_exists($folder))
		{
			throw new zipException('Error: '.$folder.' not exists');
		}
		elseif(!class_exists('\SplFileInfo'))
		{
			throw new zipException('Error: SplFileInfo class not exists');
		}

		$folder = new \SplFileInfo($folder);

		$zipName = rtrim($zipName, '.zip');
		$zipName = rtrim($zipName, '.');

		$destination = str_replace(array('/', '\\'), array(SEP, SEP), $destination);
		$destination = rtrim($destination, SEP).SEP;

		if(!file_exists($destination))
		{
			if(!mkdir($destination, 0777, true))
			{
				throw new zipException('Error: '.$destination.' can not created');
			}
		}

		$zipFile = new \SplFileInfo($destination.$zipName.'.zip');

		if(file_exists($zipFile->__toString()))
		{
			if(!unlink($zipFile->__toString()))
			{
				throw new zipException('Error: Old zip file can not be removed');
			}
		}

		$zip = new \ZipArchive();

		if($zip->open($zipFile->__toString(), \ZipArchive::CREATE) != true)
		{
			$zip->close();
			throw new zipException('Error: ZipArchive can not write in destination folder '.$destination);
		}

		$getAllFiles = new \RecursiveDirectoryIterator($folder->__toString(), \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator    = new \RecursiveIteratorIterator($getAllFiles, \RecursiveIteratorIterator::SELF_FIRST);

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $file)
			{
				if($file->getFilename() != '.DS_Store' && !$file->isDir())
				{
					$zip->addFile($file->__toString(), str_replace($folder->__toString(), '', $file->__toString()));
				}
			}
		}

		return $zip->close();
	}

	/**
	 * Entfernt eine Datei aus einem ZIP-Archiv
	 *
	 * @param string $file Der relative Pfad zur Datei / Ordner im Zip Archiv
	 * @param string $zipArchive Der Pfad zum Zip Archiv
	 * @param string $zipArchivePassword Das Paswwort für das Zip Archiv, wenn vorhanden
	 * @since v2.1.0
	 *
	 * @return bool
	 * @throws zipException
	 */
	protected function _removeFileFromZipArchive($file, $zipArchive, $zipArchivePassword = null)
	{
		if(!class_exists('\SplFileInfo'))
		{
			throw new zipException('Error: SplFileInfo class not exists');
		}

		$zipArchive	=	new \SplFileInfo(str_replace(array('/', '\\'), array(SEP, SEP), $zipArchive));

		if(!file_exists($zipArchive))
		{
			throw new zipException('Error: Zip archive not exists');
		}

		$zip		=	new \ZipArchive();
		$removeFile	=	false;

		if($zip->open($zipArchive->__toString()) == true)
		{
			if($zipArchivePassword != null)
			{
				if(!$zip->setPassword($zipArchivePassword))
				{
					$zip->close();
					throw new zipException('Error: zip archive password is wrong');
				}
			}

			$removeFile	=	$zip->deleteName($file);

			$zip->close();
		}

		return $removeFile;
	}

	/**
	 * Benennt eine Datei oder Ordner in einem Zip Archiv um
	 *
	 * @param string $oldName Der alte Name der Datei oder Ordner
	 * @param string $newName Der neue Name der Datei oder Ordner
	 * @param string $zipArchive Der absolute Pfad zum Zip Archiv
	 * @param string $zipArchivePassword Das Passwort für das Zip Archiv, wenn vorhanden
	 * @since v2.1.0
	 *
	 * @return bool
	 * @throws zipException
	 */
	protected function _renameFileInZipArchive($oldName, $newName, $zipArchive, $zipArchivePassword = null)
	{
		if(!class_exists('\SplFileInfo'))
		{
			throw new zipException('Error: SplFileInfo class not exists');
		}

		$zipArchive = new \SplFileInfo(str_replace(array('/', '\\'), array(SEP, SEP), $zipArchive));

		if(!file_exists($zipArchive))
		{
			throw new zipException('Error: Zip archive not exists');
		}

		$zip	=	new \ZipArchive();
		$rename	=	false;

		if($zip->open($zipArchive->__toString()) == true)
		{
			if($zipArchivePassword != null)
			{
				if(!$zip->setPassword($zipArchivePassword))
				{
					$zip->close();
					throw new zipException('Error: zip archive password is wrong');
				}
			}

			$rename	=	$zip->renameName($oldName, $newName);

			$zip->close();
		}

		return $rename;
	}


	/**
	 * Fügt eine Datei in das ZipArchive hinzu
	 *
	 * @param string $rootFolder Das Root Verzeichnis des Webservers
	 * @param string $file       Die Datei die dem Zip Archiv hinzugefügt werden soll
	 * @param string $zipArchive Der Pfad zum Zip Archiv
	 * @param string $zipArchivePassword Das Passwort für das Zip Archiv, wenn eins gesetzt
	 *
	 * @return bool
	 * @throws zipException
	 */
	protected function _addFileToZipArchive($rootFolder, $file, $zipArchive, $zipArchivePassword = null)
	{
		if(!class_exists('\SplFileInfo'))
		{
			throw new zipException('Error: SplFileInfo class not exists');
		}

		$rootFolder = str_replace(array('/', '\\'), array(SEP, SEP), $rootFolder);
		$file       = str_replace(array('/', '\\'), array(SEP, SEP), $file);
		$zipArchive = str_replace(array('/', '\\'), array(SEP, SEP), $zipArchive);

		$file       = new \SplFileInfo($file);
		$rootFolder = new \SplFileInfo($rootFolder);
		$zipArchive = new \SplFileInfo($zipArchive);

		if(!file_exists($file))
		{
			throw new zipException('Error: file '.$file->__toString().' not exists');
		}
		elseif(!file_exists($zipArchive))
		{
			throw new zipException('Error: zip archive '.$zipArchive->__toString().' not exists');
		}

		$zip = new \ZipArchive();

		if($zip->open($zipArchive->__toString()) == true)
		{
			if($zipArchivePassword != null)
			{
				if(!$zip->setPassword($zipArchivePassword))
				{
					$zip->close();
					throw new zipException('Error: zip archive password is wrong');
				}
			}

			if(!$zip->addFile($file->__toString(), str_replace($rootFolder->__toString(), '', $file->__toString())))
			{
				$zip->close();
				throw new zipException('Error: can not add file to zip archive');
			}
		}
		else
		{
			throw new zipException('Error: zip archive can not be open');
		}

		return true;
	}

	/**
	 * Entpackt ein ZipArchive
	 *
	 * @param string $zipArchive                   Der Pfad zum Zip Archiv
	 * @param string $destinationFolder            Der Zielordner wo das Zip Archiv entpackt werden soll
	 * @param bool   $removeZipArchiveAfterExtract Löschen nach erfolgreichem entpacken des Zip Archivs (true => Ja Zip
	 *                                             Archiv löschen, Nein => Zip Archiv bestehen lassen)
	 * @param string $zipArchivePassword		   Das Passwort für das Zip Archiv, wenn eins gesetzt (v2.1.0)
	 *
	 * @return bool
	 * @throws zipException
	 */
	protected function _extractZipArchive($zipArchive, $destinationFolder, $removeZipArchiveAfterExtract = false, $zipArchivePassword = null)
	{
		if(!class_exists('\SplFileInfo'))
		{
			throw new zipException('Error: SplFileInfo class not exists');
		}

		$zipArchive        = str_replace(array('/', '\\'), array(SEP, SEP), $zipArchive);
		$destinationFolder = str_replace(array('/', '\\'), array(SEP, SEP), $destinationFolder);

		$zipArchive        = new \SplFileInfo($zipArchive);
		$destinationFolder = new \SplFileInfo($destinationFolder);

		if(!file_exists($zipArchive))
		{
			throw new zipException('Error: zip archive not exist '.$zipArchive->__toString());
		}

		if(!file_exists($destinationFolder->__toString()))
		{
			mkdir($destinationFolder->__toString(), 0777, true);
		}

		$zip = new \ZipArchive();

		if($zip->open($zipArchive->__toString()) == true)
		{
			if($zipArchivePassword != null)
			{
				if(!$zip->setPassword($zipArchivePassword))
				{
					$zip->close();
					throw new zipException('Error: zip archive password is wrong');
				}
			}

			if(!$zip->extractTo($destinationFolder->__toString()))
			{
				$zip->close();
				throw new zipException('Error: zip archive can not be extract');
			}

			$zip->close();
		}
		else
		{
			$zip->close();
			throw new zipException('Error: zip archive can not be open');
		}

		if($removeZipArchiveAfterExtract)
		{
			if(!unlink($zipArchive->__toString()))
			{
				$zip->close();
				throw new zipException('Error: zip archive can not be remove after extract');
			}
		}

		return true;
	}
}