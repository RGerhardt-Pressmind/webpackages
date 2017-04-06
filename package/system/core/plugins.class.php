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
 * @subpackage    controllers
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2017.0
 * @filesource
 */

namespace package\core;

use package\implement\IPlugin;
use package\implement\IStatic;
use package\system\valueObjects\plugins\VOApplyPlugin;

/**
 * Initialisiert Plugins
 *
 * Die Plugins Klasse initialisiert andere Klassen von externen Personen. Diese können dann Ihren Code an
 * anderen stellen des Frameworks einbinden.
 *
 * @package        Webpackages
 * @subpackage     controllers
 * @category       plugins
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class plugins implements IStatic
{
	/**
	 * @var array Bereits definierte Plugins
	 */
	public static $definedPluginsClasses = array();

	/**
	 * @var VOApplyPlugin[]
	 */
	public static $definedHooks	=	array();

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		if(!empty(self::$definedPluginsClasses))
		{
			foreach(self::$definedPluginsClasses as $k => $class)
			{
				$class                           = null;
				self::$definedPluginsClasses[$k] = null;

				unset($class);
				unset(self::$definedPluginsClasses[$k]);
			}

			self::$definedHooks	=	array();
		}
	}

	/**
	 * Führt die Plugins vor dem Call der Funktion/Methode aus
	 *
	 * @param $class
	 * @param $class_with_namespace
	 * @param $methode
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function hookBefore($class, $class_with_namespace, $methode, $arguments)
	{
		$collect	=	array();

		if(!empty(self::$definedHooks))
		{
			foreach(self::$definedHooks as $hook)
			{
				if($hook->call_position == 'BEFORE' && ($hook->class == $class || $hook->class == $class_with_namespace || $hook->all_dynamic_class) && ($hook->methode == $methode || $hook->all_dynamic_method))
				{
					$back	=	call_user_func_array($hook->call, $arguments);

					if($hook->replace_default_function)
					{
						if(!empty($back))
						{
							$collect[]	=	$back;
						}
					}
				}
			}
		}

		if(!empty($collect))
		{
			if(count($collect) == 1)
			{
				return $collect[0];
			}
			else
			{
				return $collect;
			}
		}

		return null;
	}

	/**
	 * Führt die Plugins nach dem Call der Funktion/Methode aus
	 *
	 * @param $class
	 * @param $class_with_namespace
	 * @param $methode
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function hookAfter($class, $class_with_namespace, $methode, $arguments)
	{
		$collect	=	array();

		if(!empty(self::$definedHooks))
		{
			foreach(self::$definedHooks as $hook)
			{
				if($hook->call_position == 'AFTER' && ($hook->class == $class || $hook->class == $class_with_namespace || $hook->all_dynamic_class) && ($hook->methode == $methode || $hook->all_dynamic_method))
				{
					echo 222;
					$back	=	call_user_func_array($hook->call, $arguments);

					if($hook->replace_default_function)
					{
						if(!empty($back))
						{
							$collect[]	=	$back;
						}
					}
				}
			}
		}

		if(!empty($collect))
		{
			if(count($collect) == 1)
			{
				return $collect[0];
			}
			else
			{
				return $collect;
			}
		}

		return null;
	}

	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	/**
	 * Gibt alle definierten Plugins zurück
	 *
	 * @return array Ein mehrdimensionales Array mit allen zur Verfügungn stehenden Plugins
	 */
	public static function getAllDefinedPlugins()
	{
		$back = array();

		if(!empty(self::$definedPluginsClasses))
		{
			foreach(self::$definedPluginsClasses as $class)
			{
				if($class instanceof IPlugin)
				{
					$back[] = get_class($class);
				}
			}
		}

		return $back;
	}
}