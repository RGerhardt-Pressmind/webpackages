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
 *  @package	Webpackages
 *  @subpackage core
 *  @author	    Robbyn Gerhardt
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace package\core;

/**
 * Validieren von PHP Typen
 *
 * Validiert bestimmte Typen von PHP Variablen. Dies dient zur Sicherheit von eingegebenen Werten Ihres Benutzers.
 *
 * @package		Webpackages
 * @subpackage	core
 * @category	security
 * @author		Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class Validater
{

	/**
	 * Kontrolliert ob der übergebene Wert ein String ist
	 *
	 * @param mixed $variable Der String der kontrolliert werden soll
	 * @return bool
	 */
	public static function isString($variable)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'Validater', 'isString', array($variable));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return is_string($variable);
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Integer ist
	 *
	 * @param mixed $variable Der Integer Wert der überprüft werden soll
	 * @return bool
	 */
	public static function isInteger($variable)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'Validater', 'isInteger', array($variable));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return (is_int($variable) === false ? (ctype_digit($variable)) : true);
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Boolean ist
	 *
	 * @param mixed $variable Der Boolean Wert der überprüft werden soll
	 * @return bool
	 */
	public static function isBoolean($variable)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'Validater', 'isBoolean', array($variable));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return is_bool($variable);
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Array ist
	 *
	 * @param mixed $variable Das Array das überprüft werden soll
	 * @param bool $controlIsAssociative Ob es sich dabei um ein assoziatives Array handelt (Standartmäßig: false)
	 * @return bool
	 */
	public static function isArray($variable, $controlIsAssociative = false)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'Validater', 'isArray', array($variable, $controlIsAssociative));

			if($plugin != null)
			{
				return $plugin;
			}
		}

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
	 * @param mixed $variable Das Float das überprüft werden soll
	 * @return bool
	 */
	public static function isFloat($variable)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'Validater', 'isFloat', array($variable));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$pattern = '/^[-+]?(((\\\\d+)\\\\.?(\\\\d+)?)|\\\\.\\\\d+)([eE]?[+-]?\\\\d+)?$/';

    	return (is_bool($variable) === false && (is_float($variable) === true || preg_match($pattern, trim($variable))));
	}


	/**
	 * Kontrolliert ob der übergebene Wert ein Objekt ist
	 *
	 * @param mixed $variable Das Objekt das überprüft werden soll.
	 * @return bool
	 */
	public static function isObject($variable)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'Validater', 'isObject', array($variable));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return is_object($variable);
	}
}