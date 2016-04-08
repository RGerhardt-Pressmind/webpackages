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

use package\exceptions\templateException;
use package\system\core\initiator;

/**
 * Template Klasse
 *
 * Wenn man sogenannte Templates (Vorlagen) für sein Framework nutzen möchte, kommt die template Klasse zur Hilfe. Sie
 * bindet verschiedene HTML / PHP Dateie zu einer Ausgabe zusammen. Gleichzeitig können Ihr auch Variablen übermittelt
 * werden, die zu PHP-Variablen umgewandelt werden. Dabei kann man auch das intelligente Cache System nutzen um PHP
 * Dateien nur einmal durch den PHP-Parser durchlaufen zu lassen. Hiermit kann man PHP und HTML / CSS Dateien
 * voneinander trennen um einen saubereren und übersichtlichereren Code zu bekommen.
 *
 * @method void setSkin(string $skin)
 * @method void setHeaderFile(string $header)
 * @method void setFooterFile(string $footer)
 * @method void setTemplateDir(string $dir)
 * @method void setData(array $datas)
 * @method string getTemplatePath()
 * @method static string getPublicTemplatePath()
 * @method void displayPlugin(string $template, $cacheActive = false, $cacheExpiresTime = 0)
 * @method void display(string $template, string $header = null, string $footer = null, $cacheActive = false, $cacheExpiresTime = 0)
 * @method mixed load_template_file(string $file, string $type, string $dir = '', $minify = true)
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       template
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class template extends initiator
{
	protected $contentData = array(), $caching = false, $gzip = true, $header, $footer;
	protected static $tempDir, $skin;

	/**
	 * Setzt die Standard Werte
	 *
	 * template constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->setTemplateDir(TEMPLATE_DIR);
		$this->setHeaderFile(TEMPLATE_HEADER);
		$this->setFooterFile(TEMPLATE_FOOTER);
		$this->setSkin(TEMPLATE_DEFAULT_SKIN);

		if(USE_TEMPLATE_LANGUAGE_PATH && class_exists('\package\core\language'))
		{
			$templatePath	=	self::getPublicTemplatePath().'languages'.SEP;

			if(file_exists($templatePath))
			{
				language::set_language_path($templatePath);
			}
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		self::$tempDir = null;
		$this->header  = null;
		$this->footer  = null;
		self::$skin    = null;

		foreach($this->contentData as $key => $value)
		{
			${$key} = null;

			unset(${$key});
		}

		$this->contentData = null;

		unset($this->contentData);
		unset($this->header);
		unset($this->footer);
	}

	/**
	 * Setzt den Skin (Ordner) im Template Verzeichnis
	 *
	 * @param string $skin
	 *
	 * @return void
	 */
	protected function _setSkin($skin)
	{
		self::$skin = $skin;
	}

	/**
	 * Setzt die Header Datei die Standartmäßig in alles eingebunden wird
	 *
	 * @param string $header Der relative Pfad zum Standart HTML Header.
	 *
	 * @return void
	 */
	protected function _setHeaderFile($header)
	{
		$this->header = $header;
	}

	/**
	 * Setzt die Footer Datei die Standartmäßig in alles eingebunden wird
	 *
	 * @param string $footer Der relative Pfad zum Standart HTML Footer
	 *
	 * @return void
	 */
	protected function _setFooterFile($footer)
	{
		$this->footer = $footer;
	}

	/**
	 * Setzt den Template Ordner
	 *
	 * @param string $dir Das Verzeichnis an dem die Templates liegen.
	 *
	 * @return void
	 */
	protected function _setTemplateDir($dir)
	{
		self::$tempDir = $dir;
	}

	/**
	 * Setzt die Daten für das Template
	 *
	 * @param array $datas Alle Variablen die dem Template übermittelt werden sollen.
	 *
	 * @return void
	 */
	protected function _setData(array $datas)
	{
		$this->contentData = array_merge($this->contentData, $datas);
	}

	/**
	 * Gibt den Pfad zum Template Verzeichnis zurück. Bestehend aus Template Pfad und Skin
	 *
	 * @return string
	 */
	protected function _getTemplatePath()
	{
		return self::_getPublicTemplatePath();
	}

	/**
	 * Gibt den Pfad zum Template Verzeichnis zurück. Bestehend aus Template Pfad und Skin. Dieser kann auch ausserhalb geholt werden.
	 *
	 * @return string
	 */
	protected static function _getPublicTemplatePath()
	{
		return self::$tempDir.self::$skin.SEP;
	}

	/**
	 * Lädt den absolute angegebenen Template Pfad
	 *
	 * @param string	$template
	 * @param bool 		$cacheActive
	 * @param int  		$cacheExpiresTime
	 *
	 * @return void
	 * @throws templateException
	 */
	protected function _displayPlugin($template, $cacheActive = false, $cacheExpiresTime = 0)
	{
		ob_start();

		if(!class_exists('\SplFileInfo'))
		{
			throw new templateException('Error: class SplFileInfo not exists');
		}

		if(!empty($this->contentData))
		{
			foreach($this->contentData as $key => $value)
			{
				${$key} = $value;
			}
		}

		$templatePath = new \SplFileInfo($template);

		if(!file_exists($templatePath->__toString()))
		{
			throw new templateException('Error: template '.$template.' not exist');
		}

		if($cacheActive)
		{
			if(!class_exists('\package\cache'))
			{
				throw new templateException('class cache not found');
			}

			$cacheName   = md5(url::getCurrentUrl().'_'.$template.'_'.md5(serialize($this->contentData)));
			$getTemplate = cache::get_template_element($cacheName, $cacheExpiresTime);

			if($getTemplate != false)
			{
				echo $getTemplate;
				return;
			}
			else
			{
				ob_start();

				require $templatePath->__toString();

				$output = ob_get_contents();

				$setTemplateElement = cache::set_template_element($cacheName, $output);

				ob_end_clean();

				if(!$setTemplateElement)
				{
					throw new templateException('setTemplateElement not write');
				}

				echo $output;

				return;
			}
		}

		require $templatePath->__toString();
	}

	/**
	 * Lädt das angegebene Template mit standart Header und Footer Template
	 *
	 * @param string $template         Das aufzurufende Template. Relativ zum angegebenen Template Verzeichnis.
	 * @param string $header			Dynamischer Header
	 * @param string $footer			Dynamischer Footer
	 * @param bool   $cacheActive      Aktiviert bzw. Deaktiviert den Template Cache.
	 * @param int    $cacheExpiresTime Die Cache Dauer. Unendlich = 0. Standartmäßig 0.
	 *
	 * @return void
	 * @throws templateException
	 */
	protected function _display($template, $header = null, $footer = null, $cacheActive = false, $cacheExpiresTime = 0)
	{
		ob_start();

		if(!class_exists('\SplFileInfo'))
		{
			throw new templateException('Error: class SplFileInfo not exists');
		}

		if(!empty($this->contentData))
		{
			foreach($this->contentData as $key => $value)
			{
				${$key} = $value;
			}
		}

		$templatePath = new \SplFileInfo($this->getTemplatePath().$template);

		if(!file_exists($templatePath->__toString()))
		{
			throw new templateException('Error: template '.$template.' not exist');
		}

		if($header != null)
		{
			$headerPath	=	$this->getTemplatePath().$header;
		}
		else
		{
			$headerPath = $templatePath->getPath().SEP.$this->header;
		}

		if($footer != null)
		{
			$footerPath	=	$this->getTemplatePath().$this->footer;
		}
		else
		{
			$footerPath = $templatePath->getPath().SEP.$this->footer;
		}

		if(!file_exists($headerPath) || !file_exists($footerPath))
		{
			throw new templateException('Error: header or footer template not exist');
		}

		if($cacheActive)
		{
			if(!class_exists('\package\cache'))
			{
				throw new templateException('class cache not found');
			}

			$cacheName   = md5(url::getCurrentUrl().'_'.$template.'_'.md5(serialize($this->contentData)));
			$getTemplate = cache::get_template_element($cacheName, $cacheExpiresTime);

			if($getTemplate != false)
			{
				echo $getTemplate;
				return;
			}
			else
			{
				ob_start();

				require $headerPath;
				require $templatePath->__toString();
				require $footerPath;

				$output = ob_get_contents();

				$setTemplateElement = cache::set_template_element($cacheName, $output);

				ob_end_clean();

				if(!$setTemplateElement)
				{
					throw new templateException('setTemplateElement not write');
				}

				echo $output;

				return;
			}
		}

		require $headerPath;
		require $templatePath->__toString();
		require $footerPath;
	}

	/**
	 * Lädt eine Datei aus dem Template Verzeichnis (js,css,images) und gibt den Inhalt zurück
	 *
	 * @param string $file   Dateiname der zu ladenen Datei
	 * @param string $type   css,js,javascript,img,image,images
	 * @param string $dir    Der Ordnernamen falls nicht mit $type übereinstimmend
	 * @param bool   $minify Definiert ob die Ausgabe von JavaScript oder CSS komprimiert werden soll
	 *
	 * @return mixed Gibt den Inhalt der Datei zurück
	 */
	protected function _load_template_file($file, $type, $dir = '', $minify = true)
	{
		return HTTP.'getTemplateFile.php?f='.$file.'&t='.$type.'&s='.self::$skin.'&d='.$dir.'&c='.((!$minify) ? 'false' : 'true');
	}
} 