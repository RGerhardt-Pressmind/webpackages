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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2018.0
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
 * @method void setSingleData(string $key, string $value)
 * @method string getTemplatePath()
 * @method static string getCssUrl(string $file)
 * @method static string getCssPath(string $file)
 * @method static string getJsUrl(string $file)
 * @method static string getJsPath(string $file)
 * @method static string getImageUrl(string $file)
 * @method static string getViewPath(string $file)
 * @method string getTemplateChildPath()
 * @method static string getPublicTemplatePath()
 * @method static string getPublicTemplateChildPath()
 * @method static void removeScript(string $nameRemove, string $positionRemove = null)
 * @method static void appendScript(string $name, string $path, string $version = '', int $priority = 10, string $position = 'header')
 * @method static string getScripts(string $position = 'header', bool $single = true)
 * @method static void removeStyle(string $nameRemove, string $positionRemove = null)
 * @method static void appendStyle(string $name, string $path, string $version = '', int $priority = 10, string $position = 'header')
 * @method static string getStyles(string $position, bool $singleFile = true)
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

	private static $appendScripts = array(), $appendStyles = array();

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
	 * Setzt nur einen bestimmten Schlüssel mit Wert
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return void
	 */
	protected function _setSingleData($key, $value)
	{
		$this->contentData	=	array_merge($this->contentData, array($key => $value));
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
	 * Gibt den Pfad zum Template Child zurück
	 *
	 * @return string
	 */
	protected function _getTemplateChildPath()
	{
		return self::_getPublicTemplateChildPath();
	}

	/**
	 * Gibt den Pfad zum Template Child zurück
	 *
	 * @return string
	 */
	protected static function _getPublicTemplateChildPath()
	{
		return self::$tempDir.self::$skin.'-child'.SEP;
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

		if(!empty($this->contentData))
		{
			foreach($this->contentData as $key => $value)
			{
				${$key} = $value;
			}
		}

		if(!file_exists($template))
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

				require $template;

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

		require $template;
	}


	/**
	 * Gibt den CSS Pfad zurück
	 *
	 * @param string $file
	 * @return string
	 */
	protected static function _getCssUrl($file)
	{
		if(file_exists(self::_getPublicTemplateChildPath().'css'.SEP.$file))
		{
			return trim(HTTP_SKIN, '/').'-child/css/'.$file;
		}

		return HTTP_SKIN.'css/'.$file;
	}

	/**
	 * Gibt den JavaScript Pfad zurück
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	protected static function _getJsUrl($file)
	{
		if(file_exists(self::_getPublicTemplateChildPath().'js'.SEP.$file))
		{
			return trim(HTTP_SKIN, '/').'-child/js/'.$file;
		}

		return HTTP_SKIN.'js/'.$file;
	}

	/**
	 * Get file path back
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	protected static function _getJsPath($file)
	{
		if(file_exists(self::_getPublicTemplateChildPath().'js'.SEP.$file))
		{
			return self::_getPublicTemplateChildPath().'js'.SEP.$file;
		}

		return self::_getPublicTemplatePath().'js'.SEP.$file;
	}

	/**
	 * Get css path back
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	protected static function _getCssPath($file)
	{
		if(file_exists(self::_getPublicTemplateChildPath().'css'.SEP.$file))
		{
			return self::_getPublicTemplateChildPath().'css'.SEP.$file;
		}

		return self::_getPublicTemplatePath().'css'.SEP.$file;
	}

	/**
	 * Get view path back
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	protected static function _getViewPath($file)
	{
		if(file_exists(self::_getPublicTemplateChildPath().$file))
		{
			return self::_getPublicTemplateChildPath().$file;
		}

		return self::_getPublicTemplatePath().$file;
	}

	/**
	 * Remove style from append list
	 *
	 * @param string $nameRemove
	 * @param string $positionRemove
	 *
	 * @return void
	 */
	protected static function _removeStyle($nameRemove, $positionRemove = null)
	{
		if(!empty(self::$appendStyles))
		{
			foreach(self::$appendStyles as $position => $prioritys)
			{
				if($positionRemove !== null)
				{
					if($position !== $positionRemove)
					{
						continue;
					}
				}

				foreach($prioritys as $priority => $names)
				{
					foreach($names as $name => $value)
					{
						if($name === $nameRemove)
						{
							unset(self::$appendStyles[$position][$priority][$name]);
						}
					}
				}
			}
		}
	}

	/**
	 * Collected styles
	 *
	 * @param string $name
	 * @param string $path
	 * @param string $version
	 * @param int    $priority
	 * @param string $position
	 *
	 * @return void
	 */
	protected static function _appendStyle($name, $path, $version = '', $priority = 10, $position = 'header')
	{
		self::$appendStyles[$position][$priority][$name]	=	array('name' => $name, 'path' => $path, 'version' => $version, 'position' => $position);
	}

	/**
	 * Get all styles back
	 *
	 * @param string $position
	 * @param bool   $singleFile
	 *
	 * @return string
	 */
	protected static function _getStyles($position, $singleFile = true)
	{
		$back	=	'';

		if(!file_exists(CACHE_PATH.'css'))
		{
			mkdir(CACHE_PATH.'css', 0755, true);
		}

		if($singleFile)
		{
			$singlFilename	=	md5($position.'styles').'.css';

			if(!empty(self::$appendStyles[$position]))
			{
				$content	=	'';

				ksort(self::$appendStyles[$position]);

				foreach(self::$appendStyles[$position] as $prioritys)
				{
					foreach($prioritys as $name => $value)
					{
						if(file_exists($value['path']))
						{
							$file	=	new \SplFileInfo($value['path']);

							if($file->getExtension() == 'less')
							{
								$url	=	str_replace(array(ROOT.SEP, SEP), array(HTTP, '/'), $value['path']);
								$back	.=	'<link rel="stylesheet/less" href="'.$url.'">';
							}
							else
							{
								$content	.=	"\n".file_get_contents($value['path']);
							}
						}
					}
				}

				$content	=	preg_replace('/(\.\.\/)+(welcome\/)?/', HTTP_SKIN, $content);

				file_put_contents(CACHE_PATH.'css'.SEP.$singlFilename, $content);
			}

			$back	.=	'
			<link rel="stylesheet" href="'.HTTP.'package/system/cache/css/'.$singlFilename.'">
			';
		}
		else
		{
			if(!empty(self::$appendStyles[$position]))
			{
				ksort(self::$appendStyles[$position]);

				foreach(self::$appendStyles[$position] as $prioritys)
				{
					foreach($prioritys as $name => $value)
					{
						if(file_exists($value['path']))
						{
							$url	=	str_replace(array(ROOT.SEP, SEP), array(HTTP, '/'), $value['path']);

							$back	.=	'
							<link rel="stylesheet" href="'.$url.'">
							';
						}
					}
				}
			}
		}

		return $back;
	}


	/**
	 * Remove script from append list
	 *
	 * @param string $nameRemove
	 * @param string $positionRemove
	 *
	 * @return void
	 */
	protected static function _removeScript($nameRemove, $positionRemove = null)
	{
		if(!empty(self::$appendScripts))
		{
			foreach(self::$appendScripts as $position => $prioritys)
			{
				if($positionRemove !== null)
				{
					if($position !== $positionRemove)
					{
						continue;
					}
				}

				foreach($prioritys as $priority => $names)
				{
					foreach($names as $name => $value)
					{
						if($name === $nameRemove)
						{
							unset(self::$appendScripts[$position][$priority][$name]);
						}
					}
				}
			}
		}
	}


	/**
	 * Collected scripts
	 *
	 * @param string $name
	 * @param string $path
	 * @param string $version
	 * @param int    $priority
	 * @param string $position -> header => <header> or footer => </body> end
	 *
	 * @return void
	 */
	protected static function _appendScript($name, $path, $version = '', $priority = 10, $position = 'header')
	{
		self::$appendScripts[$position][$priority][$name]	=	array('name' => $name, 'path' => $path, 'version' => $version, 'position' => $position);
	}

	/**
	 * Get out all appendScripts in one script
	 *
	 * @param string $position
	 * @param bool $singleFile
	 *
	 * @return string
	 */
	protected static function _getScripts($position = 'header', $singleFile = true)
	{
		$back	=	'';

		if(!file_exists(CACHE_PATH.'js'))
		{
			mkdir(CACHE_PATH.'js', 0755, true);
		}

		if($singleFile)
		{
			$singlFilename	=	md5($position.'scripts').'.js';

			if(!empty(self::$appendScripts[$position]))
			{
				$content	=	'';

				ksort(self::$appendScripts[$position]);

				foreach(self::$appendScripts[$position] as $prioritys)
				{
					foreach($prioritys as $name => $value)
					{
						if(file_exists($value['path']))
						{
							$content	.=	"\n".file_get_contents($value['path']);
						}
					}
				}

				file_put_contents(CACHE_PATH.'js'.SEP.$singlFilename, $content);
			}

			$back	=	'
			<script type="text/javascript" src="'.HTTP.'package/system/cache/js/'.$singlFilename.'"></script>
			';
		}
		else
		{
			if(!empty(self::$appendScripts[$position]))
			{
				ksort(self::$appendScripts[$position]);

				foreach(self::$appendScripts[$position] as $prioritys)
				{
					foreach($prioritys as $name => $value)
					{
						if(file_exists($value['path']))
						{
							$url	=	str_replace(array(ROOT.SEP, SEP), array(HTTP, '/'), $value['path']);

							$back	.=	'
							<script type="text/javascript" src="'.$url.'"></script>
							';
						}
					}
				}
			}
		}

		return $back;
	}

	/**
	 * Get image url back
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	protected static function _getImageUrl($file)
	{
		if(file_exists(self::_getPublicTemplateChildPath().'img'.SEP.$file))
		{
			return trim(HTTP_SKIN, '/').'-child/img/'.$file;
		}
		else if(file_exists(self::_getPublicTemplateChildPath().'images'.SEP.$file))
		{
			return trim(HTTP_SKIN, '/').'-child/images/'.$file;
		}
		else if(file_exists(self::_getPublicTemplatePath().'img'.SEP.$file))
		{
			return HTTP_SKIN.'img/'.$file;
		}
		else
		{
			return HTTP_SKIN.'images/'.$file;
		}
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
	 * @return mixed
	 * @throws templateException
	 */
	protected function _display($template, $header = null, $footer = null, $cacheActive = false, $cacheExpiresTime = 0)
	{
		ob_start();

		if(!empty($this->contentData))
		{
			foreach($this->contentData as $key => $value)
			{
				${$key} = $value;
			}
		}

		if(file_exists($this->getTemplateChildPath().$template))
		{
			$templatePath = new \SplFileInfo($this->getTemplateChildPath().$template);
		}
		else
		{
			$templatePath = new \SplFileInfo($this->getTemplatePath().$template);
		}

		if(!file_exists($templatePath))
		{
			throw new templateException('Error: template '.$templatePath.' not exist');
		}

		if($header != null)
		{
			if(file_exists($this->getTemplateChildPath().$header))
			{
				$headerPath	=	$this->getTemplateChildPath().$header;
			}
			else
			{
				$headerPath	=	$this->getTemplatePath().$header;
			}
		}
		else
		{
			$headerPath = $templatePath->getPath().SEP.$this->header;
		}

		if($footer != null)
		{
			if(file_exists($this->getTemplateChildPath().$this->footer))
			{
				$footerPath	=	$this->getTemplateChildPath().$this->footer;
			}
			else
			{
				$footerPath	=	$this->getTemplatePath().$this->footer;
			}
		}
		else
		{
			$footerPath = $templatePath->getPath().SEP.$this->footer;
		}

		if(!file_exists($headerPath) || !file_exists($footerPath))
		{
			throw new templateException('Error: header or footer template not exist ('.$headerPath.' - '.$footerPath.')');
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
				return $getTemplate;
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

				return $output;
			}
		}

		ob_start();
		require $headerPath;
		require $templatePath->__toString();
		require $footerPath;
		$output	=	ob_get_contents();
		ob_end_clean();

		echo $output;

		return $output;
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