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
    
    @category   install.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

class update extends \package\load_functions
{
	/**
	 * Aktualisiert alle restlichen Dateien
	 *
	 * @return void
	 */
	public function _updateAllFilesInRoot()
	{
		if(isset($_SESSION['step5']) === false || $_SESSION['step5'] !== true)
		{
			$this->error->create404();
		}

		$masterZIP		=	ROOT.SEP.'update'.SEP.'master.zip';
		$updateFolder	=	ROOT.SEP.'update'.SEP.md5_file($masterZIP).SEP.'webpackage-master'.SEP;

		//Wenn constants.php vorhanden, dann diese zuerst
		if(file_exists($updateFolder.'constants.php') === true)
		{
			$getData	=	file_get_contents(ROOT.SEP.'constants.php');
			preg_match_all('/define\(\'(.*?)\',(.*?)\)/', $getData, $allMatches);

			if(isset($allMatches[1]) === true && is_array($allMatches[1]) === true && count($allMatches[1]) > 0)
			{
				$allValues	=	array();

				foreach($allMatches[1] as $match)
				{
					if($match == 'HTTP' || $match == 'SEP' || $match == 'OS' || $match == 'ROOT')
					{
						continue;
					}

					if($match == 'ERROR_REPORTING')
					{
						$allValues[]	=	array('constant' => $match, 'value' => (bool)constant($match));
					}
					else
					{
						$allValues[]	=	array('constant' => $match, 'value' => constant($match));
					}
				}

				$getUpdateData	=	file_get_contents($updateFolder.'constants.php');

				if(is_array($allValues) === true && count($allValues) > 0)
				{
					foreach($allValues as $value)
					{
						if(is_int($value['value']) || is_bool($value['value']))
						{
							$getUpdateData	=	preg_replace('/define\(\''.$value['constant'].'\',(.*?)\);/', 'define(\''.$value['constant'].'\', '.$value['value'].');', $getUpdateData);
						}
						else
						{
							if(strpos($value['constant'], 'PATH') || strpos($value['constant'], 'DIR'))
							{
								$value['value']	=	str_replace(ROOT.SEP, '\'.ROOT.SEP.\'', $value['value']);
								$value['value']	=	str_replace(SEP, '\'.SEP.\'', $value['value']);
							}

							$getUpdateData	=	preg_replace('/define\(\''.$value['constant'].'\',(.*?)\);/', 'define(\''.$value['constant'].'\', \''.$value['value'].'\');', $getUpdateData);
						}
					}
				}

				file_put_contents($updateFolder.'constants.php', $getUpdateData);

				if(rename($updateFolder.'constants.php', ROOT.SEP.'constants.php') === true)
				{
					echo json_encode(array('error' => false, 'endAll' => false, 'message' => 'constants.php wurde erfolgreich aktualisiert', 'percent' => 55));
					exit;
				}
			}
		}

		if(is_dir($updateFolder) === true)
		{
			$iterator	=	new \RecursiveDirectoryIterator($updateFolder, \RecursiveDirectoryIterator::SKIP_DOTS);
			$fileFound	=	false;

			foreach($iterator as $item)
			{
				$file	=	new SplFileInfo($item);

				if($file->isFile() === true)
				{
					$fileFound	=	true;
					if(rename($file, ROOT.SEP.$file->getFilename()) === true)
					{
						echo json_encode(array('error' => false, 'endAll' => false, 'message' => '<span class="text-success">Datei "'.$file->getFilename().'" erfolgreich aktualisiert</span>'));
						exit;
					}
				}
			}

			if($fileFound === false)
			{
				echo json_encode(array('error' => true, 'endAll' => true, 'message' => '<span class="text-success">Aktualisierung abgeschlossen</span>', 'percent' => 100));
			}
		}
		else
		{
			echo json_encode(array('error' => true, 'endAll' => true, 'message' => '<span class="text-danger">Archiv Update Ordner nicht gefunden</span>'));
		}
	}


	/**
	 * Aktualisiert alle Klassen im package Ordner
	 *
	 * @return void
	 * @throws Exception
	 */
	public function _updateAllClassesInPackage()
	{
		if(isset($_SESSION['step5']) === false || $_SESSION['step5'] !== true)
		{
			$this->error->create404();
		}

		$masterZIP		=	ROOT.SEP.'update'.SEP.'master.zip';
		$updateFolder	=	ROOT.SEP.'update'.SEP.md5_file($masterZIP).SEP.'webpackage-master'.SEP;

		if(is_dir($updateFolder) === true)
		{
			$copyFolder	=	$updateFolder.'package'.SEP;
			$endFolder	=	ROOT.SEP.'package'.SEP;

			$iterator	=	new \RecursiveDirectoryIterator($copyFolder, \RecursiveDirectoryIterator::SKIP_DOTS);

			if(iterator_count($iterator) > 0)
			{
				foreach($iterator as $item)
				{
					$file	=	new SplFileInfo($item);

					if($file->isDir() === true)
					{
						if($this->template->renameDirectory($file, $endFolder.$file->getFilename().SEP, 0777) === true)
						{
							echo json_encode(array('error' => false, 'endAll' => false, 'message' => 'Ordner "'.$file->getFilename().'" erfolgreich aktualisiert'));
							exit;
						}
						else
						{
							echo json_encode(array('error' => true, 'endAll' => false, 'message' => 'Ordner "'.$file->getFilename().'" konnte nicht aktualisiert werden'));
							exit;
						}
					}
					else
					{
						if(rename($file, $endFolder.$file->getFilename()) === true)
						{
							echo json_encode(array('error' => false, 'endAll' => false, 'message' => 'Klasse "'.$file->getFilename().'" erfolgreich aktualisiert'));
							exit;
						}
						else
						{
							echo json_encode(array('error' => true, 'endAll' => false, 'message' => 'Klasse "'.$file->getFilename().'" konnte nicht aktualisiert werden'));
							exit;
						}
					}
				}
			}
			else
			{
				echo json_encode(array('error' => true, 'endAll' => true, 'message' => 'Package erfolgreich aktualisiert'));
				exit;
			}
		}
		else
		{
			echo json_encode(array('error' => true, 'endAll' => false, 'message' => '<span class="text-danger">Archiv Update Ordner nicht gefunden</span>', 'percent' => 100));
			exit;
		}
	}


	/**
	 * Aktualisiert die Datei autoload.class.php
	 *
	 * @return void
	 * @throws Exception
	 */
	public function _updatePackageFileAutoload()
	{
		if(isset($_SESSION['step5']) === false || $_SESSION['step5'] !== true)
		{
			$this->error->create404();
		}

		$masterZIP		=	ROOT.SEP.'update'.SEP.'master.zip';
		$updateFolder	=	ROOT.SEP.'update'.SEP.md5_file($masterZIP).SEP.'webpackage-master'.SEP;

		if(is_dir($updateFolder) === true)
		{
			$copyFolder	=	$updateFolder.'package'.SEP.'autoload.class.php';
			$endFolder	=	ROOT.SEP.'package'.SEP.'autoload.class.php';

			if(copy($copyFolder, $endFolder) === true)
			{
				echo json_encode(array('error' => false, 'message' => '<span class="text-success">Klasse "autoload" erfolgreich aktualisiert</span>', 'percent' => 51));
			}
			else
			{
				echo json_encode(array('error' => true, 'message' => '<span class="text-danger">Klasse "autoload" konnte nicht aktualisiert werden</span>', 'percent' => 100));
			}
		}
		else
		{
			echo json_encode(array('error' => true, 'message' => '<span class="text-danger">Archiv Update Ordner nicht gefunden</span>', 'percent' => 100));
		}
	}

	/**
	 * Entpackt das master.zip Archiv
	 *
	 * @return void
	 * @throws Exception
	 */
	public function _unpackArchive()
	{
		if(isset($_SESSION['step5']) === false || $_SESSION['step5'] !== true)
		{
			$this->error->create404();
		}

		$masterZIP	=	ROOT.SEP.'update'.SEP.'master.zip';

		if(file_exists($masterZIP) === true)
		{
			if(is_writable($masterZIP) === true)
			{
				$updateFolder	=	ROOT.SEP.'update'.SEP.md5_file($masterZIP).SEP;
				$masterZIP		=	new PclZip($masterZIP);

				if(is_dir($updateFolder) === false && mkdir($updateFolder, 0777, true) === false)
				{
					echo json_encode(array('error' => true, 'message' => '<span class="text-danger">Der Archiv Ordner konnte nicht erstellt werden</span>', 'percent' => 100));
				}
				else
				{
					if($masterZIP->extract(PCLZIP_OPT_PATH, $updateFolder, PCLZIP_OPT_REPLACE_NEWER))
					{
						echo json_encode(array('error' => false, 'message' => '<span class="text-success">Archiv erfolgreich entpackt</span>', 'percent' => 15));
					}
					else
					{
						echo json_encode(array('error' => true, 'message' => '<span class="text-danger">Das Archiv konnte nicht entpackt werden</span>', 'percent' => 100));
					}
				}
			}
			else
			{
				echo json_encode(array('error' => true, 'message' => '<span class="text-danger">Die master.zip hat keine Schreibrechte, bitte ändern Sie die Zugriffsrechte auf mindestens 0755 ab</span>', 'percent' => 100));
			}
		}
		else
		{
			echo json_encode(array('error' => true, 'message' => 'master.zip Archiv nicht gefunden', 'percent' => 100));
		}
	}


	/**
	 * Fünfter Schritt
	 *
	 * @throws Exception
	 */
	public function step5()
	{
		if(isset($_SESSION['step5']) === false || $_SESSION['step5'] !== true)
		{
			$this->error->create404();
		}

		$this->template->displayDH('update/step5.php', 'update/header.php', 'update/footer.php');
	}


	/**
	 * Kontrolliert ob die master.zip im update Ordner existiert
	 *
	 * @return void
	 */
	public function existMasterZIP()
	{
		if(isset($_SESSION['step4']) === false || $_SESSION['step4'] !== true)
		{
			$this->error->create404();
		}

		$masterZIP	=	ROOT.SEP.'update'.SEP.'master.zip';

		if(file_exists($masterZIP) === true)
		{
			$_SESSION['step5']	=	true;

			echo json_encode(array('exists' => true));
		}
		else
		{
			echo json_encode(array('exists' => false));
		}
	}

	/**
	 * Vierter Schritt
	 *
	 * @return void
	 * @throws Exception
	 */
	public function step4()
	{
		if(isset($_SESSION['step4']) === false || $_SESSION['step4'] !== true)
		{
			$this->error->create404();
		}

		$this->template->displayDH('update/step4.php', 'update/header.php', 'update/footer.php');
	}

	/**
	 * Dritter Schritt
	 *
	 * @return void
	 * @throws Exception
	 */
	public function step3()
	{
		if(isset($_SESSION['step3']) === false || $_SESSION['step3'] !== true)
		{
			$this->error->create404();
		}

		\package\curl::$userAgent	=	\package\version::COMMITTER;
		$data	=	\package\curl::getData(\package\version::COMMITS);


		$decode	=	@json_decode($data, true);
		$boxIn	=	array();

		if(is_array($decode) === true && count($decode) > 0)
		{
			foreach($decode as $version)
			{
				if(isset($version['sha']) === true)
				{
					$version['commit']['message']	=	$this->convertCommitMessage($version['commit']['message']);

					preg_match('/\<h5\>(.*?)\<\/h5\>/', $version['commit']['message'], $matche);

					if(isset($matche[1]) === false)
					{
						continue;
					}

					$matche	=	str_replace('<br>', '', $matche[1]);
					$matche	=	preg_replace('/([0-9]{2}).([0-9]{2}).([0-9]{4})/', '', $matche);
					$orgVer	=	str_replace(' /', '', $matche);
					$matche	=	(int)str_replace('.', '', $orgVer);

					if($matche <= (int)str_replace('.', '', \package\version::VERSION))
					{
						break;
					}

					$boxIn[]	=	array('version' => $orgVer, 'commit' => $version['commit'], 'url' => $version['url']);
				}
			}
		}

		$this->template->setData(array(
			'boxIn'	=>	$boxIn
		));
		$this->template->displayDH('update/step3.php', 'update/header.php', 'update/footer.php');
	}


	/**
	 * Formartiert die Nachricht
	 *
	 * @param string $message
	 * @return string
	 */
	private function convertCommitMessage($message)
	{
		$message	=	str_replace("\n", "<br>", $message).'<br>';
		$message	=	'<h5>'.$message;
		$message	=	str_replace('==================', '</h5><ul>', $message);
		$message	=	preg_replace("/\* (.*?)<br>/", "<li>$1</li><br>", $message).'</ul>';


		return $message;
	}

	/**
	 * Zweiter Schritt
	 *
	 * @return void
	 */
	public function step2()
	{
		$this->template->displayDH('update/step2.php', 'update/header.php', 'update/footer.php');
	}


	/**
	 * Erster Schritt
	 *
	 * @return void
	 */
	public function step1()
	{
		$this->template->displayDH('update/step1.php', 'update/header.php', 'update/footer.php', true, 600);
	}
}