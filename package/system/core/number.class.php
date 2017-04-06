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

use package\exceptions\numberException;
use package\implement\IStatic;
use package\system\core\initiator;

/**
 * Konvertieren von Zahlen
 *
 * Konvertiert bestimmte Zahlen und gibt diese richtig zurück
 *
 * @method static array scale_proportionally(float $sourceWidth, float $sourceHeight, $destWidth = 0.00, $destHeight = 0.00)
 * @method static string byte_format(float $num, $precision = 1)
 * @method static object diff(int $start, $end = false)
 * @method static string get_diff_value(object $diffDate, $short = false)
 *
 * @package        Webpackages
 * @subpackage     controllers
 * @category       number
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class number extends initiator implements IStatic
{

	/**
	 * Zum initialisieren der Daten
	 */
	public static function init()
	{
	}

	/**
	 * Gibt die width und height Werte als Proportionales skalieren zurück. Hierbei müssen entweder gewünschte
	 * Breite oder gewünschte Höhe angegeben werden, aber nicht beide.
	 *
	 * @param float $sourceWidth  Original Breite
	 * @param float $sourceHeight Original Höhe
	 * @param float $destWidth    Gewünschte Breite
	 * @param float $destHeight   Gewünschte Höhe
	 *
	 * @return array Gibt ein assoziatives array mit den neuen Werten zurück
	 */
	protected static function _scale_proportionally($sourceWidth, $sourceHeight, $destWidth = 0.00, $destHeight = 0.00)
	{
		if($destHeight >= $destWidth)
		{
			$backHeight = $destHeight;
			$factor     = $destHeight / $sourceHeight;
			$backWidth  = $sourceWidth * $factor;
		}
		else
		{
			$backWidth  = $destWidth;
			$factor     = $destWidth / $sourceWidth;
			$backHeight = $sourceHeight * $factor;
		}

		return array(
			'width' => $backWidth,
			'height' => $backHeight
		);
	}

	/**
	 * Wandelt eine Zahl in ein Computer Byte-Format um
	 *
	 * @param int $num       Der Numerische Wert der umgewandelt werden soll
	 * @param int $precision Wieviel stellen nach dem Komma soll der Wert zurück kommen. Standartmäßig "1"
	 *
	 * @return string Gibt den umgewandelten Wert zurück.
	 */
	protected static function _byte_format($num, $precision = 1)
	{
		if($num >= 1000000000000)
		{
			$num  = round($num / 1099511627776, $precision);
			$unit = 'TB';
		}
		elseif($num >= 1000000000)
		{
			$num  = round($num / 1073741824, $precision);
			$unit = 'GB';
		}
		elseif($num >= 1000000)
		{
			$num  = round($num / 1048576, $precision);
			$unit = 'MB';
		}
		elseif($num >= 1000)
		{
			$num  = round($num / 1024, $precision);
			$unit = 'KB';
		}
		else
		{
			$unit = 'B';

			$back = number_format($num).' '.$unit;

			return $back;
		}

		$back = number_format($num, $precision).' '.$unit;

		return $back;
	}

	/**
	 * Errechnet die Differenz aus zwei Timestamps
	 *
	 * @param mixed      $start Startdatum im UNIX Timestamp Format
	 * @param bool|int $end   Das Enddatum mit dem das Startdatum verglichen werden soll im UNIX Timestamp Format.
	 *                        Alternativ kann man auch den Boolischen Wert false setzen. Somit wird der aktuelle UNIX
	 *                        Timestamp genommen. Standartmäßig false.
	 *
	 * @return object Gibt ein Objekt mit den aktuellen Differenzen in Jahren, Monaten, Tagen, Stunden, Minuten und
	 *                Sekunden zurück.
	 * @throws numberException
	 */
	protected static function _diff($start, $end = false)
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new numberException('Error: DateTime or DateTimeZone class not in php exists');
		}

		if(is_string($start) || (int)$start == 0)
		{
			$start	=	strtotime($start);
		}

		if(empty($end))
		{
			$end = time();
		}

		$start = date('Y-m-d H:i:s', $start);
		$end   = date('Y-m-d H:i:s', $end);

		$d_start = new \DateTime($start, new \DateTimeZone(TIMEZONE));
		$d_end   = new \DateTime($end, new \DateTimeZone(TIMEZONE));
		$diff    = $d_start->diff($d_end);

		$back = new \stdClass();

		$back->year  = $diff->format('%y');
		$back->month = $diff->format('%m');
		$back->day   = $diff->format('%d');
		$back->hour  = $diff->format('%h');
		$back->min   = $diff->format('%i');
		$back->sec   = $diff->format('%s');

		return $back;
	}

	/**
	 * Gibt, in Worten, die Different eines Datums aus
	 *
	 * @param object  $diffDate Ein Objekt das von der Methode "diff" kommt
	 * @param boolean $short    Ob kurze Begrifflichkeiten genutzt werden sollen beim genauen Wortlaut. Standartmäßig
	 *                          false.
	 *
	 * @return string Gibt den Wortlaut der Differenz zurück.
	 * @throws numberException
	 */
	protected static function _get_diff_value($diffDate, $short = false)
	{
		$return = '';

		if(!isset($diffDate->year))
		{
			throw new numberException('year not defined');
		}
		elseif(!isset($diffDate->month))
		{
			throw new numberException('month not defined');
		}
		elseif(!isset($diffDate->day))
		{
			throw new numberException('day not defined');
		}
		elseif(!isset($diffDate->hour))
		{
			throw new numberException('hour not defined');
		}
		elseif(!isset($diffDate->min))
		{
			throw new numberException('minutes (min) not defined');
		}
		elseif(!isset($diffDate->sec))
		{
			throw new numberException('secondy (sec) not defined');
		}

		if(!class_exists('language'))
		{
			if(class_exists('\package\core\autoload'))
			{
				autoload::get('language', '\package\core\\', true);
			}
			else
			{
				require 'language.class.php';
			}

			language::init();
		}

		if($diffDate->year == 0 && $diffDate->month == 0)
		{
			if($diffDate->day == 0)
			{
				if($diffDate->hour == 0)
				{
					if($diffDate->min == 0)
					{
						if($diffDate->sec == 0)
						{
							$return = language::translate('Jetzt');
						}
						else if($diffDate->sec == 1)
						{
							$return = $diffDate->sec.(($short) ? language::translate(' Sek.') : language::translate(' Sekunde'));
						}
						else
						{
							$return = $diffDate->sec.(($short) ? language::translate(' Sek.') : language::translate(' Sekunden'));
						}
					}
					else
					{
						if($short)
						{
							$return = $diffDate->min.' '.language::translate('Min.');
						}
						else
						{
							$return = $diffDate->min.' '.(($diffDate->min == 1) ? language::translate('Minute') : language::translate('Minuten'));
						}

						if($diffDate->sec > 0)
						{
							if($short)
							{
								$return .= ' '.$diffDate->sec.' '.language::translate('Sek.');
							}
							else
							{
								$return .= ' '.$diffDate->sec.' '.(($diffDate->sec == 1) ? language::translate('Sekunde') : language::translate('Sekunden'));
							}
						}
					}
				}
				else
				{
					if($short)
					{
						$return = $diffDate->hour.' '.language::translate('Std.').' '.$diffDate->min.' '.language::translate('Min.');
					}
					else
					{
						$return = $diffDate->hour.' '.(($diffDate->hour == 1) ? language::translate('Stunde') : language::translate('Stunden')).' '.$diffDate->min.' '.(($diffDate->min == 1) ? language::translate('Minute') : language::translate('Minuten'));
					}
				}
			}
			else
			{
				if($short)
				{
					$return = $diffDate->day.' '.(($diffDate->day == 1) ? language::translate('Tag') : language::translate('Tage')).' '.$diffDate->hour.' '.language::translate('Std.');
				}
				else
				{
					$return = $diffDate->day.' '.(($diffDate->day == 1) ? language::translate('Tag') : language::translate('Tage')).' '.$diffDate->hour.' '.(($diffDate->hour == 1) ? language::translate('Stunde') : language::translate('Stunden'));
				}
			}
		}

		return $return;
	}
} 