<?php
/*
    Copyright (C) 2016  <Robbyn Gerhardt>

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
    
    @category   Validater.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


class Validater
{

	/**
	 * Kontrolliert ob der übergebene Wert ein String ist
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	public static function isString($variable)
	{
		return is_string($variable);
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Integer ist
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	public static function isInteger($variable)
	{
		return (is_int($variable) === false ? (ctype_digit($variable)) : true);
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Boolean ist
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	public static function isBoolean($variable)
	{
		return is_bool($variable);
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Array ist
	 *
	 * @param mixed $variable
	 * @param bool $controlIsAssociative
	 * @return bool
	 */
	public static function isArray($variable, $controlIsAssociative = false)
	{
		if($controlIsAssociative === false)
		{
			return is_array($variable);
		}
		else
		{
			return is_array($variable) === true && array_diff_key($variable, array_keys(array_keys($variable)));
		}
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Float/Number ist
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	public static function isFloat($variable)
	{
		$pattern = '/^[-+]?(((\\\\d+)\\\\.?(\\\\d+)?)|\\\\.\\\\d+)([eE]?[+-]?\\\\d+)?$/';

    	return (is_bool($variable) === false && (is_float($variable) === true || preg_match($pattern, trim($variable))));
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Objekt ist
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	public static function isObject($variable)
	{
		return is_object($variable);
	}
}