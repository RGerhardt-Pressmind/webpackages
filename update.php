<?php
require_once 'init.php';

define('INSTALL_DIR',	ROOT.SEP.'update_webpackages'.SEP);

\package\core\autoload::get('curl', 'package\core\\', true);
\package\core\autoload::get('version', 'package\core\\', true);

$current_version	=	\package\core\curl::get_data('https://www.webpackages.de/getCurrentVersion.php');
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
				if($file->isFile())
				{
					$newFilePath	=	new SplFileInfo(str_replace(INSTALL_DIR, ROOT.SEP, $file->__toString()));

					if(!file_exists($newFilePath->getPath()))
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
				if($file->isFile())
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

	$languagePackage	=	new RecursiveDirectoryIterator(INSTALL_DIR.'package'.SEP.'system'.SEP.'languages'.SEP, RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator	=	new RecursiveIteratorIterator($languagePackage, RecursiveIteratorIterator::CHILD_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $file)
		{
			if($file instanceof SplFileInfo)
			{
				if($file->isFile())
				{
					unlink($file->__toString());
				}
				else
				{
					rmdir($file->__toString());
				}
			}
		}

		rmdir(INSTALL_DIR.'package'.SEP.'system'.SEP.'languages');
	}

	unlink(INSTALL_DIR.'package'.SEP.'controllers'.SEP.'welcome.class.php');
	unlink(INSTALL_DIR.'package'.SEP.'models'.SEP.'test.php');
}

if(isset($_POST['update']))
{
	$security_key	=	$_POST['security_key'];


	if($security_key == SECURITY_KEY)
	{
		\package\core\curl::downloadFile($current_version['url'], ROOT.SEP.'webpackages.zip');

		if(file_exists(ROOT.SEP.'webpackages.zip'))
		{
			$zipArchive	=	new ZipArchive();

			if($zipArchive->open(ROOT.SEP.'webpackages.zip') == true)
			{
				if($zipArchive->extractTo(INSTALL_DIR))
				{
					$currentConstants	=	file_get_contents('constants.php');
					$newConstants		=	file_get_contents(INSTALL_DIR.'constants.php');

					preg_match_all("/define\\('(.*?)',\\s(.*?)\\);/m", $currentConstants, $matches);

					$notAllowedUserConstants	=	array('HTTP', 'SEP', 'OS', 'ROOT', 'MYSQL_FUNCTIONS');

					foreach($matches[1] as $k => $match)
					{
						$value	=	$matches[2][$k];

						if(!in_array($match, $notAllowedUserConstants))
						{
							$newConstants	=	preg_replace('/define\(\''.$match.'\',\s(.*?)\);/m', 'define(\''.$match.'\', '.$value.');', $newConstants);

						}
					}

					preg_match("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", $currentConstants, $userConstants);

					$newConstants	=	preg_replace("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", '#USER_CONTENT_BEGIN'.$userConstants[2].'#USER_CONTENT_END', $newConstants);

					file_put_contents(INSTALL_DIR.'constants.php', $newConstants);

					$newHtaccess	=	file_get_contents(INSTALL_DIR.'.htaccess');
					$oldHtaccess	=	file_get_contents(ROOT.SEP.'.htaccess');


					preg_match("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", $oldHtaccess, $userHtaccess);

					$newHtaccess	=	preg_replace("/(^#USER_CONTENT_BEGIN)(.*?)(#USER_CONTENT_END$)/mis", '#USER_CONTENT_BEGIN'.str_replace(array('$'), array('||||'), $userHtaccess[2]).'#USER_CONTENT_END', $newHtaccess);

					$newHtaccess	=	str_replace(array('||||'), array('$'), $newHtaccess);

					file_put_contents(INSTALL_DIR.'.htaccess', $newHtaccess);

					removeInitialFiles();
					copyFileToRoot();

					unlink(ROOT.SEP.'webpackages.zip');

					header('Location: '.HTTP);
				}
				else
				{
					$error = 'The update could not be unpacked with ZipArchive!';
				}
			}
			else
			{
				$error = 'The update could not be opened with ZipArchive!';
			}
		}
		else
		{
			$error = 'The update could not be loaded by the webpackages server!';
		}
	}
	else
	{
		$error	=	'The security key is not correct!';
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
			<?php
			if(isset($error))
			{
				echo '
				<div class="col-md-12">
					<br>
					<div class="alert alert-danger">
						<strong>Fehler!</strong> '.$error.'
					</div>
				</div>
				';
			}
			?>
			<div class="col-md-12">
				<h3 class="text-center">Update webpackages framework</h3><br>
			</div>
			<div class="col-md-4">&nbsp;</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-6">
						<strong>Your version:</strong>
					</div>
					<div class="col-md-6 text-right">
						<?php echo 'v'.\package\core\version::VERSION; ?>
					</div>
				</div>
				<br>

				<div class="row">
					<div class="col-md-6">
						<strong>Newest version:</strong>
					</div>
					<div class="col-md-6 text-right">
						<?php echo 'v'.$current_version['current_version']; ?>
					</div>
				</div>
				<br>

				<div class="row">
					<div class="col-md-12 text-center">
						<a href="https://www.webpackages.de/changelog.html" target="_blank">Go to changelog</a>
					</div>
				</div>
				<?php
				if($isActual > 0)
				{
				?>
					<form action="" method="post">
						<div class="form-group">
							<label>Security Key</label>
							<input type="text" class="form-control" name="security_key">
						</div>
						<input type="hidden" name="update">
						<p class="text-center"><button class="btn btn-success">Update</button></p>
					</form>
				<?php
				}
				else
				{
				?>
					<br>
					<p class="text-center"><span class="btn btn-primary">No updates available</span></p>
				<?php
				}
				?>
			</div>
			<div class="col-md-4">&nbsp;</div>
		</div>
	</div>
</body>
</html>