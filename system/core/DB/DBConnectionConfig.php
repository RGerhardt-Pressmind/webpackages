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

namespace system\core\DB;

class DBConnectionConfig
{
	public $host;
	public $username;
	public $password;
	public $database;
	public $port;
	public $table_prefix;
	public $engine;

	private static $_self	=	null;

	/**
	 * Create database connection config
	 *
	 * @param string $engine
	 * @param string $host
	 * @param string $username
	 * @param string $password
	 * @param string  $database
	 * @param int $port
	 * @param null $table_prefix
	 *
	 * @return DBConnectionConfig|null
	 */
	public static function create($engine, $host, $username, $password, $database, $port, $table_prefix = null)
	{
		if(self::$_self === null && !empty($host))
		{
			self::$_self				=	new self();
			self::$_self->engine		=	$engine;
			self::$_self->host			=	$host;
			self::$_self->username		=	$username;
			self::$_self->password		=	$password;
			self::$_self->database		=	$database;
			self::$_self->port			=	$port;
			self::$_self->table_prefix	=	$table_prefix;
		}

		return self::$_self;
	}
}
