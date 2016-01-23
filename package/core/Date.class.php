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
    
    @category   Date.class.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class Date implements IStatic
{
	const STATE_BW = 'Baden-Württemberg';
    const STATE_BY = 'Bayern';
    const STATE_BE = 'Berlin';
    const STATE_BB = 'Brandenburg';
    const STATE_HB = 'Freie Hansestadt Bremen';
    const STATE_HH = 'Hamburg';
    const STATE_HE = 'Hessen';
    const STATE_MV = 'Mecklenburg-Vorpommern';
    const STATE_NI = 'Niedersachsen';
    const STATE_NW = 'Nordrhein-Westfalen';
    const STATE_RP = 'Reinland-Pfalz';
    const STATE_SL = 'Saarland';
    const STATE_SN = 'Sachsen';
    const STATE_ST = 'Sachsen-Anhalt';
    const STATE_SH = 'Schleswig-Holstein';
    const STATE_TH = 'Thüringen';

	const NATION_GERMANY	=	'Germany';
	const NATION_AUSTRIAN	=	'Austrian';
	const NATION_DENMARK	=	'Denmark';
	const NATION_FRENCH		=	'French';
	const NATION_ITALIAN	=	'Italian';
	const NATION_NORWEGIAN	=	'Norwegian';
	const NATION_POLISH		=	'Polish';
	const NATION_SWEDISH	=	'Swedish';


	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	/**
	 * Gibt den aktuellen Zeitstempel der Zeitzone zurück
	 *
	 * @param string $timezone
	 * @return int
	 */
	public static function now($timezone)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'now', array($timezone));
			$plugins	=	plugins::hookCall('before', 'Date', 'now', array($timezone));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(empty($timezone))
		{
			$timezone	=	TIMEZONE;
		}

		if($timezone == 'local' || $timezone === date_default_timezone_get())
		{
			return time();
		}

		$datetime	=	new \DateTime('now', new \DateTimeZone($timezone));

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'Date', 'now', array($datetime));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $datetime->getTimestamp();
	}


	/**
	 * Gibt den Zeitstempel eines Datums zurück
	 * unter Berücksichtigung der Zeitzone.
	 *
	 * @param string $date
	 * @return int
	 */
	public static function get_timestamp_by_date($date)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_timestamp_by_date', array($date));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_timestamp_by_date', array($date));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$datetime	=	new \DateTime($date, new \DateTimeZone(TIMEZONE));

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'Date', 'get_timestamp_by_date', array($datetime));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $datetime->getTimestamp();
	}


	/**
	 * Gibt das Datum eines Zeitstempels unter
	 * Berücksichtigung der Zeitzone zurück.
	 *
	 * @param int $timestamp
	 * @param string $format
	 *
	 * @return string
	 */
	public static function get_date_by_timestamp($timestamp, $format = 'Y-m-d')
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_date_by_timestamp', array($timestamp, $format));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_date_by_timestamp', array($timestamp, $format));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$datetime	=	new \DateTime();
		$datetime->setTimestamp($timestamp);
		$datetime->setTimezone(new \DateTimeZone(TIMEZONE));

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'Date', 'get_date_by_timestamp', array($datetime, $format));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $datetime->format($format);
	}


	/**
	 * Gibt das Datum von Ostern eines bestimmten Jahres zurück
	 *
	 * @param int $year
	 * @param bool $inTimestamp
	 *
	 * @return string|int
	 */
	public static function get_easter_day_by_year($year, $inTimestamp = false)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_easter_day_by_year', array($year, $inTimestamp));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_easter_day_by_year', array($year, $inTimestamp));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$base	=	new \DateTime($year.'-03-21', new \DateTimeZone(TIMEZONE));
		$day	=	easter_days($year);

		$base->add(new \DateInterval('P'.$day.'D'));

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'Date', 'get_easter_day_by_year', array($base, $inTimestamp));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if($inTimestamp)
		{
			return $base->getTimestamp();
		}
		else
		{
			return $base->format('Y-m-d');
		}
	}


	/**
	 * Gibt alle Feiertage in einem JAhr zurück
	 *
	 * @param int $year
	 * @return array
	 */
	private static function get_all_holidays($year)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_all_holidays', array($year));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_all_holidays', array($year));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$base	=	new \DateTime($year.'-03-21', new \DateTimeZone(TIMEZONE));
		$base->modify('+'.easter_days($year).' days');

		$gruendonnerstag	=	clone $base;
		$gruendonnerstag->modify('-3 days');

		$karfreitag			=	clone $base;
		$karfreitag->modify('-2 days');

		$ostersamstag		=	clone $base;
		$ostersamstag->modify('-1 days');

		$ostersonntag		=	$base;

		$ostermontag		=	clone $base;
		$ostermontag->modify('+1 days');

		$chirstiHimmelfahrt	=	clone $base;
		$chirstiHimmelfahrt->modify('+39 days');

		$pfingstsamstag		=	clone $base;
		$pfingstsamstag->modify('+48 days');

		$pfingstsonntag		=	clone $base;
		$pfingstsonntag->modify('+49 days');

		$pfingstmontag		=	clone $base;
		$pfingstmontag->modify('+50 days');

		$fronleichname		=	clone $base;
		$fronleichname->modify('+60 days');

		$back	=	array(
			'maundyThursday'	=>	$gruendonnerstag,
			'goodFriday'		=>	$karfreitag,
			'easterSaturday'	=>	$ostersamstag,
			'easterSunday'		=>	$ostersonntag,
			'easterMonday'		=>	$ostermontag,
			'ascensionDay'		=>	$chirstiHimmelfahrt,
			'pentecostSaturday'	=>	$pfingstsamstag,
			'pentecostSunday'	=>	$pfingstsonntag,
			'pentecostMonday'	=>	$pfingstmontag,
			'corpusChristi'		=>	$fronleichname
		);

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'Date', 'get_all_holidays', array($year, $back));

			if($plugins != null)
			{
				return $plugins;
			}
		}


		return $back;
	}


	/**
	 * Gibt die Feiertage einer Nation in einem Jahr zurück
	 *
	 * @param int $year
	 * @param string $nation
	 * @return array
	 */
	public static function get_nation_holidays_by_year($year, $nation = self::NATION_GERMANY)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_nation_holidays_by_year', array($year, $nation));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_nation_holidays_by_year', array($year, $nation));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$holidays		=	array();
		$allHolidays	=	self::get_all_holidays($year);

		if($nation == self::NATION_GERMANY)
		{
			$gruendonnerstag	=	$allHolidays['maundyThursday']->format('Y-m-d');
			$karfreitag			=	$allHolidays['goodFriday']->format('Y-m-d');
			$ostersonntag		=	$allHolidays['easterSunday']->format('Y-m-d');
			$ostermontag		=	$allHolidays['easterMonday']->format('Y-m-d');
			$chirstiHimmelfahrt	=	$allHolidays['ascensionDay']->format('Y-m-d');
			$pfingstsamstag		=	$allHolidays['pentecostSaturday']->format('Y-m-d');
			$pfingstsonntag		=	$allHolidays['pentecostSunday']->format('Y-m-d');
			$pfingstmontag		=	$allHolidays['pentecostMonday']->format('Y-m-d');
			$fronleichname		=	$allHolidays['corpusChristi']->format('Y-m-d');


			$holidays	=	array(
				$year.'-01-01'	=>	array(
					'name'			=>	'Neujahr'
				),
				$year.'-01-05'	=>	array(
					'name'			=>	'Tag der Arbeit'
				),
				$year.'-01-06'	=>	array(
					'name'			=>	'Heilige Drei Könige',
					'federal_state'	=>	array(
						self::STATE_BW,
                		self::STATE_BY,
                		self::STATE_ST
					)
				),
				$gruendonnerstag	=>	array(
					'name'			=>	'Gründonnerstag'
				),
				$karfreitag			=>	array(
					'name'			=>	'Karfreitag'
				),
				$ostersonntag		=>	array(
					'name'			=>	'Ostersonntag'
				),
				$ostermontag		=>	array(
					'name'			=>	'Ostermontag'
				),
				$chirstiHimmelfahrt	=>	array(
					'name'			=>	'Christi Himmelfahrt'
				),
				$pfingstsamstag		=>	array(
					'name'			=>	'Pfingstsamstag'
				),
				$pfingstsonntag		=>	array(
					'name'			=>	'Pfingstsonntag'
				),
				$pfingstmontag		=>	array(
					'name'			=>	'Pfingstmontag'
				),
				$fronleichname		=>	array(
					'name'			=>	'Fronleichnam',
					'federal_state'	=>	array(
						self::STATE_BW,
						self::STATE_BY,
						self::STATE_HE,
						self::STATE_NW,
						self::STATE_RP,
						self::STATE_SL
					)
				),
				$year.'-10-31'	=>	array(
					'name'			=>	'Reformationstag',
					'federal_state'	=>	array(
						self::STATE_BB,
						self::STATE_MV,
						self::STATE_SN,
						self::STATE_ST,
						self::STATE_TH
					)
				),
				$year.'-11-01'	=>	array(
					'name'			=>	'Allerheiligen',
					'federal_state'	=>	array(
						self::STATE_BW,
						self::STATE_BY,
						self::STATE_NW,
						self::STATE_RP,
						self::STATE_SL
					)
				),
				$year.'-12-25'	=>	array(
					'name'			=>	'1. Weihnachtstag'
				),
				$year.'-12-26'	=>	array(
					'name'			=>	'2. Weihnachtstag'
				),
			);
		}
		elseif($nation == self::NATION_AUSTRIAN)
		{
			$holidays = array(
				$year.'-01-01' => array(
					'name'	=>	'Neujahr'
				),
				$year.'-01-06' => array(
					'name'	=>	'Heilige Drei Könige'
				),
				$year.'-11-01' => array(
					'name'	=>	'Allerheiligen'
				),
				$year.'-08-15' => array(
					'name'	=>	'Mariä Himmelfahrt'
				),
				$year.'-05-01' => array(
					'name'	=>	'Staatsfeiertag'
				),
				$year.'-10-26' => array(
					'name'	=>	'Nationalfeiertag'
				),
				$year.'-12-08' => array(
					'name'	=>	'Mariä Empfängnis'
				),
				$year.'-12-25' => array(
					'name'	=>	'Weihnachten'
				),
				$year.'-12-26' => array(
					'name'	=>	'Stefanitag'
				),

				$allHolidays['easterSunday']->format('Y-m-d')    => array(
					'name'	=>	'Ostersonntag'
				),
				$allHolidays['easterMonday']->format('Y-m-d')    => array(
					'name'	=>	'Ostermontag'
				),
				$allHolidays['ascensionDay']->format('Y-m-d')    => array(
					'name'	=>	'Christi Himmelfahrt'
				),
				$allHolidays['pentecostMonday']->format('Y-m-d') => array(
					'name'	=>	'Pfingstmontag'
				),
				$allHolidays['corpusChristi']->format('Y-m-d')   => array(
					'name'	=>	'Fronleichnam'
				),
			);
		}
		elseif($nation == self::NATION_DENMARK)
		{
			$greatPrayerDay	=	clone $allHolidays['easterSunday'];
			$greatPrayerDay->modify('+26 days');

			$holidays = array(
				$year.'-01-01' => array(
					'name'		=>	'Nytår'
				),
				$year.'-12-25' => array(
					'name'		=>	'1. Juledag'
				),
				$year.'-12-26' => array(
					'name'		=>	'2. Juledag'
				),
				$allHolidays['maundyThursday']->format('Y-m-d')  	=> 	array(
					'name'		=>	'Skærtorsdag'
				),
				$allHolidays['goodFriday']->format('Y-m-d')      	=> 	array(
					'name'		=>	'Langfredag'
				),
				$allHolidays['easterSunday']->format('Y-m-d')    	=> 	array(
					'name'		=>	'Påskedag'
				),
				$allHolidays['easterMonday']->format('Y-m-d')    	=> 	array(
					'name'		=>	'2. Påskedag'
				),
				$greatPrayerDay->format('Y-m-d')            		=> 	array(
					'name'		=>	'Store Bededag'
				),
				$allHolidays['ascensionDay']->format('Y-m-d')    	=> 	array(
					'name'		=>	'Kristi Himmelfartsdag'
				),
				$allHolidays['pentecostSunday']->format('Y-m-d') 	=> 	array(
					'name'		=>	'Pinsedag'
				),
				$allHolidays['pentecostMonday']->format('Y-m-d') 	=> 	array(
					'name'		=>	'2. Pinsedag'
				)
			);
		}
		elseif($nation == self::NATION_FRENCH)
		{
			$holidays = array(
				$year.'-01-01'	=>	array(
					'name'		=>	'Jour de l\'an'
				),
				$year.'-05-01' 	=> 	array(
					'name'		=>	'Fête du Travail'
				),
				$year.'-05-08' 	=> 	array(
					'name'		=>	'8 Mai 1945'
				),
				$year.'-07-14' 	=> 	array(
					'name'		=>	'Fête Nationale'
				),
				$year.'-08-15' 	=> 	array(
					'name'		=>	'Assomption'
				),
				$year.'-11-01' 	=> 	array(
					'name'		=>	'La Toussaint'
				),
				$year.'-11-11' 	=> 	array(
					'name'		=>	'Armistice'
				),
				$year.'-12-25' 	=> 	array(
					'name'		=>	'Noël'
				),
				$allHolidays['easterMonday']->format('Y-m-d')    	=>	array(
					'name'		=>	'Lundi de Pâques'
				),
				$allHolidays['ascensionDay']->format('Y-m-d')    	=> 	array(
					'name'		=>	'Jeudi de l\'Ascension'
				),
				$allHolidays['pentecostMonday']->format('Y-m-d')	=> 	array(
					'name'		=>	'Lundi de Pentecôte'
				)
			);
		}
		elseif($nation == self::NATION_ITALIAN)
		{
			$holidays = array(
				$year.'-01-01'	=> 	array(
					'name'		=>	'Capodanno'
				),
				$year.'-01-06'	=> 	array(
					'name'		=>	'Epifania'
				),
				$year.'-04-25'	=> 	array(
					'name'		=>	'Liberazione dal nazifascismo (1945)'
				),
				$year.'-05-01'	=> 	array(
					'name'		=>	'Festa del lavoro'
				),
				$year.'-06-02'	=> 	array(
					'name'		=>	'Festa della Repubblica'
				),
				$year.'-08-15'	=> 	array(
					'name'		=>	'Assunzione di Maria'
				),
				$year.'-11-01'	=> 	array(
					'name'		=>	'Ognissanti'
				),
				$year.'-12-08'	=> 	array(
					'name'		=>	'Immacolata Concezione'
				),
				$year.'-12-25'	=> 	array(
					'name'		=>	'Natale di Gesù'
				),
				$year.'-12-26'	=>	array(
					'name'		=>	'Santo Stefano'
				),
				$allHolidays['easterSunday']->format('Y-m-d')	=>	array(
					'name'		=>	'Pasqua'
				),
				$allHolidays['easterMonday']->format('Y-m-d')	=>	array(
					'name'		=>	'Lunedì di Pasqua'
				)
			);
		}
		elseif($nation == self::NATION_NORWEGIAN)
		{
			$holidays	=	array(
				$year.'-01-01'	=>	array(
					'name' => '1. nyttårsdag'
				),
				$year.'-05-01'	=>	array(
					'name' => '1. mai'
				),
				$year.'-05-17'	=>	array(
					'name' => 'Grunnlovsdagen'
				),
				$year.'-12-25'	=>	array(
					'name' => '1. juledag'
				),
				$year.'-12-26'	=>	array(
					'name' => '2. juledag'
				),
				$allHolidays['maundyThursday']->format('Y-m-d')  => array(
					'name' => 'Skjærtorsdag'
				),
				$allHolidays['goodFriday']->format('Y-m-d')      => array(
					'name' => 'Langfredag'
				),
				$allHolidays['easterSunday']->format('Y-m-d')    => array(
					'name' => '1. påskedag'
				),
				$allHolidays['easterMonday']->format('Y-m-d')    => array(
					'name' => '2. påskedag'
				),
				$allHolidays['ascensionDay']->format('Y-m-d')    => array(
					'name' => 'Kristi Himmelfartsdag'
				),
				$allHolidays['pentecostMonday']->format('Y-m-d') => array(
					'name' => '2. pinsedag'
				)
			);
		}
		elseif($nation == self::NATION_POLISH)
		{
			$holidays	=	array(
				$year.'-01-01'	=>	array(
					'name' => 'Nowy Rok'
				),
				$year.'-01-06'	=>	array(
					'name' => 'Trzech Króli'
				),
				$year.'-05-01'	=>	array(
					'name' => 'Święto Pracy'
				),
				$year.'-05-03'	=>	array(
					'name' => 'Święto Konstytucji Trzeciego Maja'
				),
				$year.'-08-15'	=>	array(
					'name' => 'Wniebowzięcie Najświętszej Maryi Panny'
				),
				$year.'-11-01'	=>	array(
					'name' => 'Wszystkich Świętych'
				),
				$year.'-11-11'	=>	array(
					'name' => 'Święto Niepodległości'
				),
				$year.'-12-25'	=>	array(
					'name' => 'Boże Narodzenie'
				),
				$year.'-12-26'	=>	array(
					'name' => 'Drugi dzień Bożego Narodzenia'
				),
				$allHolidays['easterSunday']->format('Y-m-d')	=>	array(
					'name' => 'Wielkanoc'
				),
				$allHolidays['easterMonday']->format('Y-m-d')	=>	array(
					'name' => 'Poniedziałek Wielkanocny'
				),
				$allHolidays['corpusChristi']->format('Y-m-d')	=>	array(
					'name' => 'Boże Ciało'
				)
			);
		}
		elseif($nation == self::NATION_SWEDISH)
		{
			$midSummerDay = self::get_mid_summer_day($year);
        	$allSaintsDay = self::getAllSaintsDay($year);

			$holidays	=	array(
				$year.'-01-01'	=>	array(
					'name' => 'Nyårsdagen'
				),
				$year.'-01-05'	=>	array(
					'name' => 'Trettondagsafton', 
					'halfday' => true
				),
				$year.'-01-06'	=>	array(
					'name' => 'Trettondedag jul'
				),
				$year.'-04-30'	=>	array(
					'name' => 'Valborgsmässoafton', 
					'halfday' => true
				),
				$year.'-05-01'	=>	array(
					'name' => 'Första maj'
				),
				$year.'-06-06'	=>	array(
					'name' => 'Sveriges nationaldag'
				),
				$year.'-12-24'	=>	array(
					'name' => 'Julafton'
				),
				$year.'-12-25'	=>	array(
					'name' => 'Juldagen'
				),
				$year.'-12-26'	=>	array(
					'name' => 'Annandag jul'
				),
				$year.'-12-31'	=>	array(
					'name' => 'Nyårsafton'
				),
				$allHolidays['maundyThursday']->format('Y-m-d')		=> array(
					'name' => 'Skärtorsdagen',
					'halfday' => true
				),
				$allHolidays['goodFriday']->format('Y-m-d') 		=> array(
					'name' => 'Långfredagen'
				),
				$allHolidays['easterSaturday']->format('Y-m-d') 	=> array(
					'name' => 'Påskafton'
				),
				$allHolidays['easterSunday']->format('Y-m-d') 		=> array(
					'name' => 'Påskdagen'
				),
				$allHolidays['easterMonday']->format('Y-m-d') 		=> array(
					'name' => 'Annandag påsk'
				),
				$allHolidays['ascensionDay']->format('Y-m-d') 		=> array(
					'name' => 'Kristi himmelsfärdsdag'
				),
				$allHolidays['pentecostSaturday']->format('Y-m-d') 	=> array(
					'name' => 'Pingstafton'
				),
				$allHolidays['pentecostSunday']->format('Y-m-d') 	=> array(
					'name' => 'Pingstdagen'
				),
				$midSummerDay->format('Y-m-d') 						=> array(
					'name' => 'Midsommardagen'
				),
				$midSummerDay->modify('-1 day')->format('Y-m-d') 	=> array(
					'name' => 'Midsommarafton'
				),
				$allSaintsDay->format('Y-m-d') 						=> array(
					'name' => 'Alla helgons dag'
				),
				$allSaintsDay->modify('-1 day')->format('Y-m-d') 	=> array(
					'name' => 'Allhelgonaafton',
					'halfday' => true
				)
			);
		}

		ksort($holidays);

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'Date', 'get_nation_holidays_by_year', array($year, $nation, $holidays));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $holidays;
	}

	/**
	 * Gibt den Allerheiligen in Schweden zurück
	 *
	 * @param $year
	 * @return \DateTime
	 */
	public static function get_all_saints_day($year)
    {
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_all_saints_day', array($year));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_all_saints_day', array($year));

			if($plugins != null)
			{
				return $plugins;
			}
		}

        $date = new \DateTime($year.'-10-31');

        for($i = -1; ++$i < 7;)
		{
            if($date->format('w') == 6)
			{
                break;
            }

            $date->add(new \DateInterval('P1D'));
        }

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'Date', 'get_all_saints_day', array($year, $date));

			if($plugins != null)
			{
				return $plugins;
			}
		}

        return $date;
    }


	/**
	 * Gibt das Mittsommer Datum zurück
	 *
	 * @param $year
	 * @return \DateTime
	 */
	public static function get_mid_summer_day($year)
    {
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_mid_summer_day', array($year));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_mid_summer_day', array($year));

			if($plugins != null)
			{
				return $plugins;
			}
		}

        $date = new \DateTime($year.'-06-20');

        for($i = -1; ++$i < 7;)
		{
            if($date->format('w') == 6)
			{
                break;
            }

            $date->add(new \DateInterval('P1D'));
        }

		if(class_exists('\package\plugins') === true)
		{
			$plugins	=	plugins::hookCall('after', 'Date', 'get_mid_summer_day', array($year, $date));

			if($plugins != null)
			{
				return $plugins;
			}
		}

        return $date;
    }


	/**
	 * Ist ein bestimmtes Jahr ein Schaltjahr
	 *
	 * @param int $year
	 * @return bool
	 */
	public static function is_year_leap_year($year)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'is_year_leap_year', array($year));
			$plugins	=	plugins::hookCall('before', 'Date', 'is_year_leap_year', array($year));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(($year % 400) == 0 || (($year % 4) == 0 && ($year % 100) != 0))
		{
		   return true;
		}
		else
		{
		   return false;
		}
	}


	/**
	 * Gibt die Anzahl der Tage eines Monats zurück
	 *
	 * @param int $month
	 * @param int $year
	 * @param int $particular_calendar CAL_GREGORIAN | CAL_JULIAN | CAL_JEWISH | CAL_FRENCH | CAL_NUM_CALS
	 *
	 * @return int
	 */
	public static function get_days_in_month($month, $year = 0, $particular_calendar = CAL_GREGORIAN)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'Date', 'get_days_in_month', array($month, $year, $particular_calendar));
			$plugins	=	plugins::hookCall('before', 'Date', 'get_days_in_month', array($month, $year, $particular_calendar));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if($month < 1 || $month > 12)
		{
			return 0;
		}

		if(empty($year))
		{
			$year	=	date('Y');
		}

		return cal_days_in_month($particular_calendar, $month, $year);
	}
}