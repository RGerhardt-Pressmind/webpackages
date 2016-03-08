<?php
require_once 'init.php';

define('INSTALL_DIR',	ROOT.SEP.'update_webpackages'.SEP);

\package\core\autoload::get('curl', 'package\core\\', true);
\package\core\autoload::get('version', 'package\core\\', true);

$current_version	=	\package\core\curl::get_data('http://www.webpackages.de/getCurrentVersion.php');
$current_version	=	json_decode($current_version, true);

function copyFileToRoot()
{
	$webpackages	=	new RecursiveDirectoryIterator(INSTALL_DIR, RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator		=	new RecursiveIteratorIterator($webpackages, RecursiveIteratorIterator::CHILD_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $file)
		{
			if($file instanceof SplFileInfo)
			{
				if($file->isFile() === true)
				{
					$newFilePath	=	new SplFileInfo(str_replace(INSTALL_DIR, ROOT.SEP, $file->__toString()));

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

		rmdir(INSTALL_DIR);
	}
}


function removeInitialFiles()
{
	$welcomeView	=	new RecursiveDirectoryIterator(INSTALL_DIR.'package'.SEP.'views'.SEP.'welcome', RecursiveDirectoryIterator::SKIP_DOTS);
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

		rmdir(INSTALL_DIR.'package'.SEP.'views'.SEP.'welcome');
	}

	$testPlugin	=	new RecursiveDirectoryIterator(INSTALL_DIR.'package'.SEP.'system'.SEP.'plugins'.SEP.'testPlugin', RecursiveDirectoryIterator::SKIP_DOTS);
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

		rmdir(INSTALL_DIR.'package'.SEP.'system'.SEP.'plugins'.SEP.'testPlugin');
	}

	unlink(INSTALL_DIR.'package'.SEP.'controllers'.SEP.'welcome.class.php');
	unlink(INSTALL_DIR.'package'.SEP.'models'.SEP.'test.php');
}

if(isset($_POST['update']) === true)
{
	$url	=	'http://www.webpackages.de/autorisation.php';

	$data	=	\package\core\curl::get_data($url);

	if($data !== false)
	{
		if(file_put_contents(ROOT.SEP.'webpackages.zip', $data) !== false)
		{
			$zipArchive	=	new ZipArchive();

			if($zipArchive->open(ROOT.SEP.'webpackages.zip') === true)
			{
				if($zipArchive->extractTo(INSTALL_DIR) === true)
				{
					$currentConstants	=	file_get_contents('constants.php');
					$newConstants		=	file_get_contents(INSTALL_DIR.'constants.php');

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

					file_put_contents(INSTALL_DIR.'constants.php', $newConstants);

					$newHtaccess	=	file_get_contents(INSTALL_DIR.'.htaccess');
					$oldHtaccess	=	file_get_contents(ROOT.'.htaccess');

					preg_match("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", $oldHtaccess, $userHtaccess);

					$newHtaccess	=	preg_replace("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", '#USER_CONTENT_BEGIN'.$userHtaccess[2].'#USER_CONTENT_END', $newHtaccess);

					file_put_contents(INSTALL_DIR.'.htaccess', $newHtaccess);

					removeInitialFiles();
					copyFileToRoot();

					unlink(ROOT.SEP.'webpackages.zip');

					header('Location: '.HTTP);
				}
			}
		}
	}
}

$isActual	=	version_compare($current_version['current_version'], \package\core\version::VERSION);
?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Framework aktualisieren</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="text-center">webpackages Framework aktualisieren</h3>
			</div>
			<div class="col-md-4">&nbsp;</div>
			<div class="col-md-4">
				<p class="text-center"><strong>Ihre Version:</strong> <?php echo 'v'.\package\core\version::VERSION; ?></p>
				<p class="text-center"><strong>Aktuellste Version:</strong> <?php echo 'v'.$current_version['current_version']; ?></p>
				<?php
				if($isActual > 0)
				{
				?>
				<form action="" method="post">
					<input type="hidden" name="update">
					<p class="text-center"><button class="btn btn-success">Aktualisieren</button></p>
				</form>
				<?php
				}
				else
				{
				?>
				<p class="text-center"><span class="btn btn-primary">keine Aktualisierung möglich</span></p>
				<?php
				}
				?>
			</div>
			<div class="col-md-4">&nbsp;</div>
		</div>
	</div>
</body>
</html>