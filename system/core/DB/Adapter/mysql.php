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
 * @package       webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\DB\Adapter;

use system\core\DB\DBConnectionConfig;

class mysql implements AdapterInterface
{
	/**
	 * @var null|\mysqli
	 */
	private $mysql	=	null;

	private $table_prefix	=	null;

	/**
	 * Get database engine
	 *
	 * @return string
	 */
	public function getEngine()
	{
		return 'mysql';
	}

	/**
	 * @param DBConnectionConfig $config
	 *
	 * @return mixed|void
	 */
	public function connection(DBConnectionConfig $config)
	{
		$this->mysql	=	new \mysqli($config->host, $config->username, $config->password, $config->database, $config->port);

		$this->table_prefix	=	$config->table_prefix;

		if($this->mysql->connect_errno)
		{
			echo 'Failed to connect to MySQL: '.$this->mysql->connect_errno;
			exit;
		}
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
	 * @return mixed|void
	 */
	public function execute($query)
	{
		if(!is_null($this->mysql))
		{
			$this->mysql->query($query);
		}
	}

	/**
	 * Fetch all rows in mysql database
	 *
	 * @param string $table
	 * @param null   $select
	 * @param null   $where
	 * @param null   $orderBy
	 * @param int    $startIndex
	 * @param int    $numItems
	 *
	 * @return mixed
	 */
	public function fetchAll($table, $select = null, $where = null, $orderBy = null, $startIndex = -1, $numItems = -1)
	{
		$sql	=	$this->buildSQLSelectQuery($table, $select, $where, $orderBy, $startIndex, $numItems);

		$query	=	$this->mysql->query($sql);
		$result	=	$query->fetch_all(MYSQLI_ASSOC);

		$query->free_result();

		return $result;
	}

	/**
	 * @param string $table
	 * @param null   $select
	 * @param null   $where
	 * @param null   $orderBy
	 *
	 * @return array|mixed|null
	 */
	public function fetchRow($table, $select = null, $where = null, $orderBy = null)
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
	private function buildSQLSelectQuery($table, $select, $where, $orderBy, $startIndex, $numItems)
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
					$val	=	'= '.(int)$value;
				}
				else if(is_float($value))
				{
					$val	=	'= '.(float)$value;
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
			$query	.=	' LIMIT '.(int)$startIndex.', '.(int)$numItems;
		}
		else if($startIndex >= 0)
		{
			$query	.=	' LIMIT 100000, '.(int)$startIndex;
		}
		else if($numItems >= 0)
		{
			$query	.=	' LIMIT '.(int)$numItems;
		}

		$query	.=	';';

		return $query;
	}

	/**
	 * Remove dataset in database
	 *
	 * @param string $table
	 * @param null   $where
	 * @param int    $limit
	 *
	 * @return bool|mixed
	 */
	public function delete($table, $where = null, $limit = -1)
	{
		$sql	=	'DELETE FROM `'.$this->table_prefix.$table.'`';

		if(!empty($where))
		{
			$sql	.=	' WHERE ';

			$wheres	=	[];

			foreach($where as $key => $value)
			{
				$wheres[]	=	'`'.$key.'` = "'.$this->mysql->real_escape_string($value).'"';
			}

			$sql	.=	implode(' AND ', $wheres);
		}

		if($limit > 0)
		{
			$sql	.=	' LIMIT '.(int)$limit;
		}

		$query	=	$this->mysql->query($sql);

		return ($query !== false);
	}

	/**
	 * Update dataset in database
	 *
	 * @param string $table
	 * @param array  $data
	 * @param null   $where
	 * @param int    $limit
	 *
	 * @return bool|mixed
	 */
	public function update($table, $data, $where = null, $limit = -1)
	{
		$sql	=	'UPDATE `'.$this->table_prefix.$table.'` SET ';

		$inserts	=	[];

		foreach($data as $key => $value)
		{
			$inserts[]	=	'`'.$key.'` = "'.$this->mysql->real_escape_string($value).'"';
		}

		$sql	.=	implode(', ', $inserts);

		if(!empty($where))
		{
			$sql	.=	' WHERE ';

			$wheres	=	[];

			foreach($where as $key => $value)
			{
				$wheres[]	=	'`'.$key.'` = "'.$this->mysql->real_escape_string($value).'"';
			}

			$sql	.=	implode(' AND ', $wheres);
		}

		if($limit > 0)
		{
			$sql	.=	' LIMIT '.(int)$limit;
		}

		$query	=	$this->mysql->query($sql);

		return ($query !== false);
	}

	/**
	 * Insert dataset in database
	 *
	 * @param string $table
	 * @param array  $data
	 *
	 * @return bool|mixed
	 */
	public function insert($table, $data)
	{
		$sql	=	'INSERT INTO `'.$this->table_prefix.$table.'` SET ';

		$inserts	=	[];

		foreach($data as $key => $value)
		{
			$inserts[]	=	'`'.$key.'` = "'.$this->mysql->real_escape_string($value).'"';
		}

		$sql	.=	implode(', ', $inserts);

		$query	=	$this->mysql->query($sql);

		return ($query !== false);
	}

	/**
	 * Get table prefix
	 *
	 * @return string|null
	 */
	public function getTablePrefix()
	{
		return $this->table_prefix;
	}

	/**
	 * Get connection
	 *
	 * @return mixed|\mysqli|null
	 */
	public function getConnection()
	{
		return $this->mysql;
	}
}
