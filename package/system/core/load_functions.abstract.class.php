<?php
/**
 *  Copyright (C) 2010 - 2017  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2017.0
 * @filesource
 */

namespace package\core;

use package\implement\IModel;
use package\implement\IPlugin;
use package\system\core\phpMailer;
use package\system\core\restClient;
use package\system\valueObjects\phpMailer\VOPHPMailer;

/**
 * Kernklasse die alle anderen Klassen lädt
 *
 * Die load_functions ist die Klasse die alle anderen Klassen / Plugins lädt.
 *
 * @property template   $template
 * @property zip        $zip
 * @property ftp        $ftp
 * @property XML        $xml
 * @property logger     $logger
 * @property errors     $error
 * @property database   $db
 * @property paypal     $paypal
 * @property restClient $rest
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       Initial
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
abstract class load_functions
{
	public static $LOAD_DATE           = array('isStatic' => true, 'class' => 'Date', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_FILE_SYSTEM    = array('isStatic' => true, 'class' => 'FileSystem', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_URL            = array('isStatic' => true, 'class' => 'url', 'writeInAttribute' => 'url', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_VERSION        = array('isStatic' => true, 'class' => 'version', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_ZIP            = array('isStatic' => false, 'class' => 'zip', 'writeInAttribute' => 'zip', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_FTP            = array('isStatic' => false, 'class' => 'ftp', 'writeInAttribute' => 'ftp', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_BENCHMARK      = array('isStatic' => true, 'class' => 'benchmark', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_TEMPLATE       = array('isStatic' => false, 'class' => 'template', 'writeInAttribute' => 'template', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_XML            = array('isStatic' => false, 'class' => 'XML', 'writeInAttribute' => 'xml', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_LOGGER         = array('isStatic' => false, 'class' => 'logger', 'writeInAttribute' => 'logger', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_ERROR          = array('isStatic' => false, 'class' => 'errors', 'writeInAttribute' => 'error', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_DATABASE       = array('isStatic' => false, 'class' => 'database', 'writeInAttribute' => 'db');

	public static $LOAD_PLUGINS        = array('isStatic' => true, 'class' => 'plugins', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_CACHE          = array('isStatic' => true, 'class' => 'cache', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_CURL           = array('isStatic' => true, 'class' => 'curl', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_TEXT           = array('isStatic' => true, 'class' => 'text', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_NUMBER         = array('isStatic' => true, 'class' => 'number', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_LANGUAGE       = array('isStatic' => true, 'class' => 'language', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_PAYPAL         = array('isStatic' => false, 'class' => 'paypal', 'writeInAttribute' => 'paypal', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_IMAGES         = array('isStatic' => true, 'class' => 'images', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_MAILER         = array('isStatic' => true, 'class' => 'phpmailer', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\\', 'inCache' => true);

	public static $LOAD_CAPTCHA        = array('isStatic' => true, 'class' => 'captcha', 'writeInAttribute' => null, 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	public static $LOAD_REST_CLIENT    = array('isStatic' => false, 'class' => 'restClient', 'writeInAttribute' => 'rest', 'parameter' => array(), 'namespace' => '\package\core\\', 'inCache' => true);

	private       $allLoadClasses      = array(), $defineDynamicClasses = array();

	private       $notAllowedClassName = array('autoload', 'cache', 'captcha', 'curl', 'database', 'pdo', 'error', 'errors', 'GeneralFunctions', 'load_functions', 'logger', 'number', 'security', 'template', 'text', 'phpmailer', 'db', 'database', 'session', 'ftp', 'zip', 'xml', 'Validater', 'url', 'date', 'Date', 'fileSystem', 'paypal', 'restClient');

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
		if(isset($this->defineDynamicClasses[$varName]))
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
	public function __construct($loadClasses = array())
	{
		if(empty($loadClasses))
		{
			$loadClasses = self::getAllDefaultClasses();
		}

		$this->allLoadClasses = $loadClasses;

		foreach($loadClasses as $classes)
		{
			if($classes['class'] == 'phpmailer')
			{
				//PHPMailer seperat laden
				$this->loadPHPMailer();
				continue;
			}
			else if($classes['class'] == 'database')
			{
				$this->defineDynamicClasses[$classes['writeInAttribute']] = new database(PDO_TYPE, PDO_HOST, PDO_USERNAME, PDO_PASSWORD, PDO_DATABASE, PDO_PORT, PDO_CHARSET, PDO_OPTIONS);

				continue;
			}

			if($classes['isStatic'])
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
					$inCache = false;

					if(!empty($classes['inCache']))
					{
						$inCache = true;
					}

					$this->defineDynamicClasses[$classes['writeInAttribute']] = autoload::get($classes['class'], $classes['namespace'], false, $classes['parameter'], $inCache);
				}
				else
				{
					throw new \Exception('Error: class '.$classes['class'].' has not attribute');
				}
			}
		}

		$this->load_dynamic_classes();
		$this->load_install_plugins();

		unset($loadClasses);
	}


	/**
	 * Lädt die PHPMailer Klasse
	 *
	 * @return void
	 */
	private function loadPHPMailer()
	{
		require_once 'phpMailer.class.php';
		require_once 'VOMailAddress.php';
		require_once 'VOMailAttachment.php';
		require_once 'VOPHPMailer.php';

		$this->phpmailer	=	null;

		if(MAIL_HOST != '' && MAIL_USERNAME != '' && MAIL_PASSWORD != '')
		{
			$voPHPMailer				=	new VOPHPMailer();
			$voPHPMailer->host			=	MAIL_HOST;
			$voPHPMailer->username		=	MAIL_USERNAME;
			$voPHPMailer->password		=	MAIL_PASSWORD;
			$voPHPMailer->port			=	MAIL_PORT;
			$voPHPMailer->is_smtp		=	MAIL_IS_SMTP;
			$voPHPMailer->is_smtp_auth	=	MAIL_SMTP_AUTH;
			$voPHPMailer->smtp_secure	=	MAIL_SMTP_SECURE;

			$this->phpmailer			=	new phpMailer($voPHPMailer);
		}
	}


	/**
	 * Gibt alle Standard Klassen der Initialisierung zurück
	 *
	 * @return array
	 */
	public static function getAllDefaultClasses()
	{
		return array(self::$LOAD_URL, self::$LOAD_TEMPLATE, self::$LOAD_DATABASE, self::$LOAD_PLUGINS, self::$LOAD_CACHE, self::$LOAD_CURL, self::$LOAD_LANGUAGE);
	}

	/**
	 * Gibt alle Klassen in einem Array zurück
	 * die an dritte weitergegeben werden können
	 *
	 * @return array
	 */
	private function get_all_init_classes()
	{
		$back = array();

		foreach($this->allLoadClasses as $cl)
		{
			if(!$cl['isStatic'])
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
		if(PLUGIN_DIR == '' || !class_exists('\package\core\plugins'))
		{
			return;
		}

		if(!empty(plugins::$definedPluginsClasses))
		{
			$allInitClasses = $this->get_all_init_classes();

			foreach(plugins::$definedPluginsClasses as $v)
			{
				if($v instanceof IPlugin)
				{
					$v->setAllClasses($allInitClasses);
				}
			}

			$this->defineDynamicClasses	=	array_merge($this->defineDynamicClasses, plugins::$definedPluginsClasses);
		}
	}

	/**
	 * Sucht im angegebenen Ordner nach der master Plugin Datei
	 *
	 * @param string $dir
	 *
	 * @return array
	 */
	public static function get_plugins($dir)
	{
		$directory = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);
		$back      = array();

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $item)
			{
				if(stripos($item->getFilename(), '.master.class.php') != false && !$item->isDir())
				{
					$className = str_replace(array('.php', '.php4', '.php5', '.master.class'), array('', '', '', ''), $item->getFilename());

					$classNameNamespace = '';

					if(file_exists($item->getPath().SEP.'config.ini'))
					{
						$config = parse_ini_file($item->getPath().SEP.'config.ini');

						//Namespace definition
						if(!empty($config['namespace']))
						{
							$classNameNamespace = trim($config['namespace'], '\\').'\\';
						}

						//Plugin aktiv oder nicht
						if(isset($config['active']) && (!$config['active'] || $config['active'] == 0))
						{
							continue;
						}
					}

					$classNameNamespace .= $className;

					if(!class_exists($classNameNamespace))
					{
						require_once $item->__toString();

						if(!class_exists($classNameNamespace))
						{
							continue;
						}
					}

					$class = new $classNameNamespace();

					$back[] = array('class_name' => $classNameNamespace, 'class' => $class);
				}
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
	protected function load_dynamic_classes($loadFiles = array())
	{
		if(DYNAMIC_DIR == '')
		{
			return array();
		}

		$allInitClasses = $this->get_all_init_classes();

		$directory = new \RecursiveDirectoryIterator(DYNAMIC_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);

		$back = array();

		if(iterator_count($iterator) > 0)
		{
			foreach($iterator as $item)
			{
				if($item->isDir())
				{
					continue;
				}

				if(is_array($loadFiles) && !empty($loadFiles))
				{
					if(!in_array($item->getFilename(), $loadFiles))
					{
						continue;
					}
				}

				$className = str_replace(array('.php', '.php4', '.php5', '.class'), array('', '', '', ''), $item->getFilename());

				if(!class_exists($className))
				{
					require_once $item->__toString();

					if(!class_exists($className))
					{
						continue;
					}
				}

				$class = new $className();

				$back[] = array('class_name' => $className, 'class' => $class);
			}
		}

		if(!empty($back))
		{
			foreach($back as $t)
			{
				$class = $t['class'];

				if($class instanceof IModel)
				{
					$className = $class->getClassName();

					if(!empty($className) && !array_key_exists($className, $this->notAllowedClassName))
					{
						$class->setAllClasses($allInitClasses);
					}
				}
			}

			foreach($back as $t)
			{
				$class = $t['class'];

				if($class instanceof IModel)
				{
					$className = $class->getClassName();

					if(!empty($className) && !array_key_exists($className, $this->notAllowedClassName))
					{
						$class->loadData();

						$this->defineDynamicClasses[$className] = $class;
					}
				}
			}
		}

		return $back;
	}
}