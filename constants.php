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



if(isset($_SERVER['HTTP_HOST']))
{
	$base_url = (is_https() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
}
else
{
	$base_url = 'http://localhost/';
}

//HTTP(s) Adresse des Servers
define('HTTP', $base_url);

//Systemunabhängiger Seperator
define('SEP', DIRECTORY_SEPARATOR);#

//Betriebssystem
define('OS', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'WIN' : 'UNIX');

//Verzeichniss zum httpdocs Ordner der Webadresse
define('ROOT', __DIR__);

//Default Charset
define('CHARSET',	'UTF-8');

//Default Error-Reporting active / inactive
define('ERROR_REPORTING',	true);

//Default Timezone
define('TIMEZONE',	'Europe/Berlin');

//PDO Connection Type
define('PDO_TYPE',	'mysql');

//PDO Host (Server)
define('PDO_HOST',	'');

//PDO Connection Username
define('PDO_USERNAME',	'');

//PDO Connection Password
define('PDO_PASSWORD',	'');

//PDO Connection Database
define('PDO_DATABASE',	'');

//PDO Connection Port
define('PDO_PORT',	3306);

//PDO Charset
define('PDO_CHARSET', 'utf8');

//Cache - Datei Erweiterung
define('CACHE_EXTENSION',	'.cache');

//Sicherheitsschlüssel - einmalig - gegebenfalls zuvor ändern (!!! ACHTUNG !!! nach Verwendung nicht mehr verändern)
define('SECURITY_KEY',	'BLc>bjG.(#nXjAjtbX?d%&dEB\x$fW6,Sc(<^2$u');

//Template header default filename (only name not path)
define('TEMPLATE_HEADER',	'header.php');

//Template footer default filename (only name not path)
define('TEMPLATE_FOOTER',	'footer.php');

//Default langauge, Abhängig vom Server Paket
define('DEFAULT_LANGUAGE',	'de_DE.UTF-8');

//Use Session Save Handler
define('USE_SESSION_SAVE_HANDLER',	true);

//Mod Rewrite URL nutzen
define('USE_MOD_REWRITE',	true);


/*
 * Pfade im System
 */

//Template Pfad
define('TEMPLATE_DIR',			ROOT.SEP.'templates'.SEP);

//Template Default Skin
define('TEMPLATE_DEFAULT_SKIN',	'welcome');

//Update Pfad
define('UPDATE_DIR',			ROOT.SEP.'update'.SEP);

//Package Pfad
define('PACKAGE_DIR',			ROOT.SEP.'package'.SEP);

//Core Pfad
define('CORE_DIR',				PACKAGE_DIR.'core'.SEP);

//System Pfad
define('SYSTEM_PATH',			PACKAGE_DIR.'system'.SEP);

//Cache Pfad
define('CACHE_PATH',			SYSTEM_PATH.'cache'.SEP);

//Sprachdateien Pfad
define('LANGUAGE_PATH',			SYSTEM_PATH.'language'.SEP);

//Dynamische Klasse Pfad
define('DYNAMIC_DIR',			PACKAGE_DIR.'dynamic'.SEP);

//Pages Pfad - Klassen zur Verarbeitung
define('PAGE_DIR',				PACKAGE_DIR.'pages'.SEP);

//Plugin Pfad
define('PLUGIN_DIR',			PACKAGE_DIR.'plugins'.SEP);

//Implement Pfad
define('IMPLEMENT_DIR',			PACKAGE_DIR.'implement'.SEP);

//Libraries Pfad
define('LIB_DIR',				PACKAGE_DIR.'libs'.SEP);

#### Konstanten Funktionen ####

function is_https()
{
	if((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'))
	{
		return true;
	}

	return false;
}