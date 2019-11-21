<?php
/**
 *  Copyright (C) 2010 - 2020  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2020.0
 * @filesource
 */

namespace package\system\core;

use package\system\implement\IModel;
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
	public static $LOAD_CLASSES	=	array(
		'Date'	=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'FileSystem'	=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'url'			=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'version'		=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'zip'			=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'ftp'			=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'benchmark'		=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'template'		=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'XML'			=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'logger'		=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'error'			=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'cache'			=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'curl'			=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'text'			=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'number'		=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'language'		=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'paypal'		=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'images'		=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'captcha'		=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		),
		'restClient'	=>	array(
			'isStatic'	=>	false,
			'namespace'	=>	'\package\system\core\\'
		),
		'minify'		=>	array(
			'isStatic'	=>	true,
			'namespace'	=>	'\package\system\core\\'
		)
	);

	private $allLoadClasses	= array(), $defineDynamicClasses = array(), $reflectionClass = null;

	private $notAllowedClassName = array('autoload', 'cache', 'captcha', 'curl', 'database', 'pdo', 'error', 'errors', 'GeneralFunctions', 'load_functions', 'logger', 'number', 'security', 'template', 'text', 'phpmailer', 'db', 'database', 'session', 'ftp', 'zip', 'xml', 'Validater', 'url', 'date', 'Date', 'fileSystem', 'paypal', 'restClient', 'minify');

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
			if(isset(self::$LOAD_CLASSES[$varName]))
			{
				$classes	=	self::$LOAD_CLASSES[$varName];

				if(!$classes['isStatic'])
				{
					$classname	=	$classes['namespace'].$varName;

					$this->defineDynamicClasses[$varName]	=	new $classname();

					return $this->defineDynamicClasses[$varName];
				}
			}

			throw new \Exception('Error: variable '.$varName.' not exists');
		}
	}

	/**
	 * Hier können alle Notwendigen Klassen geladen werden
	 *
	 * @throws \Exception
	 * @return void
	 */
	public function __construct()
	{
		$this->reflectionClass	=	new \ReflectionClass(get_called_class());

		if(PDO_HOST != '' && PDO_USERNAME != '' && PDO_DATABASE != '')
		{
			$this->defineDynamicClasses['db'] = autoload::get('db');
		}

		$this->defineDynamicClasses['template']	=	new template();

		$this->loadPHPMailer();

		$this->load_dynamic_classes();
		$this->load_install_plugins();

		unset($loadClasses);

		plugins::callAction('wp_init_load_functions');
	}

	/**
	 * Lädt die PHPMailer Klasse
	 *
	 * @return void
	 */
	private function loadPHPMailer()
	{
		plugins::callAction('wp_load_functions_load_php_mailer');

		$this->phpmailer	=	null;

		if(MAIL_HOST != '' && MAIL_USERNAME != '' && MAIL_PASSWORD != '')
		{
			require_once 'phpMailer.class.php';
			require_once 'VOMailAddress.php';
			require_once 'VOMailAttachment.php';
			require_once 'VOPHPMailer.php';

			$voPHPMailer				=	new VOPHPMailer();
			$voPHPMailer->host			=	MAIL_HOST;
			$voPHPMailer->username		=	MAIL_USERNAME;
			$voPHPMailer->password		=	MAIL_PASSWORD;
			$voPHPMailer->port			=	MAIL_PORT;
			$voPHPMailer->is_smtp		=	MAIL_IS_SMTP;
			$voPHPMailer->is_smtp_auth	=	MAIL_SMTP_AUTH;
			$voPHPMailer->smtp_secure	=	MAIL_SMTP_SECURE;

			$this->phpmailer			=	new \phpMailer($voPHPMailer);
		}
	}


	/**
	 * Lädt alle, im Plugin Ordner, zur Verfügungn stehenden Plugins
	 *
	 * @return void
	 */
	protected function load_install_plugins()
	{
		if(!empty(plugins::$definedPluginsClasses))
		{
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
			/**
			 * @var \SplFileInfo $item
			 */
			foreach($iterator as $item)
			{
				if(stripos($item->getFilename(), '.master.class.php') != false && !$item->isDir())
				{
					$className = str_replace(array('.php', '.php4', '.php5', '.master.class'), array('', '', '', ''), $item->getFilename());

					$classNameNamespace = str_replace([ROOT.SEP, SEP], ['', '\\'], $item->getPath()).'\\';
					$classNameClean		= $className;

					if(file_exists($item->getPath().SEP.'config.ini'))
					{
						$config = parse_ini_file($item->getPath().SEP.'config.ini');

						//Plugin aktiv oder nicht
						if(isset($config['active']) && (!$config['active'] || $config['active'] == 0))
						{
							continue;
						}
					}

					$classNameNamespace .= $className;

					$class = new $classNameNamespace();

					$back[] = array('class_name' => $classNameNamespace, 'class' => $class, 'class_name_other_namespace' => $classNameClean);
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

		$directory = new \RecursiveDirectoryIterator(DYNAMIC_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator  = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);

		$back = array();

		if(iterator_count($iterator) > 0)
		{
			/**
			 * @var \SplFileInfo $item
			 */
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

				$namespace	=	str_replace([ROOT.SEP, SEP], ['', '\\'], $item->getPath())."\\";

				$className = $namespace.str_replace(array('.php', '.php4', '.php5', '.class'), array('', '', '', ''), $item->getFilename());

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
						$class->loadData();

						$this->defineDynamicClasses[$className] = $class;
					}
				}
			}
		}


		return $back;
	}
}
