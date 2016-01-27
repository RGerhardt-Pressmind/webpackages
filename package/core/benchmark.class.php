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
*/

namespace package\core;


use package\implement\IStatic;

class benchmark implements IStatic
{
	/**
	 * @var array Definiert die Start/End/Mittel Zeiten des Benchmark Ergebnisses
	 */
	private static $startTime, $endTime, $middleTime = array();

	/**
	 * Zum initialisieren von Daten
	 * Wird beim Aufruf der Klasse in der load_functions.abstract.class.php benutzt
	 */
	public static function init(){}

	/**
	 * Setzt den Startpunkt und somit die Anfangszeit
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Millisekunden
	 *
	 * @author Robbyn Gerhardt <gerhardt@webpackages.de>
	 * @copyright 2010-2016 webpackages
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @version 1.0
	 *
	 * @return void
	 */
	public static function start_point($inSeconds = false)
	{
		self::$startTime	=	microtime($inSeconds);
	}


	/**
	 * Setzt den Endpunkt und somit die Endzeit
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Millisekunden
	 *
	 * @author Robbyn Gerhardt <gerhardt@webpackages.de>
	 * @copyright 2010-2016 webpackages
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @version 1.0
	 *
	 * @return void
	 */
	public static function end_point($inSeconds = false)
	{
		self::$endTime	=	microtime($inSeconds);
	}


	/**
	 * Setzt einen Zeitpunkt, können auch mehrere sein
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Millisekunden
	 *
	 * @author Robbyn Gerhardt <gerhardt@webpackages.de>
	 * @copyright 2010-2016 webpackages
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @version 1.0
	 *
	 * @return void
	 */
	public static function middle_point($inSeconds = false)
	{
		self::$middleTime[]	=	microtime($inSeconds);
	}


	/**
	 * Beendet die Zeitmessung und gibt die Differenz
	 * zwischen den beiden Zeiten zurück
	 *
	 * @return mixed Gibt die Differenz der gespeicherten Werte zurück.
	 *
	 * @author Robbyn Gerhardt <gerhardt@webpackages.de>
	 * @copyright 2010-2016 webpackages
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @version 1.0
	 *
	 * @throws \Exception
	 */
	public static function finish()
	{
		if(empty(self::$startTime))
		{
			throw new \Exception('StartTime empty');
		}
		else if(empty(self::$endTime))
		{
			self::end_point(true);
		}

		$diff	=	self::$endTime - self::$startTime;

		if(is_array(self::$middleTime) === true && !empty(self::$middleTime))
		{
			$diff	=	array('startTime' => self::$startTime, 'endTime' => self::$endTime, 'middleTime' => self::$middleTime, 'diff' => $diff);
		}

		self::$startTime	=	'';
		self::$endTime		=	'';
		self::$middleTime	=	array();

		return $diff;
	}
}