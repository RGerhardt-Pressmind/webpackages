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

use package\implement\iDynamic;
use package\implement\IPlugin;

/**
 * Kernklasse die alle anderen Klassen lädt
 *
 * Die load_functions ist die Klasse die alle anderen Klassen / Plugins lädt.
 *
 * @package		Webpackages
 * @subpackage	core
 * @category	Initial
 * @author		Robbyn Gerhardt <gerhardt@webpackages.de>
 */
abstract class load_functions
{
	public static $LOAD_DATE			=	array('isStatic' => true, 'class' => 'Date', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_FILE_SYSTEM		=	array('isStatic' => true, 'class' => 'FileSystem', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_URL				=	array('isStatic' => false, 'class' => 'url', 'writeInAttribute' => 'url', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_BROWSER			=	array('isStatic' => false, 'class' => 'browser', 'writeInAttribute' => 'browser', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_VERSION			=	array('isStatic' => true, 'class' => 'version', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_ZIP				=	array('isStatic' => false, 'class' => 'zip', 'writeInAttribute' => 'zip', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_FTP				=	array('isStatic' => false, 'class' => 'ftp', 'writeInAttribute' => 'ftp', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_BENCHMARK		=	array('isStatic' => true, 'class' => 'benchmark', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_TEMPLATE		=	array('isStatic' => false, 'class' => 'template', 'writeInAttribute' =>'template', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_XML				=	array('isStatic' => false, 'class' => 'XML', 'writeInAttribute' => 'xml', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_LOGGER			=	array('isStatic' => false, 'class' => 'logger', 'writeInAttribute' => 'logger', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_ERROR			=	array('isStatic' => false, 'class' => 'errors', 'writeInAttribute' => 'error', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_DATABASE		=	array('isStatic' => false, 'class' => 'database', 'writeInAttribute' => 'db', 'parameter' => array('dsn' => PDO_TYPE.':dbname='.PDO_DATABASE.';host='.PDO_HOST.';port='.PDO_PORT, 'username' => PDO_USERNAME, 'password' => PDO_PASSWORD, 'options' => null, 'driver' => PDO_TYPE), 'namespace' => '\package\core\\');
	public static $LOAD_PLUGINS			=	array('isStatic' => true, 'class' => 'plugins', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_CACHE			=	array('isStatic' => true, 'class' => 'cache', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_CURL			=	array('isStatic' => true, 'class' => 'curl', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_TEXT			=	array('isStatic' => true, 'class' => 'text', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_NUMBER			=	array('isStatic' => true, 'class' => 'number', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_LANGUAGE		=	array('isStatic' => true, 'class' => 'language', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_PAYPAL			=	array('isStatic' => false, 'class' => 'paypal', 'writeInAttribute' => 'paypal', 'parameter' => array(), 'namespace' => '\package\core\\');
	public static $LOAD_IMAGES			=	array('isStatic' => true, 'class' => 'images', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\');

	protected $phpmailer;

	private	$allLoadClasses			=	array(), $defineDynamicClasses	=	array();
	private $notAllowedClassName	=	array('autoload', 'cache', 'captcha', 'curl', 'database', 'pdo', 'error', 'errors', 'GeneralFunctions', 'load_functions', 'logger', 'number', 'security', 'template', 'text', 'phpmailer', 'db', 'database', 'session', 'ftp', 'zip', 'browser', 'xml', 'Validater', 'url', 'date', 'Date', 'fileSystem', 'paypal');


	/**
	 * Kontrolliert ob es sich vielleicht um
	 * eine Dynamische Klasse handelt
	 *
	 * @param string $varName
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get($varName)
	{
		if(isset($this->defineDynamicClasses[$varName]) === true)
		{
			return $this->defineDynamicClasses[$varName];
		}
		else
		{
			throw new \Exception('Variable '.$varName.' not exists');
		}
	}


	/**
	 * Hier können alle Notwendigen Klassen geladen werden
	 *
	 * @param array $loadClasses Lädt die angegebene Liste an Klassen
	 * @throws \Exception
	 */
	public function __construct($loadClasses = array())
	{
		if(empty($loadClasses))
		{
			$loadClasses	=	array(
				self::$LOAD_DATE,
				self::$LOAD_FILE_SYSTEM,
				self::$LOAD_URL,
				self::$LOAD_BROWSER,
				self::$LOAD_VERSION,
				self::$LOAD_ZIP,
				self::$LOAD_FTP,
				self::$LOAD_BENCHMARK,
				self::$LOAD_TEMPLATE,
				self::$LOAD_XML,
				self::$LOAD_LOGGER,
				self::$LOAD_ERROR,
				self::$LOAD_DATABASE,
				self::$LOAD_PLUGINS,
				self::$LOAD_CACHE,
				self::$LOAD_CURL,
				self::$LOAD_TEXT,
				self::$LOAD_NUMBER,
				self::$LOAD_LANGUAGE,
				self::$LOAD_PAYPAL,
				self::$LOAD_IMAGES
			);
		}

		$this->allLoadClasses	=	$loadClasses;

		foreach($loadClasses as $classes)
		{
			if($classes['isStatic'] === true)
			{
				autoload::get($classes['class'], $classes['namespace'], true);

				if(!empty($classes['namespace']))
				{
					call_user_func($classes['namespace'].$classes['class'].'::init');
				}
				else
				{
					call_user_func($classes['class'].'::init');
				}
			}
			else
			{
				if(!empty($classes['writeInAttribute']))
				{
					$this->defineDynamicClasses[$classes['writeInAttribute']]	=	autoload::get($classes['class'], $classes['namespace'], false, $classes['parameter']);
				}
				else
				{
					throw new \Exception('Error: class '.$classes['class'].' has not attribute');
				}
			}
		}


		//PHPMailer class load
		if(class_exists('PHPMailer') === false)
		{
			require 'PHPMailerAutoload.php';
		}

		$this->phpmailer	=	new \PHPMailer();

		$this->load_dynamic_classes();
		$this->load_install_plugins();
		$this->load_default_functions();
	}


	/**
	 * Gibt alle Klassen in einem Array zurück
	 * die an dritte weitergegeben werden können
	 *
	 * @return array
	 */
	private function get_all_init_classes()
	{
		$back				=	array();
		$back['phpmailer']	=	$this->phpmailer;

		foreach($this->allLoadClasses as $cl)
		{
			if($cl['isStatic'] === false)
			{
				$back[$cl['writeInAttribute']]	=	$this->defineDynamicClasses[$cl['writeInAttribute']];
			}
		}

		return $back;
	}


	/**
	 * Lädt alle, im Plugin Ordner, zur Verfügungn stehenden Plugins
	 *
	 * @return void
	 */
	protected function load_install_plugins()
	{
		if(empty(PLUGIN_DIR) || class_exists('\package\plugins') === false)
		{
			return;
		}

		$allInitClasses	=	$this->get_all_init_classes();

		$back	=	$this->get_plugins(PLUGIN_DIR);

		if(is_array($back) === true && empty($back) === false)
		{
			foreach($back as $t)
			{
				if($t['class'] instanceof IPlugin)
				{
					$class	=	$t['class'];

					$class->setAllClasses($allInitClasses);
					$class->construct();

					plugins::$definedPluginsClasses[]	=	$class;
				}
			}
		}
	}


	/**
	 * Sucht im angegebenen Ordner nach der master Plugin Datei
	 *
	 * @param string $dir
	 * @return array
	 */
	protected function get_plugins($dir)
	{
		$directory 	= 	new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator 	= 	new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::LEAVES_ONLY);
		$back		=	array();

		foreach($iterator as $item)
		{
			$file	=	new \SplFileInfo($item);

			if(strpos($file->getFilename(), '.master.class.php') !== false && $file->isDir() === false)
			{
				require_once $file;

				$className	=	str_replace(array('.php', '.php5', '.master.class'), array('', '', ''), $file->getFilename());
				$className	=	'package\plugins\\'.$className;

				if(class_exists($className) === false)
				{
					continue;
				}

				$class	=	new $className();

				$back[]	=	array('class_name' => $className, 'class' => $class);
			}
			elseif($file->isDir() === true)
			{
				$back	=	array_merge($back, $this->get_plugins($file));
			}
		}

		return $back;
	}


	/**
	 * Lädt benutzerdefinierte Klassen
	 *
	 * @param array $loadFiles
	 * @return array
	 */
	protected function load_dynamic_classes($loadFiles = array())
	{
		if(empty(DYNAMIC_DIR))
		{
			return array();
		}

		$allInitClasses	=	$this->get_all_init_classes();

		$directory 	= 	new \RecursiveDirectoryIterator(DYNAMIC_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator 	= 	new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::LEAVES_ONLY);

		$back	=	array();

		foreach($iterator as $item)
		{
			$file	=	new \SplFileInfo($item);

			if($file->isDir() === true)
			{
				continue;
			}

			if(is_array($loadFiles) === true && empty($loadFiles) === false)
			{
				if(in_array($file->getFilename(), $loadFiles) === false)
				{
					continue;
				}
			}

			require_once $file;

			$className	=	str_replace(array('.php', '.php5', '.class'), array('', '', ''), $file->getFilename());

			if(class_exists($className) === false)
			{
				continue;
			}

			$class	=	new $className();

			$back[]	=	array('class_name' => $className, 'class' => $class);
		}

		foreach($back as $t)
		{
			$class	=	$t['class'];

			if($class instanceof iDynamic)
			{
				$className	=	$class->getClassName();

				if(empty($className) === false && array_key_exists($className, $this->notAllowedClassName) === false)
				{
					$class->setAllClasses($allInitClasses);
					$class->loadData();

					$this->defineDynamicClasses[$className]	=	$class;
				}
			}
		}

		return $back;
	}


	/**
	 * Lädt standart Funktionen die immer kontrolliert
	 * bzw. ausgeführt werden sollen
	 *
	 * @return void
	 */
	protected function load_default_functions()
	{
		//Load default functions
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'load_functions', 'loadDefaultFunctions');
			plugins::hookShow('after', 'load_functions', 'loadDefaultFunctions');
		}
	}



	/**
	 * Kontrolliert ob ein Benutzer eingeloggt ist
	 *
	 * @return bool Gibt bei erfolg ein true zurück ansonsten ein false.
	 */
	protected function is_user_logged_in()
	{
		if(empty($_SESSION['id_users']))
		{
			$_SESSION['id_users']	=	0;
		}

		if(empty($_COOKIE['id_users']))
		{
			$_COOKIE['id_users']	=	0;
		}

		if(class_exists('\package\core\plugins') === true)
		{
			$back	=	plugins::hookCall('before', 'load_functions', 'isUserLoggedIn', array($_SESSION['id_users'], $_COOKIE['id_users']));

			if($back != null)
			{
				return $back;
			}
		}

		if(!empty($_SESSION['id_users']))
		{
			return true;
		}
		elseif(!empty($_COOKIE['id_users']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}