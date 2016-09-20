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
 * @package       Webpackages
 * @subpackage    controllers
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\autoloadException;
use package\system\core\initiator;

/**
 * Zum dynamischen Aufrufen von Klassen
 *
 * Mittels dem autoload können klassen leicht initialisiert und definiert werden.
 *
 * @package        Webpackages
 * @subpackage     controllers
 * @category       Autoload
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class autoload extends initiator
{
	/**
	 * @var array Klassen die bereits einmal initialisiert wurden
	 */
	private static $cacheClasses = array();

	/**
	 * @const Der Klassensuffix unter den die Dateien abzuspeichern sind
	 */
	const CLASS_SUFFIX = '.class.php';

	/**
	 * Initialisiert eine Klasse
	 *
	 * @param string $class_name Der Klassenname der aufzurufenen Klasse
	 * @param string $namespace  Wenn die Klasse einen Namespace besitzt, dann diesen angeben
	 * @param bool   $isStatic   Wenn es sich um eine statische Klasse handelt
	 * @param object $parameter  Parameter für den Konstruktor der Klasse. Er muss ein Assoziatives Array sein
	 * @param bool   $inCache    Kann eine Klasse in den Cache legen, um sie bei erneuter Initialisierung aus dem Cache
	 *                           zu laden. Funktioniert nur bei nicht statischen Klassen.
	 *
	 * @throws autoloadException Wird ausgegeben wenn der Klassennamen leer ist oder die Klasse nicht geladen werden
	 *                           konnte
	 * @return mixed Gibt die Klasseninstanz zurück oder bei einer statischen Klasse ein true
	 */
	public static function get($class_name, $namespace = null, $isStatic = false, $parameter = null, $inCache = true)
	{
		if(empty($class_name))
		{
			throw new autoloadException('Error: $class_name is empty');
		}

		$pathToFile = $class_name.self::CLASS_SUFFIX;

		if(!empty($namespace))
		{
			$class_name = $namespace.$class_name;
		}

		//Wenn schon eingebunden, nicht erneut laden
		if(!class_exists($class_name))
		{
			require_once $pathToFile;
		}

		if(!class_exists($class_name))
		{
			throw new autoloadException('Error: class '.$class_name.' not found');
		}

		if(!$isStatic)
		{
			if($inCache)
			{
				if(!isset(self::$cacheClasses[$class_name]))
				{
					self::$cacheClasses[$class_name]	=	new $class_name($parameter);
				}

				return self::$cacheClasses[$class_name];
			}

			return new $class_name($parameter);
		}
		else
		{
			return true;
		}
	}
}