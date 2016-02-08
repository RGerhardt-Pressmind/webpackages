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

if(isset($_SERVER['HTTP_HOST']))
{
	$base_url = (is_https() === true ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
}
else
{
	$base_url = 'http://localhost/';
}

/**
 * HTTP(s) Adresse des Webservers
 */
define('HTTP', $base_url);

/**
 * Systemunabhängiger Seperator
 */
define('SEP', DIRECTORY_SEPARATOR);#

/**
 * Betriebssystem kürzel
 */
define('OS', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'WIN' : 'UNIX');

/**
 * Das Hauptverzeichniss des Webservers
 */
define('ROOT', __DIR__);

/**
 * Standard Charset
 */
define('CHARSET', 'UTF-8');

/**
 * Aktiviert / Deaktiviert das Error Reporting von PHP
 */
define('ERROR_REPORTING', true);

/**
 * Die Standard Zeitzone, falls keine gesetzt
 */
define('TIMEZONE', 'Europe/Berlin');

/**
 * Der Standard Datenbanktreiber
 */
define('PDO_TYPE', 'mysql');

/**
 * Der Standard Datenbank Host
 */
define('PDO_HOST', '');

/**
 * Der Standard Datenbank Benutzername
 */
define('PDO_USERNAME', '');

/**
 * Das Standard Datenbank Passwort
 */
define('PDO_PASSWORD', '');

/**
 * Die Standard Datenbank
 */
define('PDO_DATABASE', '');

/**
 * Der Standard Datenbank Port
 */
define('PDO_PORT', 3306);

/**
 * Das Standard Datenbank Charset
 */
define('PDO_CHARSET', 'utf8');

/**
 * Die Dateinamen Endung der Cache Dateien
 */
define('CACHE_EXTENSION', '.cache');

/**
 * Der Sicherheitsschlüssel für alle Verschlüsselungen im System (muss vor erstbenutzung gesetzt werden)
 * (mindestens 20 Zeichen)
 */
define('SECURITY_KEY', 'pf349nf90sh(G/G§)(=F()"=(U=U§?F=§?J');

/**
 * Der Name des Template Headers (kein Pfad)
 */
define('TEMPLATE_HEADER', 'header.php');

/**
 * Der Name des Template Footers (kein Pfad)
 */
define('TEMPLATE_FOOTER', 'footer.php');

/**
 * Die Standard Sprache des Frameworks (dies muss als locale Variable auf dem Webserver zur Verfügung stehen)
 * siehe auch http://php.net/manual/de/function.setlocale.php oder http://php.net/manual/de/class.locale.php
 */
define('DEFAULT_LANGUAGE', 'de_DE.UTF-8');

/**
 * Entscheidet ob Sessions in die Datenbank geschrieben werden
 */
define('USE_SESSION_SAVE_HANDLER', true);

/**
 * Wandelt Links (die über die URL Klasse generiert werden) in mod_rewrite Links um wenn aktiv
 */
define('USE_MOD_REWRITE', true);

/**
 * ############################
 *
 * Pfade zum System
 *
 * ############################
 */

/**
 * Framework Standard Skin (im Ordner package/views)
 */
define('TEMPLATE_DEFAULT_SKIN', 'welcome');

/**
 * Der Pfad zum Package Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('PACKAGE_DIR', ROOT.SEP.'package'.SEP);

/**
 * Pfad zum System Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('SYSTEM_PATH', PACKAGE_DIR.'system'.SEP);

/**
 * Pfad zum Template Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('TEMPLATE_DIR', PACKAGE_DIR.'views'.SEP);

/**
 * Pfad zum Systemkern Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('CORE_DIR', SYSTEM_PATH.'core'.SEP);

/**
 * Pfad zum Exception Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('EXCEPTION_DIR', SYSTEM_PATH.'exceptions'.SEP);

/**
 * Pfad zum Cache Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('CACHE_PATH', SYSTEM_PATH.'cache'.SEP);

/**
 * Pfad zum Sprachdateien Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('LANGUAGE_PATH', SYSTEM_PATH.'languages'.SEP);

/**
 * Pfad zum Dynamischen Klassen Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('DYNAMIC_DIR', SYSTEM_PATH.'models'.SEP);

/**
 * Pfad zum Controler Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('PAGE_DIR', PACKAGE_DIR.'controllers'.SEP);

/**
 * Der Pfad zu dem Plugin Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('PLUGIN_DIR', SYSTEM_PATH.'plugins'.SEP);

/**
 * Der Pfad zum implement Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('IMPLEMENT_DIR', SYSTEM_PATH.'implement'.SEP);

/**
 * Der Pfad zum thirdParty Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('LIB_DIR', SYSTEM_PATH.'thirdParty'.SEP);

/**
 * Kontrolliert ob der Webserver HTTPS erlaubt
 *
 * @return bool Gibt bei verfügbaren Zertifikat true zurück ansonsten false
 */
function is_https()
{
	if((empty($_SERVER['HTTPS']) === false && strtolower($_SERVER['HTTPS']) !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) === true && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (empty($_SERVER['HTTP_FRONT_END_HTTPS']) === false && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'))
	{
		return true;
	}

	return false;
}