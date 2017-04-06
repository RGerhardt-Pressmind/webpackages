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
 * @since         Version 2017.0
 * @filesource
 */

namespace package\system\core;

use package\core\plugins;
use package\system\valueObjects\plugins\VOApplyPlugin;

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

		// Kontrollieren ob das Plugin Modul geladen ist
		if(!empty(plugins::$definedHooks))
		{
			foreach(plugins::$definedHooks as $hook)
			{
				if($hook->call_position == VOApplyPlugin::BEFORE && ($hook->class == self::getClassName() || $hook->class == get_called_class()) && $hook->methode == $name)
				{
					$back	=	call_user_func_array($hook->call, $arguments);

					if($hook->replace_default_function && !empty($back))
					{
						return $back;
					}
				}
			}
		}

		$back	=	call_user_func_array(array(get_called_class(), '_'.$name), $arguments);

		if(!empty(plugins::$definedHooks))
		{
			foreach(plugins::$definedHooks as $hook)
			{
				if($hook->call_position == VOApplyPlugin::AFTER && ($hook->class == self::getClassName() || $hook->class == get_called_class()) && $hook->methode == $name)
				{
					$replace	=	call_user_func_array($hook->call, array($back));

					if($hook->replace_default_function && !empty($replace))
					{
						$back	=	$replace;
					}
				}
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

		$args	=	array('class' => self::getClassName(), 'method' => $name);

		if(!empty(plugins::$definedHooks))
		{
			foreach(plugins::$definedHooks as $hook)
			{
				if($hook->call_position == VOApplyPlugin::BEFORE && ($hook->class == self::getClassName() || $hook->class == get_called_class()) && $hook->methode == $name)
				{
					$back	=	call_user_func_array($hook->call, $args);

					if($hook->replace_default_function && !empty($back))
					{
						return $back;
					}
				}
			}
		}

		$back	=	call_user_func_array(array($this, '_'.$name), $arguments);

		if(!empty(plugins::$definedHooks))
		{
			$args['content']	=	$back;

			foreach(plugins::$definedHooks as $hook)
			{
				if($hook->call_position == VOApplyPlugin::AFTER && ($hook->class == self::getClassName() || $hook->class == get_called_class()) && $hook->methode == $name)
				{
					$replace	=	call_user_func_array($hook->call, $args);

					if($hook->replace_default_function && !empty($replace))
					{
						$back	=	$replace;
					}
				}
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