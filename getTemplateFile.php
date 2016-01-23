<?php
require_once 'constants.php';

if(!empty($_GET['t']))
{
	$type	=	$_GET['t'];
}
else
{
	header('HTTP/1.1 404 Not Found');
	exit;
}

if(!empty($_GET['f']))
{
	$filename	=	$_GET['f'];
}
else
{
	header('HTTP/1.1 404 Not Found');
	exit;
}

$skin	=	'';

if(!empty($_GET['s']))
{
	$skin	=	$_GET['s'].SEP;
}


$dir	=	'';

if(!empty($_GET['d']))
{
	$dir	=	$_GET['d'].SEP;
}
else
{
	$dir	=	strtolower($type).SEP;
}

$withConvert	=	true;

if(isset($_GET['c']) === true)
{
	$withConvert	=	($_GET['c'] == 'false') ? false : true;
}

require_once LIB_DIR.'minify'.SEP.'src'.SEP.'Minify.php';
require_once LIB_DIR.'minify'.SEP.'src'.SEP.'Exception.php';
require_once LIB_DIR.'minify'.SEP.'src'.SEP.'Converter.php';

switch(strtolower($type))
{
	case 'css':

		require_once LIB_DIR.'minify'.SEP.'src'.SEP.'CSS.php';

		$file	=	TEMPLATE_DIR.$skin.$dir.$filename.'.css';

		if(file_exists($file))
		{
			header('Content-type: text/css;charset: UTF-8');
			header('Cache-Control: must-revalidate');
			$offset = 60 * 60 ;
			$ExpStr = 'Expires: '.gmdate('D, d M Y H:i:s', time() + $offset).' GMT';
			header($ExpStr);

			if($withConvert === true)
			{
				$minifier = new \MatthiasMullie\Minify\CSS($file);
				echo $minifier->minify();
				exit;
			}
			else
			{
				echo file_get_contents($file);
				exit;
			}
		}
		else
		{
			header('HTTP/1.1 404 Not Found');
		}

	break;
	case 'js':
	case 'javascript':

		require_once LIB_DIR.'minify'.SEP.'src'.SEP.'JS.php';

		$file	=	TEMPLATE_DIR.$skin.$dir.$filename.'.js';

		if(file_exists($file))
		{
			header('Content-type: text/javascript;charset: UTF-8');
			header('Cache-Control: must-revalidate');
			$offset = 60 * 60 ;
			$ExpStr = 'Expires: '.gmdate('D, d M Y H:i:s', time() + $offset).' GMT';
			header($ExpStr);

			if($withConvert === true)
			{
				$minifier	=	new \MatthiasMullie\Minify\JS($file);
				echo $minifier->minify();
				exit;
			}
			else
			{
				echo file_get_contents($file);
				exit;
			}
		}
		else
		{
			header('HTTP/1.1 404 Not Found');
		}

	break;
	default:

		header('HTTP/1.1 404 Not Found');

	break;
}