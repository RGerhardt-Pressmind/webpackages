<?php
/**
 *  Copyright (C) 2010 - 2020  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2020.0
 * @filesource
 */

use package\system\core\security;

header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: sameorigin");

ini_set('session.cookie_httponly', 1);

require 'constants.php';

if(defined('ERROR_REPORTING') && (ERROR_REPORTING || ERROR_REPORTING == 1))
{
	error_reporting(-1);
	ini_set('display_errors', 1);
}
else
{
	error_reporting(0);
	ini_set('display_errors', 0);
}

if(!defined('SECURITY_KEY'))
{
	throw new Exception('Error: constant SECURITY_KEY not exist');
}
elseif(strlen(SECURITY_KEY) < 20)
{
	throw new Exception('Error: SECURITY_KEY constants must be at least 20 characters');
}

require SYSTEM_PATH.'controllExistsPaths.php';

session_start();

if(defined('CHARSET') && CHARSET != '')
{
	header('Content-Type: text/html; charset='.CHARSET);
}

if(defined('TIMEZONE') && TIMEZONE != '')
{
	date_default_timezone_set(TIMEZONE);
}

$systemPath	=	array();

if(defined('PACKAGE_DIR') && PACKAGE_DIR != '')
{
	$systemPath   = getAllSubDirectorys(PACKAGE_DIR);
}

//Alle Value Objekt Klassen includiere
initializeDirectory(VALUE_OBJECTS);

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

spl_autoload_register('autoload_functions');

if(PDO_HOST != '' && PDO_USERNAME != '' && PDO_DATABASE != '')
{
	\package\system\core\autoload::$cacheClasses['db']	=	new \package\system\core\database(PDO_TYPE, PDO_HOST, PDO_USERNAME, PDO_PASSWORD, PDO_DATABASE, PDO_PORT, PDO_CHARSET, array(\PDO::ATTR_EMULATE_PREPARES => 1));
}

initializePlugins();

\package\system\core\plugins::callAction('wp_init');

if(defined('AUTO_SECURE') && AUTO_SECURE == true)
{
	security::autoSecurity(explode(',', AUTO_SECURE_EXCEPTIONS));
}

if(file_exists(ROOT.SEP.'dynamicInit.php'))
{
	require ROOT.SEP.'dynamicInit.php';
}

function autoload_functions($class)
{
	$class	=	str_replace('\\', SEP, $class);

	if(file_exists(ROOT.SEP.$class.'.php'))
	{
		require_once ROOT.SEP.$class.'.php';
	}
	else if(file_exists(ROOT.SEP.$class.'.class.php'))
	{
		require_once ROOT.SEP.$class.'.class.php';
	}
	else if(file_exists(ROOT.SEP.$class.'.master.class.php'))
	{
		require_once ROOT.SEP.$class.'.master.class.php';
	}
	else
	{
		#print_r(debug_backtrace());exit;
	}
}
