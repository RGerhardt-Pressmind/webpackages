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
    
    @category   benchmark.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class benchmark implements IStatic
{
	private static $startTime, $endTime, $middleTime = array();

	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	/**
	 * Setzt den Startpunkt und somit die Anfangszeit
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Millisekunden
	 * @return void
	 */
	public static function startPoint($inSeconds = false)
	{
		self::$startTime	=	microtime($inSeconds);
	}


	/**
	 * Setzt den Endpunkt und somit die Endzeit
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Millisekunden
	 * @return void
	 */
	public static function endPoint($inSeconds = false)
	{
		self::$endTime	=	microtime($inSeconds);
	}


	/**
	 * Setzt einen Zeitpunkt, können auch mehrere sein
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Millisekunden
	 * @return void
	 */
	public static function middlePoint($inSeconds = false)
	{
		self::$middleTime[]	=	microtime($inSeconds);
	}


	/**
	 * Beendet die Zeitmessung und gibt die Differenz
	 * zwischen den beiden Zeiten zurück
	 *
	 * @return mixed Gibt die Differenz der gespeicherten Werte zurück.
	 * @throws \Exception
	 */
	public static function finish()
	{
		if(empty(self::$startTime) || empty(self::$endTime))
		{
			throw new \Exception('StartTime or EndTime is empty');
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