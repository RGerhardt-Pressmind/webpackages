<?php
/**
 *  Copyright (C) 2010 - 2017  <Robbyn Gerhardt>
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
 * @package       Webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\system\core;

use package\core\plugins;

/**
 * Initiator aller Hilfsklassen
 *
 * Der Initiator standartisiert Hilfsklassen und bindet in jede Methode einen Plugin Hook ein
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       initiator
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
abstract class initiator
{
	private static $reflectionClass;

	public function __construct()
	{
		self::$reflectionClass	=	new \ReflectionClass(get_called_class());
	}

	/**
	 * Ruft eine statische Methode innerhalb der Klasse auf
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public static function __callStatic($name, $arguments)
	{
		if(!method_exists(get_called_class(), '_'.$name))
		{
			throw new \Exception('Error '.get_called_class().': static methode '.$name.' not exists');
		}

		if(class_exists('\package\core\plugins'))
		{
			$plugin = plugins::hooks(plugins::BEFORE, self::getClassName(), $name, $arguments);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$back	=	call_user_func_array(array(get_called_class(), '_'.$name), $arguments);

		if(class_exists('\package\core\plugins'))
		{
			$plugin = plugins::hooks(plugins::AFTER, self::getClassName(), $name, $back);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $back;
	}

	/**
	 * Ruft eine Methode aus innerhalb der Klasse auf
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __call($name, $arguments)
	{
		if(!method_exists(get_called_class(), '_'.$name))
		{
			throw new \Exception('Error '.get_called_class().': static methode '.$name.' not exists');
		}

		if(class_exists('\package\core\plugins'))
		{
			$plugin = plugins::hooks(plugins::BEFORE, self::getClassName(), $name, $arguments);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$back	=	call_user_func_array(array($this, '_'.$name), $arguments);

		if(class_exists('\package\core\plugins'))
		{
			$plugin = plugins::hooks(plugins::AFTER, self::getClassName(), $name, $back);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $back;
	}

	/**
	 * Gibt den Klassennamen, ohne namespace, zurück
	 *
	 * @return string
	 */
	public static function getClassName()
	{
		if(self::$reflectionClass)
		{
			return self::$reflectionClass->getShortName();
		}
		else
		{
			return null;
		}
	}

	/**
	 * Gibt die Konstante der Klasse zurück
	 *
	 * @param string $constant
	 *
	 * @return mixed
	 */
	public static function getConstant($constant = '')
	{
		if(self::$reflectionClass)
		{
			return self::$reflectionClass->getConstant($constant);
		}
		else
		{
			return null;
		}
	}
}