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
 * @since         Version 2018.0
 * @filesource
 */

namespace package\core;

use package\implement\IPlugin;

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
class plugins
{
	/**
	 * @var array Bereits definierte Plugins
	 */
	public static $definedPluginsClasses = array();

	/**
	 * @var array
	 */
	public static $definedHooks	=	array();

	/**
	 * @var array
	 */
	private static $definedActions	=	array();

	/**
	 * @var array
	 */
	private static $definedFilter	=	array();

	/**
	 * @var array
	 */
	public static $callDynamicInfos	=	array();


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
	 * Set filter
	 *
	 * @param string		$key
	 * @param string|array 	$call
	 * @param int 			$priority
	 *
	 * @return void
	 */
	public static function setFilter($key, $call, $priority = 10)
	{
		$hash		=	(is_string($call) ? $call : serialize($call));
		$uniqName	=	$priority.md5($key.$hash);

		self::$definedFilter[$key][$uniqName][]	=	array('key' => $key, 'call' => $call, 'priority' => $priority);
	}


	/**
	 * Defined action
	 *
	 * @param string $key
	 * @param string|array $call
	 * @param int $priority
	 *
	 * @return void
	 */
	public static function setAction($key, $call, $priority = 10)
	{
		$hash		=	(is_string($call) ? $call : serialize($call));
		$uniqName	=	$priority.md5($key.$hash);

		self::$definedActions[$key][$uniqName][]	=	array('key' => $key, 'call' => $call, 'priority' => $priority);
	}

	/**
	 * Call filter
	 *
	 * @param string $key
	 * @param mixed $args
	 *
	 * @return mixed
	 */
	public static function callFilter($key, $args = '')
	{
		if(!empty(self::$definedFilter[$key]))
		{
			foreach(self::$definedFilter[$key] as $values)
			{
				foreach($values as $value)
				{
					$args	=	call_user_func($value['call'], $args);
				}
			}
		}

		return $args;
	}


	/**
	 * Call action
	 *
	 * @param string $key
	 * @param mixed $args
	 *
	 * @return void
	 */
	public static function callAction($key, $args = '')
	{
		if(!empty(self::$definedActions[$key]))
		{
			$args	=	array_merge(self::$callDynamicInfos, array('args' => $args));

			foreach(self::$definedActions[$key] as $values)
			{
				foreach($values as $value)
				{
					call_user_func($value['call'], $args);
				}
			}
		}
	}


	/**
	 * Führt die Plugins vor dem Call der Funktion/Methode aus
	 *
	 * @deprecated
	 *
	 * @return mixed
	 */
	public static function hookBefore()
	{
		return null;
	}

	/**
	 * Führt die Plugins nach dem Call der Funktion/Methode aus
	 *
	 * @deprecated
	 *
	 * @return mixed
	 */
	public static function hookAfter()
	{
		return null;
	}

	/**
	 * Ruft alle Plugins auf, die mit dem hook_key verbunden sind
	 *
	 * @deprecated
	 * @return mixed
	 */
	public static function callHook()
	{
		return null;
	}



	/**
	 * Zum initialisieren von Daten
	 */
	public static function init()
	{
		if(!empty($_REQUEST['action']))
		{
			self::callAction('wp_ajax_'.$_REQUEST['action']);
		}
	}

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