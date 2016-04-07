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
 * @subpackage    core
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\benchmarkException;
use package\implement\IStatic;
use package\system\core\initiator;

/**
 * Zum Messen von Laufzeiten
 *
 * Die Benchmark Klasse liest im Mikrosekunden Bereich wie schnell bestimmte Skripte brauchen um Ihre Aufgabe zu beenden
 *
 * @method static void start_point($inSeconds = false)
 * @method static void end_point($inSeconds = false)
 * @method static void middle_point($inSeconds = false)
 * @method static mixed finish()
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       Benchmark
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class benchmark extends initiator implements IStatic
{
	/**
	 * Destructor
	 */
	public function __destruct()
	{
		self::$startTime  = null;
		self::$endTime    = null;
		self::$middleTime = null;
	}

	/**
	 * @var array Definiert die Start/End/Mittel Zeiten des Benchmark Ergebnisses
	 */
	private static $startTime, $endTime, $middleTime = array();

	/**
	 * Zum initialisieren von Daten
	 * Wird beim Aufruf der Klasse in der load_functions.abstract.class.php benutzt
	 */
	public static function init()
	{
	}

	/**
	 * Setzt den Startpunkt und somit die Anfangszeit
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Mikrosekunden
	 *
	 * @return void
	 */
	protected static function _start_point($inSeconds = false)
	{
		self::$startTime = microtime($inSeconds);
	}

	/**
	 * Setzt den Endpunkt und somit die Endzeit
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Mikrosekunden
	 *
	 * @return void
	 */
	protected static function _end_point($inSeconds = false)
	{
		self::$endTime = microtime($inSeconds);
	}

	/**
	 * Setzt einen Zeitpunkt, können auch mehrere sein
	 *
	 * @param bool $inSeconds Soll der Wert in Sekunden abgespeichert werden oder in Mikrosekunden
	 *
	 * @return void
	 */
	protected static function _middle_point($inSeconds = false)
	{
		self::$middleTime[] = microtime($inSeconds);
	}

	/**
	 * Beendet die Zeitmessung und gibt die Differenz
	 * zwischen den beiden Zeiten zurück
	 *
	 * @return mixed Gibt die Differenz der gespeicherten Werte zurück.
	 * @throws benchmarkException
	 */
	protected static function _finish()
	{
		if(empty(self::$startTime))
		{
			throw new benchmarkException('Error: StartTime is empty');
		}

		if(empty(self::$endTime))
		{
			self::end_point(true);
		}

		$diff = (self::$endTime - self::$startTime);

		if(!empty(self::$middleTime))
		{
			$diff = array(
				'startTime' => self::$startTime,
				'endTime' => self::$endTime,
				'middleTime' => self::$middleTime,
				'diff' => $diff
			);
		}

		self::$startTime  = '';
		self::$endTime    = '';
		self::$middleTime = array();

		return $diff;
	}
}