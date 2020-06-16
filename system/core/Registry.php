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

namespace system\core;

class Registry
{
	private $_class	=	[];

	private static $_instance	=	null;

	/**
	 * Get registry instance
	 *
	 * @return Registry|null
	 */
	public static function getInstance()
	{
		if(self::$_instance === null)
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
	public function add($name, $class)
	{
		$this->_class[$name]	=	$class;
	}

	/**
	 * Get class
	 *
	 * @param string $name
	 *
	 * @return bool|mixed
	 */
	public function get($name)
	{
		if(isset($this->_class[$name]))
		{
			return $this->_class[$name];
		}

		return false;
	}
}
