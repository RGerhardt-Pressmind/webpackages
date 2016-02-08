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

use package\implement\iDynamic;
use package\implement\IPlugin;

/**
 * Kernklasse die alle anderen Klassen lädt
 *
 * Die load_functions ist die Klasse die alle anderen Klassen / Plugins lädt.
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       Initial
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
abstract class load_functions
{
	public static $LOAD_DATE           = ['isStatic' => true, 'class' => 'Date', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_FILE_SYSTEM    = ['isStatic' => true, 'class' => 'FileSystem', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_URL            = ['isStatic' => true, 'class' => 'url', 'writeInAttribute' => 'url', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_VERSION        = ['isStatic' => true, 'class' => 'version', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_ZIP            = ['isStatic' => false, 'class' => 'zip', 'writeInAttribute' => 'zip', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_FTP            = ['isStatic' => false, 'class' => 'ftp', 'writeInAttribute' => 'ftp', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_BENCHMARK      = ['isStatic' => true, 'class' => 'benchmark', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_TEMPLATE       = ['isStatic' => false, 'class' => 'template', 'writeInAttribute' => 'template', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_XML            = ['isStatic' => false, 'class' => 'XML', 'writeInAttribute' => 'xml', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_LOGGER         = ['isStatic' => false, 'class' => 'logger', 'writeInAttribute' => 'logger', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_ERROR          = ['isStatic' => false, 'class' => 'errors', 'writeInAttribute' => 'error', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_DATABASE       = ['isStatic' => false, 'class' => 'database', 'writeInAttribute' => 'db', 'parameter' => ['driver' => PDO_TYPE, 'host' => PDO_HOST, 'username' => PDO_USERNAME, 'password' => PDO_PASSWORD, 'charset' => PDO_CHARSET, 'port' => PDO_PORT, 'database' => PDO_DATABASE], 'namespace' => '\package\core\\'];

	public static $LOAD_PLUGINS        = ['isStatic' => true, 'class' => 'plugins', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_CACHE          = ['isStatic' => true, 'class' => 'cache', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_CURL           = ['isStatic' => true, 'class' => 'curl', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_TEXT           = ['isStatic' => true, 'class' => 'text', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_NUMBER         = ['isStatic' => true, 'class' => 'number', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_LANGUAGE       = ['isStatic' => true, 'class' => 'language', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_PAYPAL         = ['isStatic' => false, 'class' => 'paypal', 'writeInAttribute' => 'paypal', 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_IMAGES         = ['isStatic' => true, 'class' => 'images', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\package\core\\'];

	public static $LOAD_MAILER         = ['isStatic' => true, 'class' => 'phpmailer', 'writeInAttribute' => null, 'parameter' => [], 'namespace' => '\\'];

	private       $allLoadClasses      = [], $defineDynamicClasses = [];

	private       $notAllowedClassName = ['autoload', 'cache', 'captcha', 'curl', 'database', 'pdo', 'error', 'errors', 'GeneralFunctions', 'load_functions', 'logger', 'number', 'security', 'template', 'text', 'phpmailer', 'db', 'database', 'session', 'ftp', 'zip', 'xml', 'Validater', 'url', 'date', 'Date', 'fileSystem', 'paypal'];

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		if(!empty($this->defineDynamicClasses))
		{
			foreach($this->defineDynamicClasses as $k => $class)
			{
				$this->defineDynamicClasses[$k] = null;
				$class                          = null;

				unset($class);
				unset($this->defineDynamicClasses[$k]);
			}
		}

		if(!empty($this->allLoadClasses))
		{
			foreach($this->allLoadClasses as $k => $class)
			{
				$this->allLoadClasses[$k] = null;
				$class                    = null;

				unset($class);
				unset($this->allLoadClasses[$k]);
			}
		}
	}

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
			throw new \Exception('Error: variable '.$varName.' not exists');
		}
	}

	/**
	 * Hier können alle Notwendigen Klassen geladen werden
	 *
	 * @param array $loadClasses Lädt die angegebene Liste an Klassen
	 *
	 * @throws \Exception
	 */
	public function __construct($loadClasses = [])
	{
		if(empty($loadClasses))
		{
			$loadClasses = [self::$LOAD_URL, self::$LOAD_TEMPLATE, self::$LOAD_DATABASE, self::$LOAD_PLUGINS, self::$LOAD_CACHE, self::$LOAD_CURL, self::$LOAD_LANGUAGE];
		}

		$this->allLoadClasses = $loadClasses;

		foreach($loadClasses as $classes)
		{
			if($classes['class'] === 'phpmailer')
			{
				//PHPMailer class load
				if(class_exists('PHPMailer') === false)
				{
					require 'PHPMailerAutoload.php';
				}

				continue;
			}

			if($classes['isStatic'] === true)
			{
				autoload::get($classes['class'], $classes['namespace'], true);

				if(empty($classes['namespace']) === false)
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
				if(empty($classes['writeInAttribute']) === false)
				{
					$this->defineDynamicClasses[$classes['writeInAttribute']] = autoload::get($classes['class'], $classes['namespace'], false, $classes['parameter']);
				}
				else
				{
					throw new \Exception('Error: class '.$classes['class'].' has not attribute');
				}
			}
		}

		$this->load_dynamic_classes();
		$this->load_install_plugins();
		$this->load_default_functions();

		unset($loadClasses);
	}

	/**
	 * Gibt alle Klassen in einem Array zurück
	 * die an dritte weitergegeben werden können
	 *
	 * @return array
	 */
	private function get_all_init_classes()
	{
		$back = [];

		foreach($this->allLoadClasses as $cl)
		{
			if($cl['isStatic'] === false)
			{
				$back[$cl['writeInAttribute']] = $this->defineDynamicClasses[$cl['writeInAttribute']];
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
		if(empty(PLUGIN_DIR) === true || class_exists('\package\plugins') === false)
		{
			return;
		}

		$allInitClasses = $this->get_all_init_classes();

		$back = $this->get_plugins(PLUGIN_DIR);

		if(is_array($back) === true && empty($back) === false)
		{
			foreach($back as $t)
			{
				if($t['class'] instanceof IPlugin)
				{
					$class = $t['class'];

					$class->setAllClasses($allInitClasses);
					$class->construct();

					plugins::$definedPluginsClasses[] = $class;
				}
			}
		}
	}

	/**
	 * Sucht im angegebenen Ordner nach der master Plugin Datei
	 *
	 * @param string $dir
	 *
	 * @return array
	 */
	protected function get_plugins($dir)
	{
		$directory = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::LEAVES_ONLY);
		$back      = [];

		foreach($iterator as $item)
		{
			if($item instanceof \SplFileInfo && stripos($item->getFilename(), '.master.class.php') !== false && $item->isDir() === false)
			{
				require_once $item->__toString();

				$className = str_replace(['.php', '.php5', '.master.class'], ['', '', ''], $item->getFilename());
				$className = 'package\plugins\\'.$className;

				if(class_exists($className) === false)
				{
					continue;
				}

				$class = new $className();

				$back[] = ['class_name' => $className, 'class' => $class];
			}
			elseif($item->isDir() === true)
			{
				$back = array_merge($back, $this->get_plugins($item->__toString()));
			}
		}

		return $back;
	}

	/**
	 * Lädt benutzerdefinierte Klassen
	 *
	 * @param array $loadFiles
	 *
	 * @return array
	 */
	protected function load_dynamic_classes($loadFiles = [])
	{
		if(empty(DYNAMIC_DIR) === true)
		{
			return [];
		}

		$allInitClasses = $this->get_all_init_classes();

		$directory = new \RecursiveDirectoryIterator(DYNAMIC_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::LEAVES_ONLY);

		$back = [];

		foreach($iterator as $item)
		{
			if($item->isDir() === true)
			{
				continue;
			}

			if(is_array($loadFiles) === true && empty($loadFiles) === false)
			{
				if(in_array($item->getFilename(), $loadFiles) === false)
				{
					continue;
				}
			}

			require_once $item->__toString();

			$className = str_replace(['.php', '.php5', '.class'], ['', '', ''], $item->getFilename());

			if(class_exists($className) === false)
			{
				continue;
			}

			$class = new $className();

			$back[] = ['class_name' => $className, 'class' => $class];
		}

		foreach($back as $t)
		{
			$class = $t['class'];

			if($class instanceof iDynamic)
			{
				$className = $class->getClassName();

				if(empty($className) === false && array_key_exists($className, $this->notAllowedClassName) === false)
				{
					$class->setAllClasses($allInitClasses);
					$class->loadData();

					$this->defineDynamicClasses[$className] = $class;
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
}