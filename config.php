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
 * @package       truetravel_bootstrap
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, pressmind GmbH (https://www.pressmind.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          https://www.pressmind.de
 * @since         Version 2.0.0
 * @filesource
 */

use system\Autoloader;
use system\core\Config;
use system\core\DB\database;
use system\core\DB\DBConnectionConfig;
use system\core\Language;
use system\core\Plugin;
use system\core\Registry;
use system\core\Security\security;
use system\core\Security\SecurityConfig;

header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("X-Frame-Options: sameorigin");

ini_set('session.cookie_httponly', 1);

header('Content-Type: text/html; charset=UTF-8');

session_start();

define('ROOT',	dirname(__FILE__).DIRECTORY_SEPARATOR);
define('ENV',	'development');

require_once ROOT.'system'.DIRECTORY_SEPARATOR.'Autoloader.php';

// Register autoloader
Autoloader::register();

// Load all plugins and register all hooks
Plugin::loadPluginRegister();

// Load config json
$config	=	Config::getConfig();
Registry::getInstance()->add('config', $config);

// Default timezone set
date_default_timezone_set($config['timezone']);

Plugin::hook('pre_init');

// Load security class
$securityConfig	=	SecurityConfig::create($config['security']['engine']);
Registry::getInstance()->add('security', security::create($securityConfig));

// Register language
$languageConfig	=	Config\LanguageConfig::create($config['language']['default'], $config['language']['path']);
Language::register($languageConfig);

if(!empty($config['database']['host']))
{
	// Build database connection
	$databaseConfig	=	DBConnectionConfig::create(
		$config['database']['engine'],
		$config['database']['host'],
		$config['database']['username'],
		$config['database']['password'],
		$config['database']['database'],
		$config['database']['port'],
		$config['database']['tablePrefix']
	);

	if($databaseConfig)
	{
		Registry::getInstance()->add('db', database::getDatabase($databaseConfig));
	}
}

Plugin::hook('init');
