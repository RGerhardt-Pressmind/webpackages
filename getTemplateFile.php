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
 *  @package	Webpackages
 *  @subpackage core
 *  @author	    Robbyn Gerhardt
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

require_once 'constants.php';

if(empty($_GET['t']) === false)
{
	$type	=	$_GET['t'];
}
else
{
	header('HTTP/1.1 404 Not Found');
	exit;
}

if(empty($_GET['f']) === false)
{
	$filename	=	$_GET['f'];
}
else
{
	header('HTTP/1.1 404 Not Found');
	exit;
}

$skin	=	'';

if(empty($_GET['s']) === false)
{
	$skin	=	$_GET['s'].SEP;
}


$dir	=	'';

if(empty($_GET['d']) === false)
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

		if(file_exists($file) === true)
		{
			$etag	=	md5_file($file);
			$date	=	date("F d Y H:i:s.", filemtime($file));

			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Expose-Headers: ETag");
			header("Access-Control-Expose-Headers: X-Content-Length-Uncompressed");
			header("Access-Control-Expose-Headers: Content-Length");
			header("Cache-Control: public");
			header('Content-type: text/css');
			header("ETag: $etag");
			header("Last-Modified: $date");

			if((!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $date) || (!empty($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag))
			{
				header("HTTP/1.1 304 Not Modified");
				exit;
			}

			if($withConvert === true)
			{
				$minifier 	=	new \MatthiasMullie\Minify\CSS($file);
				$output		=	$minifier->minify();
			}
			else
			{
				$output		=	file_get_contents($file);
			}

			echo $output;
			exit;
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

		if(file_exists($file) === true)
		{
			$etag	=	md5_file($file);
			$date	=	date("F d Y H:i:s.", filemtime($file));

			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Expose-Headers: ETag");
			header("Access-Control-Expose-Headers: X-Content-Length-Uncompressed");
			header("Access-Control-Expose-Headers: Content-Length");
			header("Cache-Control: public");
			header('Content-type: text/javascript');
			header("ETag: $etag");
			header("Last-Modified: $date");

			if((!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $date) || (!empty($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag))
			{
				header("HTTP/1.1 304 Not Modified");
				exit;
			}

			if($withConvert === true)
			{
				$minifier	=	new \MatthiasMullie\Minify\JS($file);
				$output	=	$minifier->minify();
			}
			else
			{
				$output	= file_get_contents($file);
			}

			echo $output;
			exit;
		}
		else
		{
			header('HTTP/1.1 404 Not Found');
		}

	break;
	case 'img':

		$file	=	TEMPLATE_DIR.$skin.$dir.$filename;

		if(file_exists($file) === true)
		{
			$etag	=	md5_file($file);
			$date	=	date("F d Y H:i:s.", filemtime($file));

			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Expose-Headers: ETag");
			header("Access-Control-Expose-Headers: X-Content-Length-Uncompressed");
			header("Access-Control-Expose-Headers: Content-Length");
			header("Cache-Control: public");
			header('Content-type: '.image_type_to_mime_type(exif_imagetype($file)));
			header("ETag: $etag");
			header("Last-Modified: $date");

			if((!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $date) || (!empty($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag))
			{
				header("HTTP/1.1 304 Not Modified");
				exit;
			}

			ob_start();

			if(stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false)
			{
			  ob_start('ob_gzhandler');
			  echo file_get_contents($file);
			  ob_end_flush();
			}
			else
			{
			  	echo file_get_contents($file);
			}

			header('Content-Length: '.ob_get_length());
			ob_end_flush();
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