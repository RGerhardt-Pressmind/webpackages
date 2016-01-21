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

    @category   GeneralFunctions.abstract.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;

abstract class GeneralFunctions
{
	protected $loadedDatabases	=	array();

	/**
	 * Lädt die mod_rewrite Klasse
	 *
	 * @return mod_rewrite
	 * @throws \Exception
	 */
	public function loadModRewrite()
	{
		return autoload::get('mod_rewrite', '\package\\');
	}


	/**
	 * Lädt die XML Klasse
	 *
	 * @return XML
	 * @throws \Exception
	 */
	public function loadXML()
	{
		return autoload::get('XML', '\package\\');
	}


	/**
	 * Lädt die Date Klasse
	 *
	 * @throws \Exception
	 */
	public function loadDate()
	{
		autoload::get('date', '\package\\', true);
	}


	/**
	 * Lädt die FileSystem Klasse
	 *
	 * @throws \Exception
	 */
	public function loadFileSystem()
	{
		autoload::get('FileSystem', '\package\\', true);
	}


	/**
	 * Lädt die Browser Klasse
	 *
	 * @return browser
	 * @throws \Exception
	 */
	public function loadBrowser()
	{
		return autoload::get('browser', '\package\\');
	}


	/**
	 * Lädt die Versions Klasse
	 *
	 * @throws \Exception
	 */
	public function loadVersion()
	{
		autoload::get('version', '\package\\', true);
	}


	/**
	 * Lädt die ZIP Klasse
	 *
	 * @return zip
	 * @throws \Exception
	 */
	public function loadZIP()
	{
		return autoload::get('zip', '\package\\');
	}

	/**
	 * Lädt die FTP Klasse
	 *
	 * @return ftp
	 * @throws \Exception
	 */
	public function loadFTP()
	{
		return autoload::get('ftp', '\package\\');
	}

	/**
	 * Lädt die Benchmark Klasse
	 *
	 * @return void
	 */
	public function loadBenchmark()
	{
		autoload::get('benchmark', '\package\\', true);
	}


	/**
	 * Lädt die Cache Klasse
	 *
	 * @return void
	 */
	public function loadCache()
	{
		autoload::get('cache', '\package\\', true);
	}


	/**
	 * Lädt die Captcha Klasse
	 *
	 * @return void
	 */
	public function loadCaptcha()
	{
		autoload::get('captcha', '\package\\', true);
	}


	/**
	 * Lädt die Logger Klasse
	 *
	 * @return logger
	 */
	public function loadLogger()
	{
		return autoload::get('logger', '\package\\');
	}


	/**
	 * Lädt die cURL Klasse
	 *
	 * @return void
	 */
	public function loadCurl()
	{
		autoload::get('curl', '\package\\', true);
	}


	/**
	 * Lädt die Fehlerklasse
	 *
	 * @return errors
	 */
	public function loadError()
	{
		return autoload::get('errors', '\package\\');
	}


	/**
	 * Stellt eine Datenbankverbindung her
	 *
	 * @param bool $useDefault Ob die Verbindungsdaten aus den Konstanten genommen werden sollen oder die Parameter berücksichtig werden sollen.
	 * @param string $type Der PDO Treiber
	 * @param string $server Der Datenbank Host
	 * @param string $username Der Benutzername der Datenbank um eine Verbindung aufzubauen
	 * @param string $password Das Passwort der Datenbank um eine Verbindung aufzubauen
	 * @param string $database Die Datenbank mit der Verbunden werden soll
	 * @param int $port Der Port mit dem die Verbindung aufgebaut werden soll.
	 * @param string $charset Die Zeichenkordierung mit der die Verbindung arbeiten soll.
	 *
	 * @return database Gibt eine Datenbank Instanz zurück
	 * @throws \Exception
	 */
	public function loadDatabase($useDefault = true, $type = 'mysql', $server = '', $username = '', $password = '', $database = '', $port = 3306, $charset = '')
	{
		$hash	=	md5((int)$useDefault.'_'.$type.'_'.$server.'_'.$username.'_'.$password.'_'.$database.'_'.$port);

		if(isset($this->loadedDatabases[$hash]) === true)
		{
			return $this->loadedDatabases[$hash];
		}

		if(!class_exists('database') && !USE_SESSION_SAVE_HANDLER)
		{
			require_once 'database.class.php';
		}

		if($useDefault === true)
		{
			$type		=	PDO_TYPE;
			$server		=	PDO_HOST;
			$database	=	PDO_DATABASE;
			$port		=	PDO_PORT;
			$charset	=	PDO_CHARSET;
			$username	=	PDO_USERNAME;
			$password	=	PDO_PASSWORD;
		}

		if(in_array($type, database::$allowedDrivers) === false)
		{
			throw new \Exception('Database driver not allowed');
		}

		if((int)$port == 0)
		{
			$port	=	3306;
		}

		$addIn		=	false;
		$dsn		=	$type.':';

		if($type == 'sqlite' || $type == 'sqlite2')
		{
			$dsn	.=	$database;
		}
		else
		{
			if($server != '')
			{
				if($type == 'sqlsrv')
				{
					$dsn	.=	'Server='.$server;
				}
				else
				{
					$dsn	.=	'host='.$server;
				}

				$addIn	=	true;
			}

			if($port != '')
			{
				if($addIn === true && $type != 'sqlsrv')
				{
					$dsn	.=	';';
				}

				if($type == 'informix')
				{
					$dsn	.=	'service='.$port;
				}
				else if($type == 'sqlsrv')
				{
					$dsn	.=	','.$port;
				}
				else
				{
					$dsn	.=	'port='.$port;
				}

				$addIn	=	true;
			}

			if($database != '')
			{
				if($addIn === true)
				{
					$dsn	.=	';';
				}

				$dsn	.=	'dbname='.$database;
				$addIn	=	true;
			}

			if($charset != '')
			{
				if($addIn === true)
				{
					$dsn	.=	';';
				}

				$dsn	.=	'charset='.$charset;
			}
		}

		$databaseClasses	=	new database($dsn, $username, $password, null, $type);

		$this->loadedDatabases[$hash]	=	$databaseClasses;

		return $databaseClasses;
	}


	/**
	 * Lädt die Download Klasse
	 *
	 * @return void
	 */
	public function loadDownload()
	{
		autoload::get('download', '\package\\', true);
	}


	/**
	 * Lädt die Language Klasse
	 *
	 * @return void
	 */
	public function loadLanguage()
	{
		autoload::get('language', '\package\\', true);
	}


	/**
	 * Lädt die Number Klasse
	 *
	 * @return void
	 */
	public function loadNumber()
	{
		autoload::get('number', '\package\\', true);
	}


	/**
	 * Lädt die Template Klasse
	 *
	 * @return template
	 * @throws \Exception
	 */
	public function loadTemplate()
	{
		return autoload::get('template', '\package\\');
	}


	/**
	 * Lädt die Text Klasse
	 *
	 * @return void
	 */
	public function loadText()
	{
		autoload::get('text', '\package\\', true);
	}


	/**
	 * Lädt die Plugin Klasse
	 *
	 * @throws \Exception
	 * @return void
	 */
	public function loadPlugins()
	{
		autoload::get('plugins', '\package\\', true);
	}


	/**
	 * Lädt die PHPMailer Klasse
	 *
	 * @return \PHPMailer
	 */
	public function loadPHPMailer()
	{
		if(class_exists('PHPMailer') === false)
		{
			require 'PHPMailerAutoload.php';
		}

		return new \PHPMailer();
	}
}