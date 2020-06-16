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

interface AdapterInterface
{
	/**
	 * Create database connection
	 *
	 * @param DBConnectionConfig $config
	 *
	 * @return mixed
	 */
	function connection(DBConnectionConfig $config);

	/**
	 * Destruct class
	 */
	function __destruct();

	/**
	 * Get multiple rows from table in database
	 *
	 * @param string $table
	 * @param array $select
	 * @param null $where
	 * @param null $orderBy
	 * @param int $startIndex
	 * @param int $numItems
	 *
	 * @return mixed
	 */
	function fetchAll($table, $select = null, $where = null, $orderBy = null, $startIndex = -1, $numItems = -1);

	/**
	 * Get single row from database
	 *
	 * @param string $table
	 * @param array $select
	 * @param null $where
	 * @param null $orderBy
	 *
	 * @return mixed
	 */
	function fetchRow($table, $select = null, $where = null, $orderBy = null);

	/**
	 * Execute a query in database
	 *
	 * @param mixed $query
	 *
	 * @return mixed
	 */
	function execute($query);

	/**
	 * Insert dataset in database
	 *
	 * @param string $table
	 * @param array $data
	 *
	 * @return mixed
	 */
	function insert($table, $data);

	/**
	 * Update dataset in database
	 *
	 * @param string $table
	 * @param array $data
	 * @param null $where
	 * @param int $limit
	 *
	 * @return mixed
	 */
	function update($table, $data, $where = null, $limit = -1);

	/**
	 * Delete dataset from database
	 *
	 * @param string $table
	 * @param null $where
	 * @param int $limit
	 *
	 * @return mixed
	 */
	function delete($table, $where = null, $limit = -1);

	/**
	 * Get table prefix
	 *
	 * @return string
	 */
	function getTablePrefix();

	/**
	 * Get connection
	 *
	 * @return mixed
	 */
	function getConnection();

	/**
	 * @return string
	 */
	function getEngine();
}
