<?php
/*
    Copyright (C) 2015  <Robbyn Gerhardt>

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

    @category   autoload.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


class autoload
{
	/**
	 * @var array Auflistung aller bereits instanzierten Klassen
	 */
	private static $classes = 	array();

	/**
	 * @const Der Klassensuffix unter den die Dateien abzuspeichern sind
	 */
	const CLASS_SUFFIX 		= 	'.class.php';

    /**
     * Initialisiert eine Klasse
     *
     * @param string $class_name Der Klassenname der aufzurufenen Klasse
	 * @param string $namespace Wenn die Klasse einen Namespace besitzt, dann diesen angeben
     * @param bool $isStatic Wenn es sich um eine statische Klasse handelt
	 * @param array $parameter Parameter für den Konstruktor der Klasse. Er muss ein Assoziatives Array sein
	 *
	 * @throws \Exception
	 * @return mixed Gibt die Klasseninstanz zurück oder bei einer statischen Klasse ein true
     */
	public static function get($class_name, $namespace = null, $isStatic = false, $parameter = array())
    {
        if(empty($class_name))
        {
            throw new \Exception('class is not string or empty');
        }

        if(empty(self::$classes[$class_name]))
        {
            $pathToFile =  $class_name.self::CLASS_SUFFIX;

			//Wenn schon eingebunden, nicht erneut laden
			if(class_exists($class_name) === false)
			{
            	require_once $pathToFile;
			}

			if($isStatic === false)
			{
				if(!empty($namespace))
				{
					$class_name	=	$namespace.$class_name;

					if(class_exists($class_name) === false)
					{
						throw new \Exception('class '.$class_name.'('.$pathToFile.') not exists (NS)');
					}

					self::$classes[$class_name] = new $class_name($parameter);
				}
				else
				{
					if(class_exists($class_name) === false)
					{
						throw new \Exception('class '.$class_name.'('.$pathToFile.') not exists');
					}

					self::$classes[$class_name] = new $class_name($parameter);
				}
			}
			else
			{
				if(empty($namespace) === false)
				{
					$class_name	=	$namespace.$class_name;
				}

				if(class_exists($class_name) === false)
				{
					self::$classes[$class_name] = new $class_name();
				}
			}
        }

		if($isStatic === false)
		{
			return self::$classes[$class_name];
		}
		else
		{
			return true;
		}
    }
}