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
    
    @category   zip.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package;

class zip
{
	/**
	 * Erstellt ein ZipArchive
	 *
	 * @param string $folder Ordner der verpackt werden soll
	 * @param string $destination Zielordner wo die Zip Datei abgespeichert werden soll (nicht der Name nur der Pfad)
	 * @param string $zipName Der Name der Zip Datei (nicht der Pfad nur der Name)
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function createZipArchive($folder, $destination, $zipName)
	{
		if(!file_exists($folder))
		{
			throw new \Exception($folder.' not exists');
		}

		$folder	=	new \SplFileInfo($folder);

		$zipName	=	rtrim($zipName, '.zip');
		$zipName	=	rtrim($zipName, '.');

		$destination	=	str_replace(array('/', '\\'), array(SEP, SEP), $destination);
		$destination	=	rtrim($destination, SEP).SEP;

		if(!file_exists($destination))
		{
			if(!mkdir($destination, 0777, true))
			{
				throw new \Exception($destination.' can not created');
			}
		}

		$zipFile	=	new \SplFileInfo($destination.$zipName.'.zip');

		if(file_exists($zipFile->__toString()))
		{
			if(!unlink($zipFile->__toString()))
			{
				throw new \Exception('Old zip file can not be removed');
			}
		}

		$zip		=	new \ZipArchive();

		if($zip->open($zipFile->__toString(), \ZipArchive::CREATE) !== true)
		{
			throw new \Exception('ZipArchive can not write in destination folder '.$destination);
		}

		$getAllFiles	=	new \RecursiveDirectoryIterator($folder->__toString(), \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator		=	new \RecursiveIteratorIterator($getAllFiles, \RecursiveIteratorIterator::SELF_FIRST);

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $file)
			{
				if($file instanceof \SplFileInfo && $file->getFilename() != '.DS_Store' && $file->isDir() === false)
				{
					$zip->addFile($file->__toString(), str_replace($folder->__toString(), '', $file->__toString()));
				}
			}
		}

		return $zip->close();
	}


	/**
	 * Fügt eine Datei in das ZipArchive hinzu
	 *
	 * @param string $rootFolder Das Root Verzeichnis des Webservers
	 * @param string $file Die Datei die dem Zip Archiv hinzugefügt werden soll
	 * @param string $zipArchive Der Pfad zum Zip Archiv
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function addFileToZipArchive($rootFolder, $file, $zipArchive)
	{
		$rootFolder	=	str_replace(array('/', '\\'), array(SEP, SEP), $rootFolder);
		$file		=	str_replace(array('/', '\\'), array(SEP, SEP), $file);
		$zipArchive	=	str_replace(array('/', '\\'), array(SEP, SEP), $zipArchive);

		$file		=	new \SplFileInfo($file);
		$rootFolder	=	new \SplFileInfo($rootFolder);
		$zipArchive	=	new \SplFileInfo($zipArchive);

		if(!file_exists($file))
		{
			throw new \Exception('file '.$file->__toString().' not exists');
		}
		else if(!file_exists($zipArchive))
		{
			throw new \Exception('zipArchive '.$zipArchive->__toString().' not exists');
		}

		$zip	=	new \ZipArchive();

		if($zip->open($zipArchive->__toString()))
		{
			if(!$zip->addFile($file->__toString(), str_replace($rootFolder->__toString(), '', $file->__toString())))
			{
				throw new \Exception('can not add file to zip archive');
			}
		}
		else
		{
			throw new \Exception('zipArchive can not be open');
		}

		return true;
	}


	/**
	 * Entpackt ein ZipArchive
	 *
	 * @param string $zipArchive Der Pfad zum Zip Archiv
	 * @param string $destinationFolder Der Zielordner wo das Zip Archiv entpackt werden soll
	 * @param bool $removeZipArchiveAfterExtract Löschen nach erfolgreichem entpacken des Zip Archivs (true => Ja Zip Archiv löschen, Nein => Zip Archiv bestehen lassen)
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function extractZipArchive($zipArchive, $destinationFolder, $removeZipArchiveAfterExtract = false)
	{
		$zipArchive			=	str_replace(array('/', '\\'), array(SEP, SEP), $zipArchive);
		$destinationFolder	=	str_replace(array('/', '\\'), array(SEP, SEP), $destinationFolder);

		$zipArchive			=	new \SplFileInfo($zipArchive);
		$destinationFolder	=	new \SplFileInfo($destinationFolder);

		if(!file_exists($zipArchive))
		{
			throw new \Exception('zip archive not exist '.$zipArchive->__toString());
		}

		if(!file_exists($destinationFolder->__toString()))
		{
			mkdir($destinationFolder->__toString(), 0777, true);
		}

		$zip	=	new \ZipArchive();

		if($zip->open($zipArchive->__toString()))
		{
			if(!$zip->extractTo($destinationFolder->__toString()))
			{
				throw new \Exception('zip archive can not be extract');
			}

			$zip->close();
		}
		else
		{
			throw new \Exception('zip archive can not be open');
		}

		if($removeZipArchiveAfterExtract)
		{
			if(!unlink($zipArchive->__toString()))
			{
				throw new \Exception('zip archive can not be remove after extract');
			}
		}

		return true;
	}
}