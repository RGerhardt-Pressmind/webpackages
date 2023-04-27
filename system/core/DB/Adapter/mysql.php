<?php
/**
 *  Copyright (C) 2010 - 2022  <Robbyn Gerhardt>
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
 * @package       webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2022
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\DB\Adapter;

use mysqli;
use system\core\DB\DBConnectionConfig;

class mysql implements AdapterInterface
{
	/**
	 * @var null|mysqli
	 */
	private ?mysqli $mysql			=	null;

	/**
	 * @var string|null
	 */
	private ?string $table_prefix	=	null;

	/**
	 * Get database engine
	 *
	 * @return string
	 */
	public function getEngine(): string
	{
		return 'mysql';
	}

	/**
	 * Connected to database
	 *
	 * @param DBConnectionConfig $config
	 * @return mixed
	 */
	public function connection(DBConnectionConfig $config): mixed
	{
		$this->mysql	=	new mysqli($config->host, $config->username, $config->password, $config->database, $config->port);

		$this->table_prefix	=	$config->table_prefix;

		if($this->mysql->connect_errno)
		{
			echo 'Failed to connect to MySQL: '.$this->mysql->connect_errno;
			exit;
		}

		return true;
	}

	/**
	 * Close database connection
	 */
	public function __destruct()
	{
		$this->mysql->close();
	}

	/**
	 * Execute mysql query
	 *
	 * @param mixed $query
	 *
	 * @return mixed
	 */
	public function execute(mixed $query): mixed
	{
		if(!is_null($this->mysql))
		{
			return $this->mysql->query($query);
		}

		return false;
	}

	/**
	 * Fetch all rows in mysql database
	 *
	 * @param string $table
	 * @param array|null $select
	 * @param null   $where
	 * @param null   $orderBy
	 * @param int $startIndex
	 * @param int $numItems
	 *
	 * @return mixed
	 */
	public function fetchAll(string $table, array $select = null, $where = null, $orderBy = null, int $startIndex = -1, int $numItems = -1): mixed
	{
		$sql	=	$this->buildSQLSelectQuery($table, $select, $where, $orderBy, $startIndex, $numItems);

		$query	=	$this->mysql->query($sql);
		$result	=	$query->fetch_all(MYSQLI_ASSOC);

		$query->free_result();

		return $result;
	}

	/**
	 * @param string $table
	 * @param array|null $select
	 * @param null   $where
	 * @param null   $orderBy
	 *
	 * @return mixed
	 */
	public function fetchRow(string $table, array $select = null, $where = null, $orderBy = null): mixed
	{
		$sql	=	$this->buildSQLSelectQuery($table, $select, $where, $orderBy, -1, -1);

		$query	=	$this->mysql->query($sql);
		$result	=	$query->fetch_assoc();

		$query->free_result();;

		return $result;
	}

	/**
	 * Build sql select query
	 *
	 * @param string $table
	 * @param array $select
	 * @param array $where
	 * @param array $orderBy
	 * @param int $startIndex
	 * @param int $numItems
	 *
	 * @return string
	 */
	private function buildSQLSelectQuery(string $table, array $select, array $where, array $orderBy, int $startIndex, int $numItems): string
	{
		$query	=	'SELECT ';

		if(!empty($select))
		{
			$selects	=	[];

			foreach($select as $value)
			{
				$selects[]	=	'`'.$value.'`';
			}

			$query	.=	implode(', ', $selects).' ';
		}
		else
		{
			$query	.=	'* ';
		}

		$query	.=	'FROM `'.$this->table_prefix.$table.'`';

		if(!empty($where))
		{
			$query	.=	' WHERE ';

			$wheres	=	[];

			foreach($where as $key => $value)
			{
				if(is_int($value))
				{
					$val	=	'= '.$value;
				}
				else if(is_float($value))
				{
					$val	=	'= '.$value;
				}
				else if(mb_strpos(mb_strtolower($value), 'in(') !== false)
				{
					$val	=	$value;
				}
				else
				{
					$val	=	'= "'.$this->mysql->real_escape_string($value).'"';
				}

				$wheres[]	=	'`'.$key.'` '.$val;
			}

			$query	.=	implode(' AND ', $wheres);
		}

		if(!empty($orderBy))
		{
			$query	.=	' ORDER BY ';

			$orderBys	=	[];

			foreach($orderBy as $key => $sort)
			{
				$orderBys[]	=	'`'.$key.'` '.$sort;
			}

			$query	.=	implode(', ', $orderBys);
		}

		if($startIndex >= 0 && $numItems >= 0)
		{
			$query	.=	' LIMIT '.$startIndex.', '.$numItems;
		}
		else if($startIndex >= 0)
		{
			$query	.=	' LIMIT 100000, '.$startIndex;
		}
		else if($numItems >= 0)
		{
			$query	.=	' LIMIT '.$numItems;
		}

		$query	.=	';';

		return $query;
	}

	/**
	 * Remove dataset in database
	 *
	 * @param string $table
	 * @param null   $where
	 * @param int $limit
	 *
	 * @return mixed
	 */
	public function delete(string $table, $where = null, int $limit = -1): mixed
	{
		$sql	=	'DELETE FROM `'.$this->table_prefix.$table.'`';

		return $this->extracted($where, $sql, $limit);
	}

	/**
	 * Update dataset in database
	 *
	 * @param string $table
	 * @param array $data
	 * @param null   $where
	 * @param int $limit
	 *
	 * @return bool
	 */
	public function update(string $table, array $data, $where = null, int $limit = -1): bool
	{
		$sql	=	'UPDATE `'.$this->table_prefix.$table.'` SET ';

		$inserts	=	[];

		foreach($data as $key => $value)
		{
			$inserts[]	=	'`'.$key.'` = "'.$this->mysql->real_escape_string($value).'"';
		}

		$sql	.=	implode(', ', $inserts);

		return $this->extracted($where, $sql, $limit);
	}

	/**
	 * Insert dataset in database
	 *
	 * @param string $table
	 * @param array $data
	 *
	 * @return bool|int
	 */
	public function insert(string $table, array $data): bool|int
	{
		$sql	=	'INSERT INTO `'.$this->table_prefix.$table.'` SET ';

		$inserts	=	[];

		foreach($data as $key => $value)
		{
			$inserts[]	=	'`'.$key.'` = "'.$this->mysql->real_escape_string($value).'"';
		}

		$sql	.=	implode(', ', $inserts);

		$query	=	$this->mysql->query($sql);

		return (($query !== false) ? (int)$this->mysql->insert_id : false);
	}

	/**
	 * Get table prefix
	 */
	public function getTablePrefix(): string
	{
		return $this->table_prefix;
	}

	/**
	 * Get connection
	 *
	 * @return mysqli|null
	 */
	public function getConnection(): ?mysqli
	{
		return $this->mysql;
	}

	/**
	 * @param array|null $where
	 * @param string     $sql
	 * @param int        $limit
	 *
	 * @return bool
	 */
	private function extracted(?array $where, string $sql, int $limit): bool
	{
		if(!empty($where))
		{
			$sql .= ' WHERE ';

			$wheres = [];

			foreach($where as $key => $value)
			{
				$wheres[] = '`'.$key.'` = "'.$this->mysql->real_escape_string($value).'"';
			}

			$sql .= implode(' AND ', $wheres);
		}

		if($limit > 0)
		{
			$sql .= ' LIMIT '.$limit;
		}

		$query = $this->mysql->query($sql);

		return ($query !== false);
	}
}
