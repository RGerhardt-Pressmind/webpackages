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

if(defined('ERROR_REPORTING') && (ERROR_REPORTING == true || ERROR_REPORTING == 1))
{
	error_reporting(-1);
	ini_set('display_errors', 1);
}
else
{
	error_reporting(0);
	ini_set('display_errors', 0);
}

if(SECURITY_KEY == '')
{
	throw new Exception('Error: SECURITY_KEY constants is empty');
}
elseif(strlen(SECURITY_KEY) < 20)
{
	throw new Exception('Error: SECURITY_KEY constants must be at least 20 characters');
}

require SYSTEM_PATH.'controllExistsPaths.php';

if(class_exists('SessionHandlerInterface'))
{
	require SYSTEM_PATH.'loadSessionHandler.php';
}

if(!class_exists('SessionHandlerInterface') || (!defined('USE_SESSION_SAVE_HANDLER') || USE_SESSION_SAVE_HANDLER == false || !defined('PDO_HOST') || PDO_HOST == ''))
{
	session_start();
}

if(defined('CHARSET') && CHARSET != '')
{
	header('Content-Type: text/html; charset='.CHARSET);
}

if(defined('TIMEZONE') && TIMEZONE != '')
{
	date_default_timezone_set(TIMEZONE);
}

$myPaths   = array();
$myPaths[] = PACKAGE_DIR;
$myPaths[] = SYSTEM_PATH;
$myPaths[] = IMPLEMENT_DIR;
$myPaths[] = CORE_DIR;
$myPaths[] = PLUGIN_DIR;
$myPaths[] = LIB_DIR;
$myPaths[] = LIB_DIR.'PHPMailer';
$myPaths[] = LIB_DIR.'minifiy';

if(defined('PAGE_DIR') && PAGE_DIR != '')
{
	$myPaths	=	array_merge($myPaths, backAllPaths(PAGE_DIR));
}

//Alle Dynamischen Klassen in include_path aufnehmen
if(defined('DYNAMIC_DIR') && DYNAMIC_DIR != '')
{
	$myPaths	=	array_merge($myPaths, backAllPaths(DYNAMIC_DIR));
}


ini_set('include_path', get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $myPaths));


//Alle Implements Klassen includieren
if(defined('IMPLEMENT_DIR') && IMPLEMENT_DIR != '')
{
	initializeDirectory(IMPLEMENT_DIR);
}

//Alle Exceptions Klassen includieren
if(defined('EXCEPTION_DIR') && EXCEPTION_DIR != '')
{
	initializeDirectory(EXCEPTION_DIR);
}

require 'initiator.abstract.class.php';
require 'autoload.class.php';

require 'version.class.php';
require 'benchmark.class.php';
require 'security.class.php';
require 'load_functions.abstract.class.php';

if(file_exists(ROOT.SEP.'dynamicInit.php'))
{
	require ROOT.SEP.'dynamicInit.php';
}