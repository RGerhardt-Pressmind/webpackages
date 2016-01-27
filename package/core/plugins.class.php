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

    @category   plugins.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package\core;

use package\implement\IPlugin;
use package\implement\IStatic;

class plugins implements IStatic
{
	public static $definedPluginsClasses	=	array();

	const BEFORE	=	'before';
	const AFTER		=	'after';

	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	/**
	 * Geht alle Plugins durch und kontrolliert
	 * ob eine Methode existiert die geöffnet
	 * werden soll
	 *
	 * Ein Hook arbeitet immer nach dem selben Prinzip
	 *
	 * $position
	 * 	before,after
	 *
	 * $classes
	 * 	Klassenname
	 *
	 * $pointer
	 * 	Methode
	 *
	 * $args
	 * 	Übergebene Parameter
	 *
	 * @param string $position Die Position im Skript. Kann nur Konstante BEFORE oder AFTER sein.
	 * @param string $classes Der Name der Klasse in dem das Plugin aufgerufen werden soll.
	 * @param string $methode Die Methode in der zuvor definierten Klasse in der das Plugin aufgerufen werden soll.
	 * @param array $args Ein assoziatives Array an Parameters das das Plugin bekommen soll. Standartmäßig "array()"
	 * @return void
	 */
	public static function hookShow($position, $classes, $methode, $args = array())
	{
		if(is_array(self::$definedPluginsClasses) === true && empty(self::$definedPluginsClasses) === false)
		{
			$pointer	=	$position.'_'.$classes.'_'.$methode.'_show';

			foreach(self::$definedPluginsClasses as $class)
			{
				if($class instanceof IPlugin && method_exists($class, $pointer) === true)
				{
					call_user_func_array(array($class, $pointer), $args);
				}
			}
		}
	}


	/**
	 * Öffnet einen Ankerpunkt und gibt das Resultat zurück und nimmt dies in die Verarbeitung auf
	 *
	 * @param string $position Die Position im Skript. Kann nur Konstante BEFORE oder AFTER sein.
	 * @param string $classes Der Name der Klasse in dem das Plugin aufgerufen werden soll.
	 * @param string $methode Die Methode in der zuvor definierten Klasse in der das Plugin aufgerufen werden soll.
	 * @param array $args Ein assoziatives Array an Parameters das das Plugin bekommen soll. Standartmäßig "array()"
	 *
	 * @return mixed Gibt das Resultat des Plugins zurück und gibt es aus.
	 */
	public static function hookCall($position, $classes, $methode, $args = array())
	{
		if(is_array(self::$definedPluginsClasses) === true && empty(self::$definedPluginsClasses) === false)
		{
			$pointer	=	$position.'_'.$classes.'_'.$methode.'_call';

			foreach(self::$definedPluginsClasses as $class)
			{
				if($class instanceof IPlugin && method_exists($class, $pointer) === true)
				{
					$plugin	=	call_user_func_array(array($class, $pointer), $args);

					if($plugin != null)
					{
						return $plugin;
					}
				}
			}
		}

		return null;
	}


	/**
	 * Definiert einen Anker im Template und liefert die Ausgabe.
	 *
	 * @param string $template Der Template Name bei dem es ausgegeben werden soll.
	 * @param string $position Die Position im Template.
	 * @param array $args Ein assoziatives Array an Parameters das das Plugin bekommen soll. Standartmäßig "array()"
	 *
	 * @return string Gibt ein String mit Inhalt vom Plugin zurück und gibt es aus.
	 */
	public static function hookTemplate($template, $position, $args = array())
	{
		if(is_array(self::$definedPluginsClasses) === true && empty(self::$definedPluginsClasses) === false)
		{
			$methode	=	$template.'_'.$position;

			foreach(self::$definedPluginsClasses as $class)
			{
				if($class instanceof IPlugin && method_exists($class, $methode) === true)
				{
					$plugin	=	call_user_func_array(array($class, $methode), $args);

					if($plugin != null)
					{
						echo $plugin;
					}
				}
			}
		}

		echo '';
	}


	/**
	 * Gibt alle definierten Plugins zurück
	 *
	 * @return array Ein mehrdimensionales Array mit allen zur Verfügungn stehenden Plugins
	 */
	public static function getAllDefinedPlugins()
	{
		$back	=	array();

		if(is_array(self::$definedPluginsClasses) === true && empty(self::$definedPluginsClasses) === false)
		{
			foreach(self::$definedPluginsClasses as $class)
			{
				if($class instanceof IPlugin)
				{
					$back[]	=	get_class($class);
				}
			}
		}

		return $back;
	}
}