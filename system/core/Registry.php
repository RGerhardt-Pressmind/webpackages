<?php
/**
 *  Copyright (C) 2010 - 2023  <Robbyn Gerhardt>
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
 * @copyright     Copyright (C) 2010 - 2023
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core;

class Registry
{
	private array $_class	=	[];

	private static ?Registry $_instance	=	null;

	/**
	 * Get registry instance
	 *
	 * @return Registry
	 */
	public static function getInstance(): Registry
	{
		if(is_null(self::$_instance))
		{
			self::$_instance	=	new self();
		}

		return self::$_instance;
	}

	/**
	 * Add class
	 *
	 * @param string $name
	 * @param mixed $class
	 */
	public function add(string $name, mixed $class)
	{
		$this->_class[$name]	=	$class;
	}

	/**
	 * Exist class
	 *
	 * @param string $name
	 * @return bool
	 */
	public function exist(string $name): bool
	{
		return isset($this->_class[$name]);
	}

	/**
	 * Get class
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get(string $name): mixed
	{
		if(isset($this->_class[$name]))
		{
			return $this->_class[$name];
		}

		return false;
	}
}
