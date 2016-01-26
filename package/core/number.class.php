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

    @category   number.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class number implements IStatic
{

	/**
	 * Zum initialisieren der Daten
	 */
	public static function init(){}

	/**
	 * Wandelt eine Zahl in ein Computer Byte-Format um
	 *
	 * @param int $num Der Numerische Wert der umgewandelt werden soll
	 * @param int $precision Wieviel stellen nach dem Komma soll der Wert zurück kommen. Standartmäßig "1"
	 * @return string Gibt den umgewandelten Wert zurück.
	 */
	public static function byte_format($num, $precision = 1)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'number', 'byteFormat', array($num, $precision));
			$plugins	=	plugins::hookCall('before', 'number', 'byteFormat', array($num, $precision));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if($num >= 1000000000000)
		{
			$num 	= 	round($num / 1099511627776, $precision);
			$unit 	=	'TB';
		}
		elseif($num >= 1000000000)
		{
			$num 	= 	round($num / 1073741824, $precision);
			$unit 	=	'GB';
		}
		elseif($num >= 1000000)
		{
			$num 	= 	round($num / 1048576, $precision);
			$unit 	= 	'MB';
		}
		elseif($num >= 1000)
		{
			$num 	= 	round($num / 1024, $precision);
			$unit 	= 	'KB';
		}
		else
		{
			$unit 	= 	'B';

			$back	=	number_format($num).' '.$unit;

			if(class_exists('\package\plugins') === true)
			{
				plugins::hookShow('after', 'number', 'byteFormat', array($back));
				$plugins	=	plugins::hookCall('after', 'number', 'byteFormat', array($back));

				if($plugins != null)
				{
					return $plugins;
				}
			}

			return $back;
		}

		$back	=	number_format($num, $precision).' '.$unit;

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'number', 'byteFormat', array($back));
			$plugins	=	plugins::hookCall('after', 'number', 'byteFormat', array($back));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $back;
	}


	/**
	 * Errechnet die Differenz aus zwei Timestamps
	 *
	 * @param int $start Startdatum im UNIX Timestamp Format
	 * @param bool|int $end Das Enddatum mit dem das Startdatum verglichen werden soll im UNIX Timestamp Format. Alternativ kann man auch den Boolischen Wert false setzen. Somit wird der aktuelle UNIX Timestamp genommen. Standartmäßig false.
	 *
	 * @return object Gibt ein Objekt mit den aktuellen Differenzen in Jahren, Monaten, Tagen, Stunden, Minuten und Sekunden zurück.
	 */
	public static function diff($start, $end = false)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'number', 'diff', array($start, $end));
			$plugins	=	plugins::hookCall('before', 'number', 'diff', array($start, $end));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(empty($end))
		{
			$end = time();
		}

		$start  	= 	date('Y-m-d H:i:s',$start);
		$end    	= 	date('Y-m-d H:i:s',$end);

		$d_start    = 	new \DateTime($start, new \DateTimeZone(TIMEZONE));
		$d_end      = 	new \DateTime($end, new \DateTimeZone(TIMEZONE));
		$diff 		= 	$d_start->diff($d_end);

		$back		=	new \stdClass();

		$back->year    	= 	$diff->format('%y');
		$back->month    = 	$diff->format('%m');
		$back->day      = 	$diff->format('%d');
		$back->hour     = 	$diff->format('%h');
		$back->min      = 	$diff->format('%i');
		$back->sec      = 	$diff->format('%s');

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'number', 'diff', array($back));
			$plugins	=	plugins::hookCall('after', 'number', 'diff', array($back));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $back;
	}


	/**
	 * Gibt, in Worten, die Different eines Datums aus
	 *
	 * @param object Ein Objekt das von der Methode "diff" kommt
	 * @param boolean Ob kurze Begrifflichkeiten genutzt werden sollen beim genauen Wortlaut. Standartmäßig false.
	 *
	 * @return string Gibt den Wortlaut der Differenz zurück.
	 * @throws \Exception
	 */
	public static function get_diff_value($diffDate, $short = false)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'number', 'getDiffValue', array($diffDate, $short));
			$plugins	=	plugins::hookCall('before', 'number', 'getDiffValue', array($diffDate, $short));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$return	=	'';

		if(isset($diffDate->year) === false)
		{
			throw new \Exception('year not defined');
		}
		elseif(isset($diffDate->month) === false)
		{
			throw new \Exception('month not defined');
		}
		elseif(isset($diffDate->day) === false)
		{
			throw new \Exception('day not defined');
		}
		elseif(isset($diffDate->hour) === false)
		{
			throw new \Exception('hour not defined');
		}
		elseif(isset($diffDate->min) === false)
		{
			throw new \Exception('minutes (min) not defined');
		}
		elseif(isset($diffDate->sec) === false)
		{
			throw new \Exception('secondy (sec) not defined');
		}

		if(class_exists('language') === false)
		{
			if(class_exists('\package\autoload') === true)
			{
				autoload::get('language', '\package\\', true);
			}
			else
			{
				require 'language.class.php';
			}

			language::init();
		}

		if($diffDate->year == 0)
		{
			if($diffDate->month == 0)
			{
				if($diffDate->day == 0)
				{
					if($diffDate->hour == 0)
					{
						if($diffDate->min == 0)
						{
							if($diffDate->sec == 0)
							{
								$return	=	language::translate('Jetzt');
							}
							else if($diffDate->sec == 1)
							{
								$return	=	$diffDate->sec.(($short === true) ? language::translate(' Sek.') : language::translate(' Sekunde'));
							}
							else
							{
								$return	=	$diffDate->sec.(($short === true) ? language::translate(' Sek.') : language::translate(' Sekunden'));
							}
						}
						else
						{
							if($short === true)
							{
								$return	=	$diffDate->min.' '.language::translate('Min.');
							}
							else
							{
								$return	=	$diffDate->min.' '.(($diffDate->min == 1) ? language::translate('Minute') : language::translate('Minuten'));
							}

							if($diffDate->sec > 0)
							{
								if($short === true)
								{
									$return	.=	' '.$diffDate->sec.' '.language::translate('Sek.');
								}
								else
								{
									$return	.=	' '.$diffDate->sec.' '.(($diffDate->sec == 1) ? language::translate('Sekunde') : language::translate('Sekunden'));
								}
							}
						}
					}
					else
					{
						if($short === true)
						{
							$return	=	$diffDate->hour.' '.language::translate('Std.').' '.$diffDate->min.' '.language::translate('Min.');
						}
						else
						{
							$return	=	$diffDate->hour.' '.(($diffDate->hour == 1) ? language::translate('Stunde') : language::translate('Stunden')).' '.$diffDate->min.' '.(($diffDate->min == 1) ? language::translate('Minute') : language::translate('Minuten'));
						}
					}
				}
				else
				{
					if($short === true)
					{
						$return	=	$diffDate->day.' '.(($diffDate->day == 1) ? language::translate('Tag') : language::translate('Tage')).' '.$diffDate->hour.' '.language::translate('Std.');
					}
					else
					{
						$return	=	$diffDate->day.' '.(($diffDate->day == 1) ? language::translate('Tag') : language::translate('Tage')).' '.$diffDate->hour.' '.(($diffDate->hour == 1) ? language::translate('Stunde') : language::translate('Stunden'));
					}
				}
			}
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'number', 'getDiffValue', array($return));
			$plugins	=	plugins::hookCall('after', 'number', 'getDiffValue', array($return));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $return;
	}
} 