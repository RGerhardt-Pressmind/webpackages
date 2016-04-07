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
	$base_url = (is_https() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));
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
define('SEP', DIRECTORY_SEPARATOR);

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
define('USE_SESSION_SAVE_HANDLER', false);

/**
 * Wandelt Links (die über die URL Klasse generiert werden) in mod_rewrite Links um wenn aktiv
 */
define('USE_MOD_REWRITE', true);

/**
 * Standard Klasse beim Aufruf der Index
 */
define('DEFAULT_CLASS', 'welcome');

/**
 * Standard Methode beim Aufruf der Index
 */
define('DEFAULT_METHODE', 'hello');

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
 * Wenn ein Template Sprachdateien mitliefert, sollen diese vorrangig verwendet werden
 */
define('USE_TEMPLATE_LANGUAGE_PATH', true);

/**
 * Pfad zum Dynamischen Klassen Verzeichnis (wenn mehrere Frameworks installiert, kann man hier einen Default setzen)
 */
define('DYNAMIC_DIR', PACKAGE_DIR.'models'.SEP);

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
	if((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) != 'off'))
	{
		return true;
	}

	return false;
}

/**
 * MySQL Funktionen die bei der Security Klasse zum säubern von Inhalten genutzt wird und bei der Database Klasse um zu
 * erkennen was Funktionen sind.
 */
define('MYSQL_FUNCTIONS', '/(ABS|ACOS|ADDDATE|ADDTIME|AES_DECRYPT|AES_ENCRYPT|ANY_VALUE|ASCII|ASIN|ASYMMETRIC|ATAN|AVG|BENCHMARK|BETWEEN|BIN|BIT_AND|BIT_COUNT|BIT_LENGTH|BIT_OR|BIT_XOR|CAST|CEIL|CHAR|COALESCE|COERCIBILITY|COLLATION|COMPRESS|CONCAT|CONNECTION_ID|CONV|COS|COT|COUNT|CRC32|CREATE_|CURDATE|CURRENT_|CURTIME|DATABASE|DATE_|DATE|DAY|DECODE|DEFAULT|DEGREES|ELT|ENCODE|EXP|EXTRACT|FIELD|FIND_IN_SET|FLOOR|FORMAT|FOUND_|FROM_|GET_|GREATEST|GROUP_|GTID_|HEX|HOUR|IF|IN|IS_|ISNULL|JSON_|LAST_|LCASE|LEAST|LEFT|LENGTH|LIKE|LN|LOAD_|LOCAL|LOCATE|LOG|LOWER|LPAD|LTRIM|MAKE_|MAKEDATE|MAKETIME|MASTER_POS_WAIT|MATCH|MAX|MBR|MD5|MICROSECOND|MID|MIN|MLine|MOD|MONTH|MPointFrom|MPolyFrom|MultiLineString|MultiPoint|MultiPolygon|NAME_CONST|NOT BETWEEN|NOT IN|NOT LIKE|NOT REGEXP|NOT|NOW|NULLIF|OCT|OLD_PASSWORD|ORD|PERIOD_ADD|PERIOD_DIFF|PI|Point|Polygon|POSITION|POW|PROCEDURE ANALYSE|QUARTER|QUOTE|RADIANS|RAND|REGEXP|RELEASE_|REPEAT|REPLACE|REVERSE|RIGHT|RLIKE|ROUND|ROW_|RPAD|RPAD|RTRIM|SCHEMA|SEC_TO_TIME|SECOND|SESSION_USER|SHA|SIGN|SIN|SLEEP|SOUNDEX|SOUND_|SPACE|SQRT|ST_|STD|STDDEV|STR_TO_DATE|STRCMP|SUB|SUM|SYSDATE|SYSTEM_USER|TAN|TIME|TO_|TRIM|TRUNCATE|UCASE|UNCOMPRESS|UNHEX|UNIX|UpdateXML|UPPER|USER|UTC_|UUID|VALIDATE_PASSWORD_STRENGTH|VALUES|VAR_|VARIANCE|VERSION|WAIT_|WEEK|WEIGHT_STRING|XOR|YEAR)/');

function backAllPaths($dir)
{
	$myPaths   = array();
	$myPaths[] = $dir;

	$director = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator = new \RecursiveIteratorIterator($director, \RecursiveIteratorIterator::CHILD_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $item)
		{
			if($item->isDir())
			{
				$myPaths[] = $item->__toString();
			}
		}
	}

	return $myPaths;
}

function initializeDirectory($dir)
{
	$implements = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator   = new RecursiveIteratorIterator($implements, RecursiveIteratorIterator::CHILD_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);

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


#USER_CONTENT_BEGIN

#USER_CONTENT_END