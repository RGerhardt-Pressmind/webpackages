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

    @category   template.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


class template
{
	protected $contentData = array(), $caching = false, $gzip = true, $tempDir, $header, $footer, $skin;


	/**
	 * Setzt die Standard Werte
	 *
	 * template constructor.
	 */
	public function __construct()
	{
		$this->setTemplateDir(TEMPLATE_DIR);
		$this->setHeaderFile(TEMPLATE_HEADER);
		$this->setFooterFile(TEMPLATE_FOOTER);
		$this->setSkin(TEMPLATE_DEFAULT_SKIN);
	}


	/**
	 * Setzt den Skin (Ordner) im Template Verzeichnis
	 *
	 * @param string $skin
	 * @return void
	 */
	public function setSkin($skin)
	{
		$this->skin	=	$skin;
	}

	/**
	 * Setzt die Header Datei die Standartmäßig in alles eingebunden wird
	 *
	 * @param string $header Der relative Pfad zum Standart HTML Header.
	 * @return void
	 */
	public function setHeaderFile($header)
	{
		$this->header	=	$header;
	}


	/**
	 * Setzt die Footer Datei die Standartmäßig in alles eingebunden wird
	 *
	 * @param string $footer Der relative Pfad zum Standart HTML Footer
	 * @return void
	 */
	public function setFooterFile($footer)
	{
		$this->footer	=	$footer;
	}

	/**
	 * Setzt den Template Ordner
	 *
	 * @param string $dir Das Verzeichnis an dem die Templates liegen.
	 * @return void
     */
	public function setTemplateDir($dir)
	{
		$this->tempDir	=	$dir;
	}


	/**
	 * Setzt die Daten für das Template
	 *
	 * @param array $datas Alle Variablen die dem Template übermittelt werden sollen.
	 * @return void
	 */
	public function setData(array $datas)
	{
		$this->contentData	=	array_merge($this->contentData, $datas);
	}


	/**
	 * Lädt ein Template mit dynamischen Header und Footer Template.
	 *
	 * @param string $template Der Pfad zum Template. Relativ zum angegebenen Template Verzeichnis.
	 * @param string $header Der Pfad zum Header Template. Relativ zum angegebenen Template Verzeichnis.
	 * @param string $footer Der Pfad zum Footer Template. Relativ zum angegebenen Template Verzeichnis.
	 * @param bool $cacheActive Aktiviert bzw. Deaktiviert den Cache dieses aufgerufenen Templates. Standartmäßig false.
	 * @param int $cacheExpiresTime Die Cache Dauer. Unendlich = 0. Standartmäßig 0.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function displayDH($template, $header, $footer, $cacheActive = false, $cacheExpiresTime = 0)
	{
		ob_start();

		foreach($this->contentData as $key => $value)
		{
			${$key}	=	$value;
		}

		$templateFile	=	$this->tempDir.$this->skin.SEP.$template;
		$splFileInfo	=	new \SplFileInfo($templateFile);

		$headerPath		=	$splFileInfo->getPath().SEP.$header;
		$footerPath		=	$splFileInfo->getPath().SEP.$footer;

		if($cacheActive === true)
		{
			if(class_exists('\package\cache') === false)
			{
				throw new \Exception('class cache not found');
			}

			$cacheName		=	md5($this->currentURL().'_'.$template.'_'.$header.'_'.$footer.'_'.md5(serialize($this->contentData))).'.cache';
			$getTemplate	=	cache::get_template_element($cacheName, $cacheExpiresTime);

			if($getTemplate !== false)
			{
				$loadHeader		=	false;
				$loadTemplate	=	$getTemplate;
				$loadFooter		=	false;
			}
			else
			{
				ob_start();

				require $headerPath;
				require $templateFile;
				require $footerPath;

				$setTemplateElement	=	cache::set_template_element($cacheName, ob_get_contents());

				ob_end_clean();

				if($setTemplateElement === false)
				{
					throw new \Exception('setTemplateElement not write');
				}

				$loadHeader		=	false;
				$loadTemplate	=	cache::get_template_element($cacheName, $cacheExpiresTime);
				$loadFooter		=	false;
			}
		}
		else
		{
			$loadHeader		=	$headerPath;
			$loadTemplate	=	$templateFile;
			$loadFooter		=	$footerPath;
		}

		if($loadHeader !== false)
		{
			require $loadHeader;
		}

		require $loadTemplate;

		if($loadFooter !== false)
		{
			require $loadFooter;
		}
	}


	/**
	 * Lädt das angegebene Template mit standart Header und Footer Template
	 *
	 * @param string $template Das aufzurufende Template. Relativ zum angegebenen Template Verzeichnis.
	 * @param bool $cacheActive Aktiviert bzw. Deaktiviert den Template Cache.
	 * @param int $cacheExpiresTime Die Cache Dauer. Unendlich = 0. Standartmäßig 0.
	 * @return void
	 * @throws \Exception
	 */
	public function display($template, $cacheActive = false, $cacheExpiresTime = 0)
	{
		ob_start();

		foreach($this->contentData as $key => $value)
		{
			${$key}	=	$value;
		}

		$templatePath	=	$this->tempDir.$this->skin.SEP.$template;
		$splFileInfo	=	new \SplFileInfo($templatePath);

		$headerPath		=	$splFileInfo->getPath().SEP.$this->header;
		$footerPath		=	$splFileInfo->getPath().SEP.$this->footer;

		if($cacheActive === true)
		{
			if(class_exists('\package\cache') === false)
			{
				throw new \Exception('class cache not found');
			}

			$cacheName		=	md5($this->currentURL().'_'.$template.'_'.md5(serialize($this->contentData))).'.cache';
			$getTemplate	=	cache::get_template_element($cacheName, $cacheExpiresTime);

			if($getTemplate !== false)
			{
				$loadTemplate	=	$getTemplate;
				require $loadTemplate;
				return;
			}
			else
			{
				ob_start();

				require $headerPath;
				require $templatePath;
				require $footerPath;

				$output	=	ob_get_contents();

				$setTemplateElement	=	cache::set_template_element($cacheName, $output);

				ob_end_clean();

				if($setTemplateElement === false)
				{
					throw new \Exception('setTemplateElement not write');
				}

				echo $output;

				return;
			}
		}
		else
		{
			$loadTemplate	=	$this->tempDir.$this->skin.SEP.$template;
		}

		require $headerPath;

		require $loadTemplate;

		require $footerPath;
	}



	/**
	 * Lädt das angegebene Template ohne Header und Footer Template
	 *
	 * @param string $template Das anzuzeigende Template. Relativ zum angegebenen Template Verzeichnis.
	 * @param bool $cacheActive Aktiviert bzw. Deaktiviert den Cache
	 * @param int $cacheExpiresTime Die Dauer des Caches. Unendlich = 0. Standartmäßig 0
	 * @return void
	 * @throws \Exception
	 */
	public function displayNP($template, $cacheActive = false, $cacheExpiresTime = 0)
	{
		ob_start();

		foreach($this->contentData as $key => $value)
		{
			${$key}	=	$value;
		}

		$templatePath	=	$this->tempDir.$template;

		if($cacheActive === true)
		{
			if(class_exists('\package\cache') === false)
			{
				throw new \Exception('Error: class cache not found');
			}

			$cacheName		=	md5($this->currentURL().'_'.$template.'_'.md5(serialize($this->contentData))).'.cache';
			$getTemplate	=	cache::get_template_element($cacheName, $cacheExpiresTime);

			if($getTemplate !== false)
			{
				$templatePath	=	$getTemplate;
			}
			else
			{
				ob_start();

				require $this->tempDir.$this->skin.SEP.$template;

				$setTemplateElement	=	cache::set_template_element($cacheName, ob_get_contents());

				ob_end_clean();

				if($setTemplateElement === false)
				{
					throw new \Exception('setTemplateElement not write');
				}

				$templatePath	=	cache::get_template_element($cacheName, $cacheExpiresTime);

				if($templatePath === false)
				{
					throw new \Exception('template '.$template.' in cache not found');
				}
			}
		}

		require $templatePath;
	}


	/**
	 * Lädt eine Datei aus dem Template Verzeichnis (js,css,images) und gibt den Inhalt zurück
	 *
	 * @param string $file Dateiname der zu ladenen Datei
	 * @param string $type css,js,javascript,img,image,images
	 * @param string $dir Der Ordnernamen falls nicht mit $type übereinstimmend
	 * @param bool $minify Definiert ob die Ausgabe von JavaScript oder CSS komprimiert werden soll
	 *
	 * @return mixed Gibt den Inhalt der Datei zurück
	 */
	public function load_template_file($file, $type, $dir = '', $minify = true)
	{
		return HTTP.'getTemplateFile.php?f='.$file.'&t='.$type.'&s='.$this->skin.'&d='.$dir.'&c='.$minify;
	}
} 