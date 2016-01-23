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

    @category   database.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


class database extends \PDO
{
	/**
	 * @var array Eine Liste aller erlaubten PDO Treiber die verwendet werden dürfen
	 */
	public static $allowedDrivers	=	array('mysql', 'cubrid', 'dblib', 'firebird', 'informix', 'sqlsrv', 'oci', 'pgsql', 'sqlite', 'sqlite2');

	/**
	 * @var string Der aktuell benutzte PDO Treiber
	 */
	private $useDriver	=	'', $isInit = false;


	public function __construct($para/*$dsn, $username, $passwd, $options, $driver = 'mysql'*/)
	{
		$dsn		=	$para['dsn'];
		$username	=	$para['username'];
		$passwd		=	$para['password'];
		$options	=	$para['options'];
		$driver		=	$para['driver'];

		if(empty($dsn) || empty($username) || empty($passwd))
		{
			return;
		}

		if(is_array($options) === false)
		{
			$options	=	array();
		}

		$this->useDriver	=	$driver;

		if(isset($options[\PDO::ATTR_DEFAULT_FETCH_MODE]) === false)
		{
			$options[\PDO::ATTR_DEFAULT_FETCH_MODE]	=	\PDO::FETCH_ASSOC;
		}

		if(isset($options[\PDO::ATTR_ERRMODE]) === false)
		{
			$options[\PDO::ATTR_ERRMODE]	=	\PDO::ERRMODE_EXCEPTION;
		}

		if(isset($options[\PDO::ATTR_EMULATE_PREPARES]) === false)
		{
			$options[\PDO::ATTR_EMULATE_PREPARES]	=	0;
		}

		if(isset($options[\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY]) === false)
		{
			$options[\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY]	=	true;
		}

		if(isset($options[\PDO::ATTR_PERSISTENT]) === false)
		{
			$options[\PDO::ATTR_PERSISTENT]	=	true;
		}

		parent::__construct($dsn, $username, $passwd, $options);

		$this->isInit	=	true;
	}


	/**
	 * Führt den SQL Befehl aus und gibt ein gefundenes Resultat zurück
	 *
	 * @param string $sql Die SQL Anweisung die ausgeführt werden soll. Am besten eine wo nur ein Resultat zurück kommt
	 *
	 * @throws \Exception
	 * @return array Gibt ein gefundenes Resultat zurück
	 */
	public function quefetch($sql)
	{
		if($this->isInit === false)
		{
			return array();
		}

		if(is_string($sql) === false || empty($sql))
		{
			throw new \Exception('pdo::quefetch: $sql ist kein string oder ist leer');
		}

		try{
			$stmt 	= 	$this->query($sql);
			$stmt	=	$stmt->fetchAll();
		}catch(\PDOException $e){
			return false;
		}

		if(is_array($stmt) === false || empty($stmt[0]))
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
	 * @param string $sql Der SQL Befehle. Hierbei sollte nur SELECT verwendet werden.
	 *
	 * @throws \Exception
	 * @return array Gibt ein assoziatives Array zurück
	 */
	public function result_array($sql)
	{
		if($this->isInit === false)
		{
			return array();
		}

		if(is_string($sql) === false || empty($sql))
		{
			throw new \Exception('pdo::result_array $sql ist kein string oder ist leer');
		}

		preg_match_all('/(SELECT|select)/', $sql, $matches);

		if(is_array($matches) === false || empty($matches))
		{
			throw new \Exception('pdo::result_array $sql ist keine SELECT Anweisung');
		}

		try{
			$stmt 	= 	$this->query($sql);
			$return	=	$stmt->fetchAll();
		}catch(\PDOException $e){
			return false;
		}

		return $return;
	}


	/**
	 * Führt mehrere SQL Befehle, Semikolon getrennt voneinander, aus.
	 *
	 * @param string $sql Die Liste, durch Semikolon getrennte, SQL Befehle.
	 *
	 * @throws \Exception
	 * @return boolean Gibt bei Erfolg ein true zurück ansonsten ein Exception
	 */
	public function multi_query($sql)
	{
		if($this->isInit === false)
		{
			return false;
		}

		if(is_string($sql) === false)
		{
			throw new \Exception('pdo::multi_query: $sql ist kein string');
		}

		try{
			$this->exec($sql);
		}catch(\PDOException $e){
			return false;
		}

		return true;
	}

	/**
	 * Gibt die aktuelle Client Version zurück
	 *
	 * @return mixed Gibt die aktuelle Client Version zurück
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
	 * @return int Die zuletzt hinzugefügte ID
	 */
	public function insert_id($name = null)
	{
		if($this->isInit === false)
		{
			return 0;
		}

		if($this->useDriver == 'pgsql')
		{
			$v 		=	$this->version();
			$table	= 	func_num_args() > 0 ? func_get_arg(0) : null;

			if($table == null && $v >= '8.1')
			{
				$sql = 'SELECT LASTVAL() as ins_id';
			}
			else
			{
				return 0;
			}

			$row	=	$this->quefetch($sql);
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
	 * @param string $sql Der auszuführende SQL Befehl. Ersetzende Werte sind durch ein ? gesetzt
	 * @param array $execute Die Liste der zu ersetzenden Werte im SQL Befehl
	 * @param bool $isResultAssociative Ist das Resultat ein assoziatives oder mehrdimensionales Array oder ein Boolischer Wert
	 * @param bool $getFirstResult Soll nur der zuerst gefundenen Wert als assoziatives Array zurück gegebene werden oder ein komplettes mehrdimensionales Array
	 *
	 * @throws \Exception
	 * @return mixed Gibt, je nach Parameter, ein Array oder einen Boolischen Wert zurück
	 */
	public function secQuery($sql, $execute, $isResultAssociative = true, $getFirstResult = false)
	{
		if($this->isInit === false)
		{
			return false;
		}

		if(is_string($sql) === false || empty($sql))
		{
			throw new \Exception('pdo::secQuery: $sql ist kein string');
		}

		try{
			$stmt	=	$this->prepare($sql);

			if($isResultAssociative === true)
			{
				$stmt->execute($execute);

				$back	=	$stmt->fetchAll();

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

		}catch(\PDOException $e){
			return false;
		}
	}
}