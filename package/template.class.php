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

    @category   template.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


class template 
{
	protected $contentData = array(), $caching = false, $gzip = true, $tempDir, $header, $footer;

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
	 * Leitet den Browser auf die übergeben URL weiter
	 *
	 * @param string $url Die URL die aufgerufen werden soll.
	 * @return void
	 */
	public function loc($url)
	{
		header('Location: '.$url);
		exit;
	}


	/**
	 * Aktualisiert die Seite
	 *
	 * @return void
	 */
	public function reload()
	{
		$this->loc($this->currentURL());
		exit;
	}


	/**
	 * Gibt die aktuelle URL zurück
	 *
	 * @return string Gibt die aktuelle URL zurück
	 */
	public function currentURL()
	{
		return (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
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

		if($cacheActive === true)
		{
			if(class_exists('\package\cache') === false)
			{
				throw new \Exception('class cache not found');
			}

			$cacheName		=	md5($this->currentURL().'_'.$template.'_'.$header.'_'.$footer.'_'.md5(serialize($this->contentData))).'.cache';
			$getTemplate	=	cache::getTemplateElement($cacheName, $cacheExpiresTime);

			if($getTemplate !== false)
			{
				$loadHeader		=	false;
				$loadTemplate	=	$getTemplate;
				$loadFooter		=	false;
			}
			else
			{
				ob_start();

				require $this->tempDir.$header;
				require $this->tempDir.$template;
				require $this->tempDir.$footer;

				$setTemplateElement	=	cache::setTemplateElement($cacheName, ob_get_contents());

				ob_end_clean();

				if($setTemplateElement === false)
				{
					throw new \Exception('setTemplateElement not write');
				}

				$loadHeader		=	false;
				$loadTemplate	=	cache::getTemplateElement($cacheName, $cacheExpiresTime);
				$loadFooter		=	false;
			}
		}
		else
		{
			$loadHeader		=	$this->tempDir.$header;
			$loadTemplate	=	$this->tempDir.$template;
			$loadFooter		=	$this->tempDir.$footer;
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

		if($cacheActive === true)
		{
			if(class_exists('\package\cache') === false)
			{
				throw new \Exception('class cache not found');
			}

			$cacheName		=	md5($this->currentURL().'_'.$template.'_'.md5(serialize($this->contentData))).'.cache';
			$getTemplate	=	cache::getTemplateElement($cacheName, $cacheExpiresTime);

			if($getTemplate !== false)
			{
				$loadTemplate	=	$getTemplate;
				require $loadTemplate;
				return;
			}
			else
			{
				ob_start();

				require $this->tempDir.$this->header;
				require $this->tempDir.$template;
				require $this->tempDir.$this->footer;

				$output	=	ob_get_contents();

				$setTemplateElement	=	cache::setTemplateElement($cacheName, $output);

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
			$loadTemplate	=	$this->tempDir.$template;
		}

		require $this->tempDir.$this->header;

		require $loadTemplate;

		require $this->tempDir.$this->footer;
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
				throw new \Exception('class cache not found');
			}

			$cacheName		=	md5($this->currentURL().'_'.$template.'_'.md5(serialize($this->contentData))).'.cache';
			$getTemplate	=	cache::getTemplateElement($cacheName, $cacheExpiresTime);

			if($getTemplate !== false)
			{
				$templatePath	=	$getTemplate;
			}
			else
			{
				ob_start();

				require $this->tempDir.$template;

				$setTemplateElement	=	cache::setTemplateElement($cacheName, ob_get_contents());

				ob_end_clean();

				if($setTemplateElement === false)
				{
					throw new \Exception('setTemplateElement not write');
				}

				$templatePath	=	cache::getTemplateElement($cacheName, $cacheExpiresTime);

				if($templatePath === false)
				{
					throw new \Exception('template '.$template.' in cache not found');
				}
			}
		}

		require $templatePath;
	}



	/**
	 * Validiert eine URL und gibt diese "Sauber" zurück
	 *
	 * @param string $url Die URL die Validiert werden soll. Erlaubt sind alle Buchstaben, Zahlen und $-_.+!*'{}|^~[]`#%/?@&= Zeichen
	 * @return string
	 */
	public function createValidURL($url)
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'template', 'createValidURL', array($url));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$url	=	preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $url)));

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'template', 'createValidURL', array($url));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $url;
	}


	/**
	 * Entfernt einen Ordner Rekursiv
	 *
	 * @param string $dirPath Der realative Pfad zum Ordner
	 * @return bool Gibt ein tru bei Erfolg und ein false bein einem Fehler zurück.
	 */
	public function removeFolder($dirPath)
	{
		$recu	=	new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);

		foreach($recu as $path)
		{
			if($path->isDir() === true)
			{
				$removeFolder	=	@rmdir($path->getPathname());

				if($removeFolder === false)
				{
					return false;
				}
			}
			else
			{
				$unlinkFile	=	@unlink($path->getPathname());

				if($unlinkFile === false)
				{
					return false;
				}
			}
		}

		$removeFolder	=	@rmdir($dirPath);

		if($removeFolder === false)
		{
			return false;
		}

		return true;
	}


	/**
	 * Schickt einen zur letzten Seite zurück (Javascript)
	 *
	 * @return void
	 */
	public function back()
	{
		if(empty($_SERVER['HTTP_REFERER']) === false)
		{
			$this->loc($_SERVER['HTTP_REFERER']);
		}
		else
		{
			echo '
			<script type="text/javascript">
				history.back(-1);
			</script>
			';

			exit;
		}
	}

	/**
	 * Konvertiert ein Datumsformat
	 *
	 * @param int $date Konvertiert ein UNIX Timestamp in ein valides Datum
	 * @param string $convert Konvertierungsformat. Standartmäßig "%d %B %y"
	 * @return string Gibt das Konvertierte Datum zurück
	 */
	public function convertDatetime($date, $convert = '%d %B %y')
	{
		$timestampe	=	strtotime($date);

		return strftime($convert, $timestampe);
	}




	/**
	 * Verschiebt ein ganzes Verzeichnis
	 *
	 * @param string $source Relativer Pfad zum Ursprungsverzeichnis
	 * @param string $dest Relativer Pfad zum Zielverzeichnis
	 * @param int $chmod Ändert Anschließend die Zugriffsrechte, wenn erlaubt. Standartmäßig "0755"
	 *
	 * @return bool Gibt true bei Erfolg und false bei einem Fehler zurück.
	 */
	public function renameDirectory($source, $dest, $chmod = 0755)
	{
		if(is_dir($source) === true)
		{
			$iterator	=	new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);

			foreach($iterator as $item)
			{
				$file	=	new \SplFileInfo($item);

				if($file->isDir() === true)
				{
					$destFolder	=	$dest.$file->getFilename().SEP;

					if(is_dir($destFolder) === false)
					{
						$mkdir	=	@mkdir($destFolder, 0777, true);

						if($mkdir === false)
						{
							return false;
						}
					}

					$back	=	$this->renameDirectory($file->__toString(), $destFolder, $chmod);

					if($back === false)
					{
						return false;
					}
				}
				else
				{
					$rename	=	@rename($file->__toString(), $dest.$file->getFilename());

					if($rename === false)
					{
						return false;
					}

					$chmod	=	@chmod($dest.$file->getFilename(), $chmod);

					if($chmod === false)
					{
						return false;
					}
				}
			}

			$rmdir	=	@rmdir($source);

			if($rmdir === false)
			{
				return false;
			}
		}
		else
		{
			$rename	=	@rename($source, $dest);

			if($rename === false)
			{
				return false;
			}

			$chmod	=	@chmod($dest, $chmod);

			if($chmod === false)
			{
				return false;
			}
		}

		return true;
	}


	/**
	 * Kopiert ein ganzes Verzeichnis
	 *
	 * @param string $source Relativer Pfad zum koopierenden Verzeichnis
	 * @param string $dest Relativer Pfad zum Zielverzeichnis
	 * @param int $chmod Ändert anschließend die Zugriffsrechte im Zielverzeichnis, wenn erlaubt. Standartmäßig "0755"
	 * @return bool
	 */
	public function copyDirectory($source, $dest, $chmod = 0755)
	{
		if(is_dir($source) === true)
		{
			$iterator	=	new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);

			foreach($iterator as $item)
			{
				$file	=	new \SplFileInfo($item);

				if($file->isDir() === true)
				{
					$destFolder	=	$dest.$file->getFilename().SEP;

					if(is_dir($destFolder) === false)
					{
						$mkdir	=	@mkdir($destFolder, 0777, true);

						if($mkdir === false)
						{
							return false;
						}
					}

					$back	=	$this->copyDirectory($file->__toString(), $destFolder, $chmod);

					if($back === false)
					{
						return false;
					}
				}
				else
				{
					$copy	=	@copy($file->__toString(), $dest.$file->getFilename());

					if($copy === false)
					{
						return false;
					}

					$chmod	=	@chmod($dest.$file->getFilename(), $chmod);

					if($chmod === false)
					{
						return false;
					}
				}
			}
		}
		else
		{
			$copy	=	@copy($source, $dest);

			if($copy === false)
			{
				return false;
			}

			$chmod	=	@chmod($dest, $chmod);

			if($chmod === false)
			{
				return false;
			}
		}

		return true;
	}
} 