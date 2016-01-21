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

    @category   load_functions.abstract.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;

use package\implement\iDynamic;
use package\implement\IPlugin;

abstract class load_functions extends GeneralFunctions
{
	protected $db, $template, $phpmailer, $logger, $error, $ftp, $zip, $browser, $xml, $mod_rewrite;

	private $defineDynamicClasses	=	array();
	private $notAllowedClassName	=	array(
		'autoload',
		'cache',
		'captcha',
		'curl',
		'database',
		'pdo',
		'download',
		'error',
		'GeneralFunctions',
		'load_functions',
		'logger',
		'number',
		'security',
		'template',
		'text',
		'phpmailer',
		'db',
		'session',
		'ftp',
		'zip',
		'browser',
		'xml',
		'Validater',
		'mod_rewrite'
	);


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
		//Change from array_key_exists to isset - performance
		if(empty($this->defineDynamicClasses[$varName]) === false)
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
	 */
	public function __construct()
	{
		//Class load with $this->load...(template,security,captcha)

		//mod_rewrite class load
		$this->mod_rewrite	=	$this->loadModRewrite();

		$this->mod_rewrite->setUseModRewrite(USE_MOD_REWRITE);

		//browser class load
		$this->browser	=	$this->loadBrowser();

		//versions class load
		$this->loadVersion();

		//download class load
		$this->loadDownload();

		//zip class load
		$this->zip	=	$this->loadZIP();

		//ftp class load
		$this->ftp	=	$this->loadFTP();

		//benchmark class load
		$this->loadBenchmark();

		//Template class load and configuration
		$this->template	=	$this->loadTemplate();

		$this->template->setTemplateDir(TEMPLATE_DIR);
		$this->template->setHeaderFile(TEMPLATE_HEADER);
		$this->template->setFooterFile(TEMPLATE_FOOTER);

		//xml class load
		$this->xml	=	$this->loadXML();

		//PHPMailer class load
		$this->phpmailer	=	$this->loadPHPMailer();

		//logger class load
		$this->logger	=	$this->loadLogger();

		//error class load
		$this->error	=	$this->loadError();

		//Database class load and configuration
		if(!empty(PDO_HOST))
		{
			$this->db	=	$this->loadDatabase(true);
		}

		//plugins class load
		$this->loadPlugins();

		//cache class load
		$this->loadCache();

		//cURL class load
		$this->loadCurl();

		//text class load
		$this->loadText();

		//number class load
		$this->loadNumber();

		//Langauge class load and configuration
		$this->loadLanguage();

		if(empty(LANGUAGE_PATH) === false)
		{
			language::setLanguagePath(LANGUAGE_PATH);
		}

		if(empty(DEFAULT_LANGUAGE) === false)
		{
			language::setDefaultLanguage(DEFAULT_LANGUAGE);
		}

		if(empty(CACHE_PATH) === false)
		{
			cache::setCacheDir(CACHE_PATH);
		}

		if(empty(CACHE_EXTENSION) === false)
		{
			cache::setCacheExtension(CACHE_EXTENSION);
		}

		language::loadLang(true);

		$this->loadDynamicClasses();
		$this->loadInstallPlugins();
		$this->loadDefaultFunctions();
	}


	/**
	 * Gibt alle Klassen in einem Array zurück
	 * die an dritte weitergegeben werden können
	 *
	 * @return array
	 */
	private function getAllInitClasses()
	{
		return array(
			'database'		=>	$this->db,
			'template'		=>	$this->template,
			'phpmailer'		=>	$this->phpmailer,
			'logger'		=>	$this->logger,
			'error'			=>	$this->error,
			'ftp'			=>	$this->ftp,
			'zip'			=>	$this->zip,
			'browser'		=>	$this->browser,
			'xml'			=>	$this->xml,
			'mod_rewrite'	=>	$this->mod_rewrite
		);
	}


	/**
	 * Lädt alle, im Plugin Ordner, zur Verfügungn stehenden Plugins
	 *
	 * @return void
	 */
	protected function loadInstallPlugins()
	{
		if(!empty(PLUGIN_DIR))
		{
			return;
		}

		$allInitClasses	=	$this->getAllInitClasses();

		$back	=	$this->getPlugins(PLUGIN_DIR);

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
	protected function getPlugins($dir)
	{
		$directory 	= 	new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator 	= 	new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::LEAVES_ONLY);
		$back		=	array();

		foreach($iterator as $item)
		{
			$file	=	new \SplFileInfo($item);

			if(strpos($file->getFilename(), '.master.class.php') !== false && $file->isDir() === false)
			{
				require $file;

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
				$back	=	array_merge($back, $this->getPlugins($file));
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
	protected function loadDynamicClasses($loadFiles = array())
	{
		if(empty(DYNAMIC_DIR))
		{
			return array();
		}

		$allInitClasses	=	$this->getAllInitClasses();

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

			require $file;

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
	protected function loadDefaultFunctions()
	{
		//Load default functions
		if(class_exists('\package\plugins') === true)
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
	protected function isUserLoggedIn()
	{
		if(empty($_SESSION['id_users']))
		{
			$_SESSION['id_users']	=	0;
		}

		if(empty($_COOKIE['id_users']))
		{
			$_COOKIE['id_users']	=	0;
		}

		if(class_exists('\package\plugins') === true)
		{
			$back	=	plugins::hookCall('before', 'load_functions', 'isUserLoggedIn', array($_SESSION['id_users'], $_COOKIE['id_users']));

			if($back != null)
			{
				return $back;
			}
		}

		if(empty($_SESSION['id_users']) === false)
		{
			return true;
		}
		elseif(empty($_COOKIE['id_users']) === false)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}