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
 * @subpackage    core
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\databaseException;

/**
 * Datenbankverbindung herstellen
 *
 * Mit der database Klasse können PDO Datenbankverbindungen hergestellt werden. Mit der aufgebauten Verbindung ist es
 * möglich Daten auszulesen, zu schreiben, zu aktualisieren oder zu löschen. Unterstützt werden mysql, cubrid, dblib,
 * firebird, informix, sqlsrv, oci, pgsql, sqlite und sqlite2
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       database
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class database extends \PDO
{
	/**
	 * @var array Eine Liste aller erlaubten PDO Treiber die verwendet werden dürfen. Erlaubt sind: mysql, cubrid,
	 *      dblib, firebird, informix, sqlsrv, oci, pgsql, sqlite und sqlite2
	 */
	public static $allowedDrivers = array('mysql', 'cubrid', 'dblib', 'firebird', 'informix', 'sqlsrv', 'oci', 'pgsql', 'sqlite', 'sqlite2');

	/**
	 * @var string Der aktuell benutzte PDO Treiber
	 */
	private $useDriver = '';

	/**
	 * @var string Die letzte Fehlermeldung die die database Klasse geworfen hat
	 */
	public $error_info = '';

	/**
	 * @var bool Kontrolliert ob die Datenbank initialisiert ist
	 */
	private $isInit            = false;

	private $databaseFunctions = '/(ABS|ACOS|ADDDATE|ADDTIME|AES_DECRYPT|AES_ENCRYPT|ANY_VALUE|ASCII|ASIN|ASYMMETRIC|ATAN|AVG|BENCHMARK|BETWEEN|BIN|BIT_AND|BIT_COUNT|BIT_LENGTH|BIT_OR|BIT_XOR|CAST|CEIL|CHAR|COALESCE|COERCIBILITY|COLLATION|COMPRESS|CONCAT|CONNECTION_ID|CONV|COS|COT|COUNT|CRC32|CREATE_|CURDATE|CURRENT_|CURTIME|DATABASE|DATE_|DATE|DAY|DECODE|DEFAULT|DEGREES|ELT|ENCODE|EXP|EXTRACT|FIELD|FIND_IN_SET|FLOOR|FORMAT|FOUND_|FROM_|GET_|GREATEST|GROUP_|GTID_|HEX|HOUR|IF|IN|IS_|ISNULL|JSON_|LAST_|LCASE|LEAST|LEFT|LENGTH|LIKE|LN|LOAD_|LOCAL|LOCATE|LOG|LOWER|LPAD|LTRIM|MAKE_|MAKEDATE|MAKETIME|MASTER_POS_WAIT|MATCH|MAX|MBR|MD5|MICROSECOND|MID|MIN|MLine|MOD|MONTH|MPointFrom|MPolyFrom|MultiLineString|MultiPoint|MultiPolygon|NAME_CONST|NOT BETWEEN|NOT IN|NOT LIKE|NOT REGEXP|NOT|NOW|NULLIF|OCT|OLD_PASSWORD|ORD|PERIOD_ADD|PERIOD_DIFF|PI|Point|Polygon|POSITION|POW|PROCEDURE ANALYSE|QUARTER|QUOTE|RADIANS|RAND|REGEXP|RELEASE_|REPEAT|REPLACE|REVERSE|RIGHT|RLIKE|ROUND|ROW_|RPAD|RPAD|RTRIM|SCHEMA|SEC_TO_TIME|SECOND|SESSION_USER|SHA|SIGN|SIN|SLEEP|SOUNDEX|SOUND_|SPACE|SQRT|ST_|STD|STDDEV|STR_TO_DATE|STRCMP|SUB|SUM|SYSDATE|SYSTEM_USER|TAN|TIME|TO_|TRIM|TRUNCATE|UCASE|UNCOMPRESS|UNHEX|UNIX|UpdateXML|UPPER|USER|UTC_|UUID|VALIDATE_PASSWORD_STRENGTH|VALUES|VAR_|VARIANCE|VERSION|WAIT_|WEEK|WEIGHT_STRING|XOR|YEAR)/';

	/**
	 * Am Konstruktor müssen dsn, username, password, options und driver übermittelt werden
	 *
	 * @param array $para
	 *
	 * @throws databaseException
	 */
	public function __construct($para)
	{
		$username = '';
		$passwd   = '';
		$options  = null;
		$driver   = 'mysql';

		$dsn = $para['driver'].':';

		if($para['driver'] === 'sqlite' || $para['driver'] === 'sqlite2')
		{
			$dsn .= $para['database'];
		}
		else
		{
			if($para['driver'] === 'sqlsrv')
			{
				$dsn .= 'Server='.$para['host'];
			}
			else
			{
				$dsn .= 'host='.$para['host'];
			}

			$addIn = true;

			if(empty($para['port']) === false)
			{
				if($addIn === true && $para['driver'] !== 'sqlsrv')
				{
					$dsn .= ';';
				}

				if($para['driver'] === 'informix')
				{
					$dsn .= 'service='.$para['port'];
				}
				elseif($para['driver'] === 'sqlsrv')
				{
					$dsn .= ','.$para['port'];
				}
				else
				{
					$dsn .= 'port='.$para['port'];
				}

				$addIn = true;
			}

			if(empty($para['database']) === false)
			{
				if($addIn === true)
				{
					$dsn .= ';';
				}

				$dsn .= 'dbname='.$para['database'];
				$addIn = true;
			}

			if(empty($para['charset']) === false)
			{
				if($addIn === true)
				{
					$dsn .= ';';
				}

				$dsn .= 'charset='.$para['charset'];
			}
		}

		if(empty($para['username']) === false)
		{
			$username = $para['username'];
		}

		if(empty($para['password']) === false)
		{
			$passwd = $para['password'];
		}

		if(empty($para['options']) === false)
		{
			$options = $para['options'];
		}

		if(empty($para['driver']) === false)
		{
			$driver = $para['driver'];
		}

		if(empty($dsn) === true || empty($username) === true)
		{
			return;
		}

		if(empty($options) === true)
		{
			$options = array();
		}

		if(in_array($driver, self::$allowedDrivers) === false)
		{
			throw new databaseException('Error: database driver '.$driver.' not allowed. Allowed: '.implode(',', self::$allowedDrivers));
		}

		$this->useDriver = $driver;

		if(isset($options[\PDO::ATTR_DEFAULT_FETCH_MODE]) === false)
		{
			$options[\PDO::ATTR_DEFAULT_FETCH_MODE] = \PDO::FETCH_ASSOC;
		}

		if(isset($options[\PDO::ATTR_ERRMODE]) === false)
		{
			$options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
		}

		if(isset($options[\PDO::ATTR_EMULATE_PREPARES]) === false)
		{
			$options[\PDO::ATTR_EMULATE_PREPARES] = 0;
		}

		if(isset($options[\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY]) === false)
		{
			$options[\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
		}

		if(isset($options[\PDO::ATTR_PERSISTENT]) === false)
		{
			$options[\PDO::ATTR_PERSISTENT] = true;
		}

		parent::__construct($dsn, $username, $passwd, $options);

		$this->isInit = true;
	}

	/**
	 * Führt den SQL Befehl aus und gibt ein gefundenes Resultat zurück
	 *
	 * @param string $sql Füht einen SQL Befehl aus und gibt ein assoziatives Array zurück. quefetch sollte verwendet
	 *                    werden, wenn nur ein Ergebnis erwartet wird.
	 *
	 * @throws databaseException Wenn $sql leer ist
	 * @return array Gibt das gefundene assoziative Array zurück
	 */
	public function quefetch($sql)
	{
		if($this->isInit === false)
		{
			return array();
		}

		if(is_string($sql) === false || empty($sql) === true)
		{
			throw new databaseException('Error: pdo::quefetch $sql is not a string or empty');
		}

		try
		{
			$stmt = $this->query($sql);
			$stmt = $stmt->fetchAll();
		}
		catch(\PDOException $e)
		{
			$this->error_info	=	$e->getMessage();
			return false;
		}

		if(is_array($stmt) === false || empty($stmt[0]) === true)
		{
			return array();
		}
		else
		{
			return $stmt[0];
		}
	}

	/**
	 * Führt einen SQL Befehle aus und gibt das assoziative Array zurück
	 *
	 * @param string $sql Gibt das Ergebniss einer SELECT Anweisung zurück. result_array sollte verwendet werden, wenn
	 *                    man mehr als ein Ergebnis erwartet
	 *
	 * @throws databaseException Wenn $sql leer ist oder $sql keine SELECT Anweisung enthält
	 * @return array Gibt ein assoziatives Array zurück
	 */
	public function result_array($sql)
	{
		if($this->isInit === false)
		{
			return array();
		}

		if(empty($sql) === true)
		{
			throw new databaseException('Error: pdo::result_array $sql is empty');
		}

		preg_match_all('/(SELECT|select)/', $sql, $matches);

		if(empty($matches) === true)
		{
			throw new databaseException('Error: pdo::result_array $sql is not select');
		}

		try
		{
			$stmt   = $this->query($sql);
			$return = $stmt->fetchAll();
		}
		catch(\PDOException $e)
		{
			$this->error_info	=	$e->getMessage();
			return false;
		}

		return $return;
	}

	/**
	 * Führt mehrere SQL Befehle, Semikolon getrennt voneinander, aus.
	 *
	 * @param string $sql Die Liste, durch Semikolon getrennte, SQL Befehle. Hier sollten nur update, insert oder
	 *                    delete Befehle übermittelt werden, da nur ein Boolean Wert zurück gegeben wird.
	 *
	 * @throws databaseException
	 * @return boolean Gibt bei Erfolg ein true zurück ansonsten ein Exception.
	 */
	public function multi_query($sql)
	{
		if($this->isInit === false)
		{
			return false;
		}

		if(is_string($sql) === false || empty($sql) === true)
		{
			throw new databaseException('Error: pdo::multi_query: $sql is not a string or empty');
		}

		try
		{
			$this->exec($sql);
		}
		catch(\PDOException $e)
		{
			$this->error_info	=	$e->getMessage();
			$this->rollBack();

			return false;
		}

		return true;
	}

	/**
	 * Gibt die aktuelle Datenbank Client Version zurück
	 *
	 * @return mixed Gibt die aktuelle Client Version zurück oder ein false
	 */
	public function version()
	{
		if($this->isInit === false)
		{
			return false;
		}

		return $this->getAttribute(\PDO::ATTR_CLIENT_VERSION);
	}

	/**
	 * Gibt die zuletzt hinzugefügt Primary Key ID zurück
	 *
	 * @param string $name Der Name der Sequenz aus der er die letzte ID zurück geben soll
	 *
	 * @return int Die zuletzt hinzugefügte ID
	 */
	public function insert_id($name = null)
	{
		if($this->isInit === false)
		{
			return 0;
		}

		if($this->useDriver === 'pgsql')
		{
			$v     = $this->version();
			$table = func_num_args() > 0 ? func_get_arg(0) : null;

			if($table === null && $v >= '8.1')
			{
				$sql = 'SELECT LASTVAL() as ins_id';
			}
			else
			{
				return 0;
			}

			$row = $this->quefetch($sql);

			return (int)$row['ins_id'];
		}
		else
		{
			return $this->lastInsertId($name);
		}
	}

	/**
	 * Füht einen SQL Befehl mittels "Prepared Statements" aus
	 *
	 * @param string $sql                 Der auszuführende SQL Befehl. Ersetzende Werte sind durch ein ? gesetzt
	 * @param array  $execute             Die Liste der zu ersetzenden Werte im SQL Befehl
	 * @param bool   $isResultAssociative Ist das Resultat ein assoziatives oder mehrdimensionales Array oder ein
	 *                                    Boolischer Wert
	 * @param bool   $getFirstResult      Soll nur der zuerst gefundenen Wert als assoziatives Array zurück gegebene
	 *                                    werden oder ein komplettes mehrdimensionales Array
	 *
	 * @throws databaseException Wenn $sql leer ist
	 * @return mixed Gibt, je nach Parameter, ein Array oder einen Boolischen Wert zurück
	 */
	public function secQuery($sql, $execute, $isResultAssociative = true, $getFirstResult = false)
	{
		if($this->isInit === false)
		{
			return false;
		}

		if(empty($sql) === true)
		{
			throw new databaseException('Error: pdo::secQuery: $sql is empty');
		}

		try
		{
			$stmt = $this->prepare($sql);

			if($isResultAssociative === true)
			{
				$stmt->execute($execute);

				$back = $stmt->fetchAll();

				if($getFirstResult === false)
				{
					return $back;
				}
				else
				{
					if(empty($back[0]) === false)
					{
						return $back[0];
					}
					else
					{
						return array();
					}
				}
			}
			else
			{
				return $stmt->execute($execute);
			}
		}
		catch(\PDOException $e)
		{
			$this->error_info	=	$e->getMessage();
			return false;
		}
	}

	/**
	 * Fügt einen Wert in eine Tabelle ein
	 *
	 * @param string $table        Der Tabellenname in den geschrieben werden soll
	 * @param array  $setParameter Ein Assoziatives Array das den key und das value beinhaltet
	 *
	 * @return int|false Gibt den erzeugten Primary Key zurück, oder ein false im fehlerfall
	 */
	public function insertTable($table, $setParameter)
	{
		$insert = '
		INSERT INTO
			`'.$table.'`
		SET
		';

		$execute = array();

		foreach($setParameter as $key => $value)
		{
			if(preg_match($this->databaseFunctions, $key) !== 0)
			{
				$insert .= '
				'.$key;
			}
			else
			{
				$insert .= '
				`'.$key.'`';
			}

			if(preg_match($this->databaseFunctions, $value) !== 0)
			{
				$insert .= '	=	'.$value.',';
			}
			else
			{
				$execute[] = $value;

				$insert .= '	=	?,';
			}
		}

		$insert = trim($insert, ',').';';

		$execInsert = $this->secQuery($insert, $execute, false, false);

		if($execInsert === false)
		{
			return false;
		}

		return $this->lastInsertId();
	}

	/**
	 * Aktualisiert einen oder mehrere Datensätze
	 *
	 * @param string $table          Der Tabellenname in den Datensätze aktualisiert werden sollen
	 * @param array  $setParameter   Enthält ein assoziatives Array mit key als Feldnamen und value als Wert
	 * @param array  $whereParameter Enthält ein assoziatives Array mit key als Feldnamen und value als Wert
	 * @param int    $limit          Die Anzahl zu aktualisierender Datensätze. Standartmäßig 0, heißt soviel wie man
	 *                               findet.
	 *
	 * @return bool
	 */
	public function updateTable($table, $setParameter, $whereParameter, $limit = 0)
	{
		$update = '
		UPDATE
			`'.$table.'`
		SET
		';

		$execute = array();

		foreach($setParameter as $key => $value)
		{
			if(preg_match($this->databaseFunctions, $key) !== 0)
			{
				$update .= '
				'.$key;
			}
			else
			{
				$update .= '
				`'.$key.'`
				';
			}

			if(preg_match($this->databaseFunctions, $value) !== 0)
			{
				$update .= '	=	'.$value.',';
			}
			else
			{
				$execute[] = $value;

				$update .= '	=	?,';
			}
		}

		$update = trim($update, ',');

		if(empty($whereParameter) === false)
		{
			$update .= '
			WHERE';

			foreach($whereParameter as $key => $value)
			{
				if(preg_match($this->databaseFunctions, $key) !== 0)
				{
					$update .= '
					'.$key;
				}
				else
				{
					$update .= '
					`'.$key.'`
					';
				}

				if(preg_match($this->databaseFunctions, $value) !== 0)
				{
					$update .= '	=	'.$value.' AND';
				}
				else
				{
					$execute[] = $value;

					$update .= '	=	? AND';
				}
			}

			$update = trim($update, 'AND');
		}

		if($limit > 0)
		{
			$update .= ' LIMIT '.$limit;
		}

		$update .= ';';

		$execUpdate = $this->secQuery($update, $execute, false, false);

		if($execUpdate === false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Löscht Einträge aus einer Tabelle
	 *
	 * @param string $table          Der Tabellenname
	 * @param array  $whereParameter Die Where Bedingungen. Welche Einträge sollen gelöscht werden.
	 * @param int    $limit          Limit ist standartmäßig auf 0, heißt alle die gefunden werden löschen.
	 *
	 * @return bool
	 */
	public function deleteTable($table, $whereParameter, $limit = 0)
	{
		$execute = array();

		$deleteTable = '
		DELETE FROM
			`'.$table.'`';

		if(empty($whereParameter) === false)
		{
			$deleteTable .= '
			WHERE
			';

			foreach($whereParameter as $key => $value)
			{
				if(preg_match($this->databaseFunctions, $key) !== 0)
				{
					$deleteTable .= '
					'.$key.'';
				}
				else
				{
					$deleteTable .= '
					`'.$key.'`';
				}

				if(preg_match($this->databaseFunctions, $value) !== 0)
				{
					$deleteTable .= ' =	'.$value.' AND';
				}
				else
				{
					$execute[] = $value;
					$deleteTable .= '	=	? AND';
				}
			}
		}

		$deleteTable = trim($deleteTable, 'AND');

		if($limit > 0)
		{
			$deleteTable .= '
			LIMIT '.$limit;
		}

		$deleteTable .= ';';

		$deleted = $this->secQuery($deleteTable, $execute, false, false);

		return $deleted;
	}
}