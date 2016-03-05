<?php
require_once 'init.php';

\package\core\autoload::get('curl', 'package\core\\', true);

$url	=	'http://www.webpackages.de/autorisation.php';

$data	=	\package\core\curl::get_data($url);

if($data !== false)
{
	if(file_put_contents(ROOT.'webpackages.zip', $data) !== false)
	{
		$zipArchive	=	new ZipArchive();

		if($zipArchive->open(ROOT.'webpackages.zip') === true)
		{
			if($zipArchive->extractTo(ROOT.'webpackages') === true)
			{
				$currentConstants	=	file_get_contents('constants.php');
				$newConstants		=	file_get_contents(ROOT.'webpackages'.SEP.'constants.php');

				preg_match_all("/define\\('(.*?)',\\s(.*?)\\);/m", $currentConstants, $matches);

				$notAllowedUserConstants	=	array('HTTP', 'SEP', 'OS', 'ROOT', 'MYSQL_FUNCTIONS');

				foreach($matches[1] as $k => $match)
				{
					$value	=	$matches[2][$k];

					if(in_array($match, $notAllowedUserConstants) === false)
					{
						$newConstants	=	preg_replace('/define\(\''.$match.'\',\s(.*?)\);/m', 'define(\''.$match.'\', '.$value.');', $newConstants);

					}
				}

				preg_match("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", $currentConstants, $userConstants);

				$newConstants	=	preg_replace("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", '#USER_CONTENT_BEGIN'.$userConstants[2].'#USER_CONTENT_END', $newConstants);

				file_put_contents(ROOT.'webpackages'.SEP.'constants.php', $newConstants);

				removeInitialFiles();
				copyFileToRoot();

				unlink(ROOT.'webpackages.zip');

				header('Location: '.HTTP);
			}
		}
	}
}


function copyFileToRoot()
{
	$webpackages	=	new RecursiveDirectoryIterator(ROOT.'webpackages', RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator		=	new RecursiveIteratorIterator($webpackages, RecursiveIteratorIterator::CHILD_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $file)
		{
			if($file instanceof SplFileInfo)
			{
				if($file->isFile() === true)
				{
					$newFilePath	=	new SplFileInfo(str_replace('webpackages'.SEP, '', $file->__toString()));

					if(file_exists($newFilePath->getPath()) === false)
					{
						mkdir($newFilePath->getPath(), 0755, true);
					}

					rename($file->__toString(), $newFilePath);
				}
				else
				{
					rmdir($file->__toString());
				}
			}
		}

		rmdir(ROOT.'webpackages');
	}
}


function removeInitialFiles()
{
	$welcomeView	=	new RecursiveDirectoryIterator(ROOT.'webpackages'.SEP.'package'.SEP.'views'.SEP.'welcome', RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator		=	new RecursiveIteratorIterator($welcomeView, RecursiveIteratorIterator::CHILD_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $file)
		{
			if($file instanceof SplFileInfo)
			{
				if($file->isFile() === true)
				{
					unlink($file->__toString());
				}
				else
				{
					rmdir($file->__toString());
				}
			}
		}

		rmdir(ROOT.'webpackages'.SEP.'package'.SEP.'views'.SEP.'welcome');
	}

	$testPlugin	=	new RecursiveDirectoryIterator(ROOT.'webpackages'.SEP.'package'.SEP.'system'.SEP.'plugins'.SEP.'testPlugin', RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator	=	new RecursiveIteratorIterator($testPlugin, RecursiveIteratorIterator::CHILD_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $file)
		{
			if($file instanceof SplFileInfo)
			{
				if($file->isFile() === true)
				{
					unlink($file->__toString());
				}
				else
				{
					rmdir($file->__toString());
				}
			}
		}

		rmdir(ROOT.'webpackages'.SEP.'package'.SEP.'system'.SEP.'plugins'.SEP.'testPlugin');
	}

	unlink(ROOT.'webpackages'.SEP.'package'.SEP.'controllers'.SEP.'welcome.class.php');
	unlink(ROOT.'webpackages'.SEP.'package'.SEP.'models'.SEP.'test.php');
}