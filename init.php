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
 * @package       Webpackages
 * @subpackage    controllers
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

require 'constants.php';

if(empty(SECURITY_KEY) === true)
{
	throw new Exception('Error: SECURITY_KEY constants is empty');
}
elseif(strlen(SECURITY_KEY) < 20)
{
	throw new Exception('Error: SECURITY_KEY constants must be at least 20 characters');
}

require SYSTEM_PATH.'controllExistsPaths.php';
require SYSTEM_PATH.'loadSessionHandler.php';

if(defined('USE_SESSION_SAVE_HANDLER') === false || USE_SESSION_SAVE_HANDLER === false || defined('PDO_HOST') === false || empty(PDO_HOST) === true)
{
	session_start();
}

if(defined('ERROR_REPORTING') === true && (ERROR_REPORTING === true || ERROR_REPORTING === 1))
{
	error_reporting(-1);
	ini_set('display_errors', 1);
}
else
{
	error_reporting(0);
	ini_set('display_errors', 0);
}

if(defined('CHARSET') === true && empty(CHARSET) === false)
{
	header('Content-Type: text/html; charset='.CHARSET);
}

if(defined('TIMEZONE') === true && empty(TIMEZONE) === false)
{
	date_default_timezone_set(TIMEZONE);
}

$myPaths   = [];
$myPaths[] = PACKAGE_DIR;
$myPaths[] = SYSTEM_PATH;
$myPaths[] = IMPLEMENT_DIR;
$myPaths[] = CORE_DIR;
$myPaths[] = LIB_DIR;
$myPaths[] = LIB_DIR.'gettext';
$myPaths[] = LIB_DIR.'gettext'.SEP.'php5';
$myPaths[] = LIB_DIR.'PHPMailer';
$myPaths[] = LIB_DIR.'minifiy';

if(defined('PAGE_DIR') === true && empty(PAGE_DIR) === false)
{
	$myPaths[] = PAGE_DIR;

	$director = new \RecursiveDirectoryIterator(PAGE_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator = new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::SELF_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $item)
		{
			if($item->isDir() === true)
			{
				$myPaths[] = $dir;
			}
		}
	}
}

//Alle Dynamischen Klassen in include_path aufnehmen
if(defined('DYNAMIC_DIR') === true && empty(DYNAMIC_DIR) === false)
{
	$myPaths[] = DYNAMIC_DIR;

	$director = new \RecursiveDirectoryIterator(DYNAMIC_DIR, \RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator = new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::SELF_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $item)
		{
			if($item->isDir() === true)
			{
				$myPaths[] = $dir;
			}
		}
	}
}

ini_set('include_path', get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $myPaths));


//Alle Implements Klassen includieren
if(defined('IMPLEMENT_DIR') === true && empty(IMPLEMENT_DIR) === false)
{
	$implements	=	new RecursiveDirectoryIterator(IMPLEMENT_DIR, RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator	=	new RecursiveIteratorIterator($implements, RecursiveIteratorIterator::SELF_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $file)
		{
			if($file->isFile() && $file->getFilename() != '.htaccess')
			{
				require_once $file->__toString();
			}
		}
	}
}

//Alle Exceptions Klassen includieren
if(defined('EXCEPTION_DIR') === true && empty(EXCEPTION_DIR) === false)
{
	$exceptions	=	new RecursiveDirectoryIterator(EXCEPTION_DIR, RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator	=	new RecursiveIteratorIterator($exceptions, RecursiveIteratorIterator::SELF_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $file)
		{
			if($file->isFile() && $file->getFilename() != '.htaccess')
			{
				require_once $file->__toString();
			}
		}
	}
}

require 'gettext_reader.php';
require 'FileReader.php';

require 'autoload.class.php';

require 'version.class.php';
require 'benchmark.class.php';
require 'security.class.php';
require 'load_functions.abstract.class.php';

if(file_exists(ROOT.SEP.'dynamicInit.php') === true)
{
	require ROOT.SEP.'dynamicInit.php';
}