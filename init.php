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

    @category   init.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/
require 'constants.php';
require 'controllExistsPaths.php';
require 'loadSessionHandler.php';

if(!defined('USE_SESSION_SAVE_HANDLER') || !USE_SESSION_SAVE_HANDLER || !defined('PDO_HOST') || empty(PDO_HOST))
{
	session_start();
}


if(ERROR_REPORTING === true || ERROR_REPORTING == 1)
{
	error_reporting(-1);
	ini_set('display_errors', 1);
}
else
{
	error_reporting(0);
	ini_set('display_errors', 0);
}

if(CHARSET != '')
{
	header('Content-Type: text/html; charset='.CHARSET);
}

if(TIMEZONE != '')
{
	date_default_timezone_set(TIMEZONE);
}

$myPaths	=	array();
$myPaths[]	=	ROOT.SEP.'update';
$myPaths[]	=	ROOT.SEP.'package';
$myPaths[]	=	ROOT.SEP.'package'.SEP.'implement';
$myPaths[]	=	ROOT.SEP.'package'.SEP.'core';
$myPaths[]	=	ROOT.SEP.'package'.SEP.'gettext';
$myPaths[]	=	ROOT.SEP.'package'.SEP.'gettext'.SEP.'php5';
$myPaths[]	=	ROOT.SEP.'package'.SEP.'PHPMailer';
$myPaths[]	=	ROOT.SEP.'package'.SEP.'libs';

if(PAGE_DIR != '')
{
	$myPaths[]	=	PAGE_DIR;

	$director	=	new \RecursiveDirectoryIterator(PAGE_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator	=	new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::LEAVES_ONLY);

	foreach($iterator as $item)
	{
		$dir	=	new SplFileInfo($item);

		if($dir->isDir() === true)
		{
			$myPaths[]	=	$dir;
		}
	}
}

if(DYNAMIC_DIR != '')
{
	$myPaths[]	=	DYNAMIC_DIR;

	$director	=	new \RecursiveDirectoryIterator(DYNAMIC_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator	=	new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::LEAVES_ONLY);

	foreach($iterator as $item)
	{
		$dir	=	new SplFileInfo($item);

		if($dir->isDir() === true)
		{
			$myPaths[]	=	$dir;
		}
	}
}

ini_set('include_path', get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $myPaths));

require 'gettext_reader.php';
require 'FileReader.php';

require 'autoload.class.php';

require 'iLogger.class.php';
require 'iDynamic.class.php';
require 'IPlugin.class.php';

require 'Validater.class.php';
require 'security.class.php';
require 'GeneralFunctions.abstract.class.php';
require 'load_functions.abstract.class.php';

if(file_exists(ROOT.SEP.'dynamicInit.php') === true)
{
	require ROOT.SEP.'dynamicInit.php';
}