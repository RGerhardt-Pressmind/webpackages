<?php
#error_reporting(-1);
#ini_set('display_errors', 1);

if(PHP_SAPI !== 'cli')
{
	exit('The script may only be executed via the terminal.');
}

function delete_directory($dirPath)
{
	if (substr($dirPath, strlen($dirPath) - 1, 1) !== DIRECTORY_SEPARATOR) {
        $dirPath .= DIRECTORY_SEPARATOR;
    }

    $files	=	new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($files as $file) {
    	if($file->isFile())
		{
			unlink($file->__toString());
		}
		else
		{
			delete_directory($file->__toString());
		}
    }

    rmdir($dirPath);
}

function _log($type, $str)
{
	switch($type)
	{
		default:
		case 'info':

			$color	=	'0;37';
			$prefix	=	'Info:';

		break;
		case 'warning':

			$color	=	'0;33';
			$prefix	=	'Warning:';

		break;
		case 'error':

			$color	=	'0;31';
			$prefix	=	'Error:';

		break;
	}

	$date	=	new DateTime('now');

	echo "\033[".$color."m".$date->format('d.m.Y H:i:s')." - ".$prefix." ".$str."\033[0m".PHP_EOL;
}

define('ROOT', str_replace('update', '', __DIR__));

$pathToUpdate	=	'https://github.com/RGerhardt-Pressmind/webpackages/archive/refs/heads/master.zip';

$checkFolderIsWritable	=	[
	ROOT.'update',
	ROOT.'system',
	ROOT.'cache'
];

foreach($checkFolderIsWritable as $path)
{
	$folder_perms	=	fileperms($path);

	_log('info', 'Check folder writable permissions: "'.$path.'"');

	if(!($folder_perms & 0x0080) && !($folder_perms & 0x0010) && !($folder_perms & 0x0002))
	{
		_log('error', 'Folder "'.$path.'" is not writable');
		exit;
	}
}

$localePath	=	ROOT.'cache'.DIRECTORY_SEPARATOR.'update'.DIRECTORY_SEPARATOR;

_log('info', 'Check exist cache/update folder');

if(!file_exists($localePath))
{
	_log('info', 'cache/update folder exist not, create');
	mkdir($localePath, 0755, true);
}

if(file_exists($localePath.'webpackages-master'.DIRECTORY_SEPARATOR))
{
	delete_directory($localePath.'webpackages-master'.DIRECTORY_SEPARATOR);
}

$ch	=	curl_init();
curl_setopt($ch, CURLOPT_URL, $pathToUpdate);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

_log('info', 'Download zip file');

$zipData = curl_exec($ch);

if(curl_errno($ch))
{
	_log('error', 'Error by download update core file: '.curl_error($ch));
	exit;
}

curl_close($ch);

if(!file_put_contents($localePath.'master.zip', $zipData))
{
	_log('error', 'Error by save the zip file');
	exit;
}

$zip	=	new ZipArchive();

if(!$zip->open($localePath.'master.zip'))
{
	_log('error', 'Error by open zip file');
	exit;
}

if(!$zip->extractTo($localePath))
{
	_log('error', 'Error by extra zip file');
	exit;
}

$zip->close();

if(!unlink($localePath.'master.zip'))
{
	_log('warning', 'Cannot remove master.zip file after extract, please remove this file ('.$localePath.'master.zip)');
}

$localePath	=	$localePath.'webpackages-master'.DIRECTORY_SEPARATOR;

if(!file_exists($localePath))
{
	_log('error', 'Error, webpackages-master dir in cache/update not exist');
}

$copyItems	=	[
	[
		'from'	=>	$localePath.'index.php',
		'to'	=>	ROOT.'index.php'
	],
	[
		'from'	=>	$localePath.'config.php',
		'to'	=>	ROOT.'config.php'
	],
	[
		'from'	=>	$localePath.'composer.json',
		'to'	=>	ROOT.'composer.json'
	],
	[
		'from'	=>	$localePath.'system',
		'to'	=>	ROOT.'system'
	],
	[
		'from'	=>	$localePath.'update',
		'to'	=>	ROOT.'update'
	]
];

function move_directory($source, $destination): void
{
    if (!is_dir($destination))
    {
        mkdir($destination, 0755, true);
    }

    $source = rtrim($source, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $destination = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);

    foreach ($files as $file)
    {
        if (!$file->isDir())
        {
        	$file_path = str_replace($source, $destination, $file->__toString());
        	$dirname = dirname($file_path);

        	if(!file_exists($dirname))
			{
				mkdir($dirname, 0755, true);
			}

        	_log('info', 'Move file from "'.$file->__toString().'" to "'.$file_path.'"');

        	rename($file, $file_path);
        }
    }
}

foreach($copyItems as $copyItem)
{
	if(is_file($copyItem['to']))
	{
		unlink($copyItem['to']);
	}
	else if(is_dir($copyItem['to']))
	{
		delete_directory($copyItem['to']);
	}

	if(file_exists($copyItem['from']))
	{
		if(is_file($copyItem['from']))
		{
			rename($copyItem['from'], $copyItem['to']);
		}
		else if(is_dir($copyItem['from']))
		{
			move_directory($copyItem['from'], $copyItem['to']);
		}
	}
}

if(file_exists(ROOT.'.htaccess') && file_exists($localePath.'.htaccess'))
{
	_log('info', 'Move .htaccess');
	$htaccess	=	file_get_contents(ROOT.'.htaccess');

	preg_match('/(#USER_CONTENT_BEGIN(.*?)#USER_CONTENT_END)/ms', $htaccess, $matches);

	rename($localePath.'.htaccess', ROOT.'.htaccess');

	if(file_exists(ROOT.'.htaccess') && !empty($matches[1]))
	{
		$htaccess	=	file_get_contents(ROOT.'.htaccess');
		$htaccess	=	preg_replace('/(#USER_CONTENT_BEGIN(.*?)#USER_CONTENT_END)/ms', $matches[1], $htaccess);

		file_put_contents(ROOT.'.htaccess', $htaccess);
	}
}

_log('info', 'Remove update folder');
delete_directory(ROOT.'cache'.DIRECTORY_SEPARATOR.'update');

$executableFiles	=	ROOT.'update'.DIRECTORY_SEPARATOR.'executable'.DIRECTORY_SEPARATOR;

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($executableFiles, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

foreach($files as $file)
{
	require_once $file->__toString();
}
