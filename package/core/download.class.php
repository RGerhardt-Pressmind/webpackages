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

    @category   download.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package\core;


use package\implement\IStatic;

class download implements IStatic
{
	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	/**
	 * Bietet eine Datei zum Download an
	 *
	 * @param string $filename Der Name der Datei
	 * @param string $data Der Inhalt der Datei der zum Download angeboten wird
	 *
	 * @return mixed Bietet die Daten zum Download an
	 */
	public static function force_download($filename = '', $data = '')
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'download', 'forceDownload', array($filename, $data));
			plugins::hookCall('before', 'download', 'forceDownload', array($filename, $data));
		}

		if(empty($filename) || empty($data))
		{
			return false;
		}

		// Try to determine if the filename includes a file extension.
		// We need it in order to set the MIME type
		if(strpos($filename, '.') === false)
		{
			return false;
		}

		// Grab the file extension
		$x 			= 	explode('.', $filename);
		$extension 	=	end($x);

		// Load the mime types
		if(class_exists('security') === false)
		{
			require 'security.class.php';
		}

		//Mimes in Variable
		$mimes	=	security::$mimes;

		// Set a default mime if we can't find it
		if(empty($mimes[$extension]))
		{
			$mime	=	'application/octet-stream';
		}
		else
		{
			if(is_array($mimes[$extension]) === true)
			{
				$mime	=	$mimes[$extension][0];
			}
			else
			{
				$mime	=	$mimes[$extension];
			}
		}

		// Generate the server headers
		if(empty($_SERVER['HTTP_USER_AGENT']) === false && strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== false)
		{
			header('Content-Type: '.$mime);
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: ".strlen($data));
		}
		else
		{
			header('Content-Type: '.$mime);
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: ".strlen($data));
		}

		exit($data);
	}
} 