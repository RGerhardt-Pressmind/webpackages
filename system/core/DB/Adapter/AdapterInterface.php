<?php
/**
 *  Copyright (C) 2010 - 2021  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2021
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
	function connection(DBConnectionConfig $config): mixed;

	/**
	 * Destruct class
	 */
	function __destruct();

	/**
	 * Get multiple rows from table in database
	 *
	 * @param string $table
	 * @param array|null $select
	 * @param null $where
	 * @param null $orderBy
	 * @param int $startIndex
	 * @param int $numItems
	 *
	 * @return mixed
	 */
	function fetchAll(string $table, array $select = null, $where = null, $orderBy = null, int $startIndex = -1, int $numItems = -1): mixed;

	/**
	 * Get single row from database
	 *
	 * @param string $table
	 * @param array|null $select
	 * @param null $where
	 * @param null $orderBy
	 *
	 * @return mixed
	 */
	function fetchRow(string $table, array $select = null, $where = null, $orderBy = null): mixed;

	/**
	 * Execute a query in database
	 *
	 * @param mixed $query
	 *
	 * @return mixed
	 */
	function execute(mixed $query): mixed;

	/**
	 * Insert dataset in database
	 *
	 * @param string $table
	 * @param array $data
	 *
	 * @return mixed
	 */
	function insert(string $table, array $data): mixed;

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
	function update(string $table, array $data, $where = null, int $limit = -1): mixed;

	/**
	 * Delete dataset from database
	 *
	 * @param string $table
	 * @param null $where
	 * @param int $limit
	 *
	 * @return mixed
	 */
	function delete(string $table, $where = null, int $limit = -1): mixed;

	/**
	 * Get table prefix
	 *
	 * @return string
	 */
	function getTablePrefix(): string;

	/**
	 * Get connection
	 *
	 * @return mixed
	 */
	function getConnection(): mixed;

	/**
	 * @return string
	 */
	function getEngine(): string;
}
