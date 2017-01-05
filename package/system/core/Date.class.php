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
 * @subpackage    core
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\dateException;
use package\implement\IStatic;
use package\system\core\initiator;

/**
 * Feiertage, Datum miteinander vergleichen etc.
 *
 * Die Klasse kann die Feiertag von unterschiedlichen Ländern zurück geben. Die aktuelle Zeit in der richtigen
 * Zeitzone oder einfach nur zur Formatierung eines Timestamps oder Datums.
 *
 * @method static int now($timezone = TIMEZONE)
 * @method static int get_timestamp_by_date(string $date)
 * @method static get_date_by_timestamp(int $timestamp, $format = 'Y-m-d')
 * @method static string|int get_easter_day_by_year(int $year, $inTimestamp = false)
 * @method static array get_nation_holidays_by_year(int $year, string $nation = self::NATION_GERMANY)
 * @method static \DateTime get_all_saints_day(int $year)
 * @method static \DateTime get_mid_summer_day(int $year)
 * @method static bool is_year_leap_year(int $year)
 * @method static int get_days_in_month(int $month, $year = 0, $particular_calendar = CAL_GREGORIAN)
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       Date
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class Date extends initiator implements IStatic
{
	/**
	 * @var array Alle Zeitzonen. Erst sortiert nach Kontinent und anschließend Alphabetisch.
	 */
	public static $timezone = array(array('zone' => 'Africa', 'cities' => array('Africa/Abidjan' => 'Abidjan', 'Africa/Accra' => 'Accra', 'Africa/Addis_Ababa' => 'Addis Ababa', 'Africa/Algiers' => 'Algiers', 'Africa/Asmara' => 'Asmara', 'Africa/Bamako' => 'Bamako', 'Africa/Bangui' => 'Bangui', 'Africa/Banjul' => 'Banjul', 'Africa/Bissau' => 'Bissau', 'Africa/Blantyre' => 'Blantyre', 'Africa/Brazzaville' => 'Brazzaville', 'Africa/Bujumbura' => 'Bujumbura', 'Africa/Cairo' => 'Cairo', 'Africa/Casablanca' => 'Casablanca', 'Africa/Ceuta' => 'Ceuta', 'Africa/Conakry' => 'Conakry', 'Africa/Dakar' => 'Dakar', 'Africa/Dar_es_Salaam' => 'Dar es Salaam', 'Africa/Djibouti' => 'Djibouti', 'Africa/Douala' => 'Douala', 'Africa/El_Aaiun' => 'El Aaiun', 'Africa/Freetown' => 'Freetown', 'Africa/Gaborone' => 'Gaborone', 'Africa/Harare' => 'Harare', 'Africa/Johannesburg' => 'Johannesburg', 'Africa/Juba' => 'Juba', 'Africa/Kampala' => 'Kampala', 'Africa/Khartoum' => 'Khartoum', 'Africa/Kigali' => 'Kigali', 'Africa/Kinshasa' => 'Kinshasa', 'Africa/Lagos' => 'Lagos', 'Africa/Libreville' => 'Libreville', 'Africa/Lome' => 'Lome', 'Africa/Luanda' => 'Luanda', 'Africa/Lubumbashi' => 'Lubumbashi', 'Africa/Lusaka' => 'Lusaka', 'Africa/Malabo' => 'Malabo', 'Africa/Maputo' => 'Maputo', 'Africa/Maseru' => 'Maseru', 'Africa/Mbabane' => 'Mbabane', 'Africa/Mogadishu' => 'Mogadishu', 'Africa/Monrovia' => 'Monrovia', 'Africa/Nairobi' => 'Nairobi', 'Africa/Ndjamena' => 'Ndjamena', 'Africa/Niamey' => 'Niamey', 'Africa/Nouakchott' => 'Nouakchott', 'Africa/Ouagadougou' => 'Ouagadougou', 'Africa/Porto-Novo' => 'Porto-Novo', 'Africa/Sao_Tome' => 'Sao Tome', 'Africa/Tripoli' => 'Tripoli', 'Africa/Tunis' => 'Tunis', 'Africa/Windhoek' => 'Windhoek')), array('zone' => 'America', 'cities' => array('America/Adak' => 'Adak', 'America/Anchorage' => 'Anchorage', 'America/Anguilla' => 'Anguilla', 'America/Antigua' => 'Antigua', 'America/Araguaina' => 'Araguaina', 'America/Argentina/Buenos_Aires' => 'Argentina - Buenos Aires', 'America/Argentina/Catamarca' => 'Argentina - Catamarca', 'America/Argentina/Cordoba' => 'Argentina - Cordoba', 'America/Argentina/Jujuy' => 'Argentina - Jujuy', 'America/Argentina/La_Rioja' => 'Argentina - La Rioja', 'America/Argentina/Mendoza' => 'Argentina - Mendoza', 'America/Argentina/Rio_Gallegos' => 'Argentina - Rio Gallegos', 'America/Argentina/Salta' => 'Argentina - Salta', 'America/Argentina/San_Juan' => 'Argentina - San Juan', 'America/Argentina/San_Luis' => 'Argentina - San Luis', 'America/Argentina/Tucuman' => 'Argentina - Tucuman', 'America/Argentina/Ushuaia' => 'Argentina - Ushuaia', 'America/Aruba' => 'Aruba', 'America/Asuncion' => 'Asuncion', 'America/Atikokan' => 'Atikokan', 'America/Bahia' => 'Bahia', 'America/Bahia_Banderas' => 'Bahia Banderas', 'America/Barbados' => 'Barbados', 'America/Belem' => 'Belem', 'America/Belize' => 'Belize', 'America/Blanc-Sablon' => 'Blanc-Sablon', 'America/Boa_Vista' => 'Boa Vista', 'America/Bogota' => 'Bogota', 'America/Boise' => 'Boise', 'America/Cambridge_Bay' => 'Cambridge Bay', 'America/Campo_Grande' => 'Campo Grande', 'America/Cancun' => 'Cancun', 'America/Caracas' => 'Caracas', 'America/Cayenne' => 'Cayenne', 'America/Cayman' => 'Cayman', 'America/Chicago' => 'Chicago', 'America/Chihuahua' => 'Chihuahua', 'America/Costa_Rica' => 'Costa Rica', 'America/Creston' => 'Creston', 'America/Cuiaba' => 'Cuiaba', 'America/Curacao' => 'Curacao', 'America/Danmarkshavn' => 'Danmarkshavn', 'America/Dawson' => 'Dawson', 'America/Dawson_Creek' => 'Dawson Creek', 'America/Denver' => 'Denver', 'America/Detroit' => 'Detroit', 'America/Dominica' => 'Dominica', 'America/Edmonton' => 'Edmonton', 'America/Eirunepe' => 'Eirunepe', 'America/El_Salvador' => 'El Salvador', 'America/Fortaleza' => 'Fortaleza', 'America/Glace_Bay' => 'Glace Bay', 'America/Godthab' => 'Godthab', 'America/Goose_Bay' => 'Goose Bay', 'America/Grand_Turk' => 'Grand Turk', 'America/Grenada' => 'Grenada', 'America/Guadeloupe' => 'Guadeloupe', 'America/Guatemala' => 'Guatemala', 'America/Guayaquil' => 'Guayaquil', 'America/Guyana' => 'Guyana', 'America/Halifax' => 'Halifax', 'America/Havana' => 'Havana', 'America/Hermosillo' => 'Hermosillo', 'America/Indiana/Indianapolis' => 'Indiana - Indianapolis', 'America/Indiana/Knox' => 'Indiana - Knox', 'America/Indiana/Marengo' => 'Indiana - Marengo', 'America/Indiana/Petersburg' => 'Indiana - Petersburg', 'America/Indiana/Tell_City' => 'Indiana - Tell City', 'America/Indiana/Vevay' => 'Indiana - Vevay', 'America/Indiana/Vincennes' => 'Indiana - Vincennes', 'America/Indiana/Winamac' => 'Indiana - Winamac', 'America/Inuvik' => 'Inuvik', 'America/Iqaluit' => 'Iqaluit', 'America/Jamaica' => 'Jamaica', 'America/Juneau' => 'Juneau', 'America/Kentucky/Louisville' => 'Kentucky - Louisville', 'America/Kentucky/Monticello' => 'Kentucky - Monticello', 'America/Kralendijk' => 'Kralendijk', 'America/La_Paz' => 'La Paz', 'America/Lima' => 'Lima', 'America/Los_Angeles' => 'Los Angeles', 'America/Lower_Princes' => 'Lower Princes', 'America/Maceio' => 'Maceio', 'America/Managua' => 'Managua', 'America/Manaus' => 'Manaus', 'America/Marigot' => 'Marigot', 'America/Martinique' => 'Martinique', 'America/Matamoros' => 'Matamoros', 'America/Mazatlan' => 'Mazatlan', 'America/Menominee' => 'Menominee', 'America/Merida' => 'Merida', 'America/Metlakatla' => 'Metlakatla', 'America/Mexico_City' => 'Mexico City', 'America/Miquelon' => 'Miquelon', 'America/Moncton' => 'Moncton', 'America/Monterrey' => 'Monterrey', 'America/Montevideo' => 'Montevideo', 'America/Montserrat' => 'Montserrat', 'America/Nassau' => 'Nassau', 'America/New_York' => 'New York', 'America/Nipigon' => 'Nipigon', 'America/Nome' => 'Nome', 'America/Noronha' => 'Noronha', 'America/North_Dakota/Beulah' => 'North Dakota - Beulah', 'America/North_Dakota/Center' => 'North Dakota - Center', 'America/North_Dakota/New_Salem' => 'North Dakota - New Salem', 'America/Ojinaga' => 'Ojinaga', 'America/Panama' => 'Panama', 'America/Pangnirtung' => 'Pangnirtung', 'America/Paramaribo' => 'Paramaribo', 'America/Phoenix' => 'Phoenix', 'America/Port-au-Prince' => 'Port-au-Prince', 'America/Port_of_Spain' => 'Port of Spain', 'America/Porto_Velho' => 'Porto Velho', 'America/Puerto_Rico' => 'Puerto Rico', 'America/Rainy_River' => 'Rainy River', 'America/Rankin_Inlet' => 'Rankin Inlet', 'America/Recife' => 'Recife', 'America/Regina' => 'Regina', 'America/Resolute' => 'Resolute', 'America/Rio_Branco' => 'Rio Branco', 'America/Santa_Isabel' => 'Santa Isabel', 'America/Santarem' => 'Santarem', 'America/Santiago' => 'Santiago', 'America/Santo_Domingo' => 'Santo Domingo', 'America/Sao_Paulo' => 'Sao Paulo', 'America/Scoresbysund' => 'Scoresbysund', 'America/Sitka' => 'Sitka', 'America/St_Barthelemy' => 'St Barthelemy', 'America/St_Johns' => 'St Johns', 'America/St_Kitts' => 'St Kitts', 'America/St_Lucia' => 'St Lucia', 'America/St_Thomas' => 'St Thomas', 'America/St_Vincent' => 'St Vincent', 'America/Swift_Current' => 'Swift Current', 'America/Tegucigalpa' => 'Tegucigalpa', 'America/Thule' => 'Thule', 'America/Thunder_Bay' => 'Thunder Bay', 'America/Tijuana' => 'Tijuana', 'America/Toronto' => 'Toronto', 'America/Tortola' => 'Tortola', 'America/Vancouver' => 'Vancouver', 'America/Whitehorse' => 'Whitehorse', 'America/Winnipeg' => 'Winnipeg', 'America/Yakutat' => 'Yakutat', 'America/Yellowknife' => 'Yellowknife')), array('zone' => 'Antarctica', 'cities' => array('Antarctica/Casey' => 'Casey', 'Antarctica/Davis' => 'Davis', 'Antarctica/DumontDUrville' => 'DumontDUrville', 'Antarctica/Macquarie' => 'Macquarie', 'Antarctica/Mawson' => 'Mawson', 'Antarctica/McMurdo' => 'McMurdo', 'Antarctica/Palmer' => 'Palmer', 'Antarctica/Rothera' => 'Rothera', 'Antarctica/Syowa' => 'Syowa', 'Antarctica/Troll' => 'Troll', 'Antarctica/Vostok' => 'Vostok')), array('zone' => 'Arctic', 'cities' => array('Arctic/Longyearbyen' => 'Longyearbyen')), array('zone' => 'Asia', 'cities' => array('Asia/Aden' => 'Aden', 'Asia/Almaty' => 'Almaty', 'Asia/Amman' => 'Amman', 'Asia/Anadyr' => 'Anadyr', 'Asia/Aqtau' => 'Aqtau', 'Asia/Aqtobe' => 'Aqtobe', 'Asia/Ashgabat' => 'Ashgabat', 'Asia/Baghdad' => 'Baghdad', 'Asia/Bahrain' => 'Bahrain', 'Asia/Baku' => 'Baku', 'Asia/Bangkok' => 'Bangkok', 'Asia/Beirut' => 'Beirut', 'Asia/Bishkek' => 'Bishkek', 'Asia/Brunei' => 'Brunei', 'Asia/Chita' => 'Chita', 'Asia/Choibalsan' => 'Choibalsan', 'Asia/Colombo' => 'Colombo', 'Asia/Damascus' => 'Damascus', 'Asia/Dhaka' => 'Dhaka', 'Asia/Dili' => 'Dili', 'Asia/Dubai' => 'Dubai', 'Asia/Dushanbe' => 'Dushanbe', 'Asia/Gaza' => 'Gaza', 'Asia/Hebron' => 'Hebron', 'Asia/Ho_Chi_Minh' => 'Ho Chi Minh', 'Asia/Hong_Kong' => 'Hong Kong', 'Asia/Hovd' => 'Hovd', 'Asia/Irkutsk' => 'Irkutsk', 'Asia/Jakarta' => 'Jakarta', 'Asia/Jayapura' => 'Jayapura', 'Asia/Jerusalem' => 'Jerusalem', 'Asia/Kabul' => 'Kabul', 'Asia/Kamchatka' => 'Kamchatka', 'Asia/Karachi' => 'Karachi', 'Asia/Kathmandu' => 'Kathmandu', 'Asia/Khandyga' => 'Khandyga', 'Asia/Kolkata' => 'Kolkata', 'Asia/Krasnoyarsk' => 'Krasnoyarsk', 'Asia/Kuala_Lumpur' => 'Kuala Lumpur', 'Asia/Kuching' => 'Kuching', 'Asia/Kuwait' => 'Kuwait', 'Asia/Macau' => 'Macau', 'Asia/Magadan' => 'Magadan', 'Asia/Makassar' => 'Makassar', 'Asia/Manila' => 'Manila', 'Asia/Muscat' => 'Muscat', 'Asia/Nicosia' => 'Nicosia', 'Asia/Novokuznetsk' => 'Novokuznetsk', 'Asia/Novosibirsk' => 'Novosibirsk', 'Asia/Omsk' => 'Omsk', 'Asia/Oral' => 'Oral', 'Asia/Phnom_Penh' => 'Phnom Penh', 'Asia/Pontianak' => 'Pontianak', 'Asia/Pyongyang' => 'Pyongyang', 'Asia/Qatar' => 'Qatar', 'Asia/Qyzylorda' => 'Qyzylorda', 'Asia/Rangoon' => 'Rangoon', 'Asia/Riyadh' => 'Riyadh', 'Asia/Sakhalin' => 'Sakhalin', 'Asia/Samarkand' => 'Samarkand', 'Asia/Seoul' => 'Seoul', 'Asia/Shanghai' => 'Shanghai', 'Asia/Singapore' => 'Singapore', 'Asia/Srednekolymsk' => 'Srednekolymsk', 'Asia/Taipei' => 'Taipei', 'Asia/Tashkent' => 'Tashkent', 'Asia/Tbilisi' => 'Tbilisi', 'Asia/Tehran' => 'Tehran', 'Asia/Thimphu' => 'Thimphu', 'Asia/Tokyo' => 'Tokyo', 'Asia/Ulaanbaatar' => 'Ulaanbaatar', 'Asia/Urumqi' => 'Urumqi', 'Asia/Ust-Nera' => 'Ust-Nera', 'Asia/Vientiane' => 'Vientiane', 'Asia/Vladivostok' => 'Vladivostok', 'Asia/Yakutsk' => 'Yakutsk', 'Asia/Yekaterinburg' => 'Yekaterinburg', 'Asia/Yerevan' => 'Yerevan')), array('zone' => 'Atlantic', 'cities' => array('Atlantic/Azores' => 'Azores', 'Atlantic/Bermuda' => 'Bermuda', 'Atlantic/Canary' => 'Canary', 'Atlantic/Cape_Verde' => 'Cape Verde', 'Atlantic/Faroe' => 'Faroe', 'Atlantic/Madeira' => 'Madeira', 'Atlantic/Reykjavik' => 'Reykjavik', 'Atlantic/South_Georgia' => 'South Georgia', 'Atlantic/Stanley' => 'Stanley', 'Atlantic/St_Helena' => 'St Helena')), array('zone' => 'Australia', 'cities' => array('Australia/Adelaide' => 'Adelaide', 'Australia/Brisbane' => 'Brisbane', 'Australia/Broken_Hill' => 'Broken Hill', 'Australia/Currie' => 'Currie', 'Australia/Darwin' => 'Darwin', 'Australia/Eucla' => 'Eucla', 'Australia/Hobart' => 'Hobart', 'Australia/Lindeman' => 'Lindeman', 'Australia/Lord_Howe' => 'Lord Howe', 'Australia/Melbourne' => 'Melbourne', 'Australia/Perth' => 'Perth', 'Australia/Sydney' => 'Sydney')), array('zone' => 'Europe', 'cities' => array('Europe/Amsterdam' => 'Amsterdam', 'Europe/Andorra' => 'Andorra', 'Europe/Athens' => 'Athens', 'Europe/Belgrade' => 'Belgrade', 'Europe/Berlin' => 'Berlin', 'Europe/Bratislava' => 'Bratislava', 'Europe/Brussels' => 'Brussels', 'Europe/Bucharest' => 'Bucharest', 'Europe/Budapest' => 'Budapest', 'Europe/Busingen' => 'Busingen', 'Europe/Chisinau' => 'Chisinau', 'Europe/Copenhagen' => 'Copenhagen', 'Europe/Dublin' => 'Dublin', 'Europe/Gibraltar' => 'Gibraltar', 'Europe/Guernsey' => 'Guernsey', 'Europe/Helsinki' => 'Helsinki', 'Europe/Isle_of_Man' => 'Isle of Man', 'Europe/Istanbul' => 'Istanbul', 'Europe/Jersey' => 'Jersey', 'Europe/Kaliningrad' => 'Kaliningrad', 'Europe/Kiev' => 'Kiev', 'Europe/Lisbon' => 'Lisbon', 'Europe/Ljubljana' => 'Ljubljana', 'Europe/London' => 'London', 'Europe/Luxembourg' => 'Luxembourg', 'Europe/Madrid' => 'Madrid', 'Europe/Malta' => 'Malta', 'Europe/Mariehamn' => 'Mariehamn', 'Europe/Minsk' => 'Minsk', 'Europe/Monaco' => 'Monaco', 'Europe/Moscow' => 'Moscow', 'Europe/Oslo' => 'Oslo', 'Europe/Paris' => 'Paris', 'Europe/Podgorica' => 'Podgorica', 'Europe/Prague' => 'Prague', 'Europe/Riga' => 'Riga', 'Europe/Rome' => 'Rome', 'Europe/Samara' => 'Samara', 'Europe/San_Marino' => 'San Marino', 'Europe/Sarajevo' => 'Sarajevo', 'Europe/Simferopol' => 'Simferopol', 'Europe/Skopje' => 'Skopje', 'Europe/Sofia' => 'Sofia', 'Europe/Stockholm' => 'Stockholm', 'Europe/Tallinn' => 'Tallinn', 'Europe/Tirane' => 'Tirane', 'Europe/Uzhgorod' => 'Uzhgorod', 'Europe/Vaduz' => 'Vaduz', 'Europe/Vatican' => 'Vatican', 'Europe/Vienna' => 'Vienna', 'Europe/Vilnius' => 'Vilnius', 'Europe/Volgograd' => 'Volgograd', 'Europe/Warsaw' => 'Warsaw', 'Europe/Zagreb' => 'Zagreb', 'Europe/Zaporozhye' => 'Zaporozhye', 'Europe/Zurich' => 'Zurich')), array('zone' => 'Indian', 'cities' => array('Indian/Antananarivo' => 'Antananarivo', 'Indian/Chagos' => 'Chagos', 'Indian/Christmas' => 'Christmas', 'Indian/Cocos' => 'Cocos', 'Indian/Comoro' => 'Comoro', 'Indian/Kerguelen' => 'Kerguelen', 'Indian/Mahe' => 'Mahe', 'Indian/Maldives' => 'Maldives', 'Indian/Mauritius' => 'Mauritius', 'Indian/Mayotte' => 'Mayotte', 'Indian/Reunion' => 'Reunion')), array('zone' => 'Pacific', 'cities' => array('Pacific/Apia' => 'Apia', 'Pacific/Auckland' => 'Auckland', 'Pacific/Chatham' => 'Chatham', 'Pacific/Chuuk' => 'Chuuk', 'Pacific/Easter' => 'Easter', 'Pacific/Efate' => 'Efate', 'Pacific/Enderbury' => 'Enderbury', 'Pacific/Fakaofo' => 'Fakaofo', 'Pacific/Fiji' => 'Fiji', 'Pacific/Funafuti' => 'Funafuti', 'Pacific/Galapagos' => 'Galapagos', 'Pacific/Gambier' => 'Gambier', 'Pacific/Guadalcanal' => 'Guadalcanal', 'Pacific/Guam' => 'Guam', 'Pacific/Honolulu' => 'Honolulu', 'Pacific/Johnston' => 'Johnston', 'Pacific/Kiritimati' => 'Kiritimati', 'Pacific/Kosrae' => 'Kosrae', 'Pacific/Kwajalein' => 'Kwajalein', 'Pacific/Majuro' => 'Majuro', 'Pacific/Marquesas' => 'Marquesas', 'Pacific/Midway' => 'Midway', 'Pacific/Nauru' => 'Nauru', 'Pacific/Niue' => 'Niue', 'Pacific/Norfolk' => 'Norfolk', 'Pacific/Noumea' => 'Noumea', 'Pacific/Pago_Pago' => 'Pago Pago', 'Pacific/Palau' => 'Palau', 'Pacific/Pitcairn' => 'Pitcairn', 'Pacific/Pohnpei' => 'Pohnpei', 'Pacific/Port_Moresby' => 'Port Moresby', 'Pacific/Rarotonga' => 'Rarotonga', 'Pacific/Saipan' => 'Saipan', 'Pacific/Tahiti' => 'Tahiti', 'Pacific/Tarawa' => 'Tarawa', 'Pacific/Tongatapu' => 'Tongatapu', 'Pacific/Wake' => 'Wake', 'Pacific/Wallis' => 'Wallis')));

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

	const NATION_GERMANY   = 'Germany';
	const NATION_AUSTRIAN  = 'Austrian';
	const NATION_DENMARK   = 'Denmark';
	const NATION_FRENCH    = 'French';
	const NATION_ITALIAN   = 'Italian';
	const NATION_NORWEGIAN = 'Norwegian';
	const NATION_POLISH    = 'Polish';
	const NATION_SWEDISH   = 'Swedish';

	/**
	 * Zum initialisieren von Daten
	 */
	public static function init()
	{
		if(TIMEZONE == '')
		{
			throw new dateException('Error: Constant TIMEZONE is empty');
		}
	}

	/**
	 * Gibt den aktuellen Zeitstempel der angegebenen Zeitzone zurück
	 *
	 * @param string $timezone Zeitzone. Standartmäßig die aus der constants.php
	 *
	 * @return int Gibt den Zeitstempel in Sekunden zurück
	 * @throws dateException
	 */
	protected static function _now($timezone = TIMEZONE)
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new dateException('Error: DateTime or DateTimeZone class not in php installed');
		}

		if(empty($timezone))
		{
			$timezone = TIMEZONE;
		}

		if($timezone == 'local' || $timezone == date_default_timezone_get())
		{
			return time();
		}

		$datetime = new \DateTime('now', new \DateTimeZone($timezone));

		return $datetime->getTimestamp();
	}

	/**
	 * Gibt den Zeitstempel eines Datums zurück
	 * unter Berücksichtigung der Zeitzone.
	 *
	 * @param string $date Das Datum in einem gängigen Format
	 *
	 * @return int Gibt den Zeitstempel in Sekunden zurück.
	 * @throws dateException
	 */
	protected static function _get_timestamp_by_date($date)
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new dateException('Error: DateTime or DateTimeZone class not in php installed');
		}

		$datetime = new \DateTime($date, new \DateTimeZone(TIMEZONE));

		return $datetime->getTimestamp();
	}

	/**
	 * Gibt das Datum eines Zeitstempels unter
	 * Berücksichtigung der Zeitzone zurück.
	 *
	 * @param int    $timestamp Der Zeitstempel in Sekunden
	 * @param string $format    Das Format in das der Zeitstempel umgewandelt werden soll hierzu siehe auch
	 *                          http://php.net/manual/de/function.date.php
	 *
	 * @return string
	 * @throws dateException
	 */
	protected static function _get_date_by_timestamp($timestamp, $format = 'Y-m-d')
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new dateException('Error: DateTime or DateTimeZone class not in php installed');
		}

		$datetime = new \DateTime();
		$datetime->setTimestamp($timestamp);
		$datetime->setTimezone(new \DateTimeZone(TIMEZONE));

		return $datetime->format($format);
	}

	/**
	 * Gibt das Datum von Ostern eines bestimmten Jahres zurück
	 *
	 * @param int  $year        Das Jahr von dem die Ostertage zurück gegeben werden soll
	 * @param bool $inTimestamp Ob die Ostertage in einem Datum oder als Zeitstempel zurück gegeben werden sollen
	 *
	 * @return string|int Datum mit Y-m-d oder als Zeitstempel in Sekunden
	 * @throws dateException
	 */
	protected static function _get_easter_day_by_year($year, $inTimestamp = false)
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new dateException('Error: DateTime or DateTimeZone class not in php installed');
		}
		else if(!function_exists('easter_days'))
		{
			throw new dateException('Error: easter_days function in php not exists');
		}

		$base = new \DateTime($year.'-03-21', new \DateTimeZone(TIMEZONE));
		$day  = easter_days($year);

		$base->modify('+'.$day.' Days');

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
	 * Gibt alle Feiertage in einem Jahr zurück
	 *
	 * @param int $year Aus welchem Jahr die Feiertage zurück gegeben werden sollen
	 *
	 * @return array Gibt alle Feiertage in einem assoziativen Array zurück
	 * @throws dateException
	 */
	private static function get_all_holidays($year)
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new dateException('Error: DateTime or DateTimeZone class not in php installed');
		}
		elseif(!function_exists('easter_days'))
		{
			throw new dateException('Error: easter_days function in php not exists');
		}

		$base = new \DateTime($year.'-03-21', new \DateTimeZone(TIMEZONE));
		$base->modify('+'.easter_days($year).' days');

		$gruendonnerstag = clone $base;
		$gruendonnerstag->modify('-3 days');

		$karfreitag = clone $base;
		$karfreitag->modify('-2 days');

		$ostersamstag = clone $base;
		$ostersamstag->modify('-1 days');

		$ostersonntag = $base;

		$ostermontag = clone $base;
		$ostermontag->modify('+1 days');

		$chirstiHimmelfahrt = clone $base;
		$chirstiHimmelfahrt->modify('+39 days');

		$pfingstsamstag = clone $base;
		$pfingstsamstag->modify('+48 days');

		$pfingstsonntag = clone $base;
		$pfingstsonntag->modify('+49 days');

		$pfingstmontag = clone $base;
		$pfingstmontag->modify('+50 days');

		$fronleichname = clone $base;
		$fronleichname->modify('+60 days');

		$back = array('maundyThursday' => $gruendonnerstag, 'goodFriday' => $karfreitag, 'easterSaturday' => $ostersamstag, 'easterSunday' => $ostersonntag, 'easterMonday' => $ostermontag, 'ascensionDay' => $chirstiHimmelfahrt, 'pentecostSaturday' => $pfingstsamstag, 'pentecostSunday' => $pfingstsonntag, 'pentecostMonday' => $pfingstmontag, 'corpusChristi' => $fronleichname);

		return $back;
	}

	/**
	 * Gibt die Feiertage einer Nation in einem Jahr zurück
	 *
	 * @param int    $year   Aus welchem Jahr die Feiertage zurück geben werden sollen
	 * @param string $nation Aus welchem Land sollen die Feiertage zurück gegeben werden
	 *
	 * @return array Gibt ein assoziatives Array der Feiertage des Landes zurück
	 */
	protected static function _get_nation_holidays_by_year($year, $nation = self::NATION_GERMANY)
	{
		$holidays    = array();
		$allHolidays = self::get_all_holidays($year);

		if($nation == self::NATION_GERMANY)
		{
			$gruendonnerstag    = $allHolidays['maundyThursday']->format('Y-m-d');
			$karfreitag         = $allHolidays['goodFriday']->format('Y-m-d');
			$ostersonntag       = $allHolidays['easterSunday']->format('Y-m-d');
			$ostermontag        = $allHolidays['easterMonday']->format('Y-m-d');
			$chirstiHimmelfahrt = $allHolidays['ascensionDay']->format('Y-m-d');
			$pfingstsamstag     = $allHolidays['pentecostSaturday']->format('Y-m-d');
			$pfingstsonntag     = $allHolidays['pentecostSunday']->format('Y-m-d');
			$pfingstmontag      = $allHolidays['pentecostMonday']->format('Y-m-d');
			$fronleichname      = $allHolidays['corpusChristi']->format('Y-m-d');

			$holidays = array($year.'-01-01' => array('name' => 'Neujahr'), $year.'-01-05' => array('name' => 'Tag der Arbeit'), $year.'-01-06' => array('name' => 'Heilige Drei Könige', 'federal_state' => array(self::STATE_BW, self::STATE_BY, self::STATE_ST)), $gruendonnerstag => array('name' => 'Gründonnerstag'), $karfreitag => array('name' => 'Karfreitag'), $ostersonntag => array('name' => 'Ostersonntag'), $ostermontag => array('name' => 'Ostermontag'), $chirstiHimmelfahrt => array('name' => 'Christi Himmelfahrt'), $pfingstsamstag => array('name' => 'Pfingstsamstag'), $pfingstsonntag => array('name' => 'Pfingstsonntag'), $pfingstmontag => array('name' => 'Pfingstmontag'), $fronleichname => array('name' => 'Fronleichnam', 'federal_state' => array(self::STATE_BW, self::STATE_BY, self::STATE_HE, self::STATE_NW, self::STATE_RP, self::STATE_SL)), $year.'-10-31' => array('name' => 'Reformationstag', 'federal_state' => array(self::STATE_BB, self::STATE_MV, self::STATE_SN, self::STATE_ST, self::STATE_TH)), $year.'-11-01' => array('name' => 'Allerheiligen', 'federal_state' => array(self::STATE_BW, self::STATE_BY, self::STATE_NW, self::STATE_RP, self::STATE_SL)), $year.'-12-25' => array('name' => '1. Weihnachtstag'), $year.'-12-26' => array('name' => '2. Weihnachtstag'));
		}
		elseif($nation == self::NATION_AUSTRIAN)
		{
			$holidays = array($year.'-01-01' => array('name' => 'Neujahr'), $year.'-01-06' => array('name' => 'Heilige Drei Könige'), $year.'-11-01' => array('name' => 'Allerheiligen'), $year.'-08-15' => array('name' => 'Mariä Himmelfahrt'), $year.'-05-01' => array('name' => 'Staatsfeiertag'), $year.'-10-26' => array('name' => 'Nationalfeiertag'), $year.'-12-08' => array('name' => 'Mariä Empfängnis'), $year.'-12-25' => array('name' => 'Weihnachten'), $year.'-12-26' => array('name' => 'Stefanitag'), $allHolidays['easterSunday']->format('Y-m-d') => array('name' => 'Ostersonntag'), $allHolidays['easterMonday']->format('Y-m-d') => array('name' => 'Ostermontag'), $allHolidays['ascensionDay']->format('Y-m-d') => array('name' => 'Christi Himmelfahrt'), $allHolidays['pentecostMonday']->format('Y-m-d') => array('name' => 'Pfingstmontag'), $allHolidays['corpusChristi']->format('Y-m-d') => array('name' => 'Fronleichnam'));
		}
		elseif($nation == self::NATION_DENMARK)
		{
			$greatPrayerDay = clone $allHolidays['easterSunday'];
			$greatPrayerDay->modify('+26 days');

			$holidays = array($year.'-01-01' => array('name' => 'Nytår'), $year.'-12-25' => array('name' => '1. Juledag'), $year.'-12-26' => array('name' => '2. Juledag'), $allHolidays['maundyThursday']->format('Y-m-d') => array('name' => 'Skærtorsdag'), $allHolidays['goodFriday']->format('Y-m-d') => array('name' => 'Langfredag'), $allHolidays['easterSunday']->format('Y-m-d') => array('name' => 'Påskedag'), $allHolidays['easterMonday']->format('Y-m-d') => array('name' => '2. Påskedag'), $greatPrayerDay->format('Y-m-d') => array('name' => 'Store Bededag'), $allHolidays['ascensionDay']->format('Y-m-d') => array('name' => 'Kristi Himmelfartsdag'), $allHolidays['pentecostSunday']->format('Y-m-d') => array('name' => 'Pinsedag'), $allHolidays['pentecostMonday']->format('Y-m-d') => array('name' => '2. Pinsedag'));
		}
		elseif($nation == self::NATION_FRENCH)
		{
			$holidays = array($year.'-01-01' => array('name' => 'Jour de l\'an'), $year.'-05-01' => array('name' => 'Fête du Travail'), $year.'-05-08' => array('name' => '8 Mai 1945'), $year.'-07-14' => array('name' => 'Fête Nationale'), $year.'-08-15' => array('name' => 'Assomption'), $year.'-11-01' => array('name' => 'La Toussaint'), $year.'-11-11' => array('name' => 'Armistice'), $year.'-12-25' => array('name' => 'Noël'), $allHolidays['easterMonday']->format('Y-m-d') => array('name' => 'Lundi de Pâques'), $allHolidays['ascensionDay']->format('Y-m-d') => array('name' => 'Jeudi de l\'Ascension'), $allHolidays['pentecostMonday']->format('Y-m-d') => array('name' => 'Lundi de Pentecôte'));
		}
		elseif($nation == self::NATION_ITALIAN)
		{
			$holidays = array($year.'-01-01' => array('name' => 'Capodanno'), $year.'-01-06' => array('name' => 'Epifania'), $year.'-04-25' => array('name' => 'Liberazione dal nazifascismo (1945)'), $year.'-05-01' => array('name' => 'Festa del lavoro'), $year.'-06-02' => array('name' => 'Festa della Repubblica'), $year.'-08-15' => array('name' => 'Assunzione di Maria'), $year.'-11-01' => array('name' => 'Ognissanti'), $year.'-12-08' => array('name' => 'Immacolata Concezione'), $year.'-12-25' => array('name' => 'Natale di Gesù'), $year.'-12-26' => array('name' => 'Santo Stefano'), $allHolidays['easterSunday']->format('Y-m-d') => array('name' => 'Pasqua'), $allHolidays['easterMonday']->format('Y-m-d') => array('name' => 'Lunedì di Pasqua'));
		}
		elseif($nation == self::NATION_NORWEGIAN)
		{
			$holidays = array($year.'-01-01' => array('name' => '1. nyttårsdag'), $year.'-05-01' => array('name' => '1. mai'), $year.'-05-17' => array('name' => 'Grunnlovsdagen'), $year.'-12-25' => array('name' => '1. juledag'), $year.'-12-26' => array('name' => '2. juledag'), $allHolidays['maundyThursday']->format('Y-m-d') => array('name' => 'Skjærtorsdag'), $allHolidays['goodFriday']->format('Y-m-d') => array('name' => 'Langfredag'), $allHolidays['easterSunday']->format('Y-m-d') => array('name' => '1. påskedag'), $allHolidays['easterMonday']->format('Y-m-d') => array('name' => '2. påskedag'), $allHolidays['ascensionDay']->format('Y-m-d') => array('name' => 'Kristi Himmelfartsdag'), $allHolidays['pentecostMonday']->format('Y-m-d') => array('name' => '2. pinsedag'));
		}
		elseif($nation == self::NATION_POLISH)
		{
			$holidays = array($year.'-01-01' => array('name' => 'Nowy Rok'), $year.'-01-06' => array('name' => 'Trzech Króli'), $year.'-05-01' => array('name' => 'Święto Pracy'), $year.'-05-03' => array('name' => 'Święto Konstytucji Trzeciego Maja'), $year.'-08-15' => array('name' => 'Wniebowzięcie Najświętszej Maryi Panny'), $year.'-11-01' => array('name' => 'Wszystkich Świętych'), $year.'-11-11' => array('name' => 'Święto Niepodległości'), $year.'-12-25' => array('name' => 'Boże Narodzenie'), $year.'-12-26' => array('name' => 'Drugi dzień Bożego Narodzenia'), $allHolidays['easterSunday']->format('Y-m-d') => array('name' => 'Wielkanoc'), $allHolidays['easterMonday']->format('Y-m-d') => array('name' => 'Poniedziałek Wielkanocny'), $allHolidays['corpusChristi']->format('Y-m-d') => array('name' => 'Boże Ciało'));
		}
		elseif($nation == self::NATION_SWEDISH)
		{
			$midSummerDay = self::get_mid_summer_day($year);
			$allSaintsDay = self::get_all_saints_day($year);

			$holidays = array($year.'-01-01' => array('name' => 'Nyårsdagen'), $year.'-01-05' => array('name' => 'Trettondagsafton', 'halfday' => true), $year.'-01-06' => array('name' => 'Trettondedag jul'), $year.'-04-30' => array('name' => 'Valborgsmässoafton', 'halfday' => true), $year.'-05-01' => array('name' => 'Första maj'), $year.'-06-06' => array('name' => 'Sveriges nationaldag'), $year.'-12-24' => array('name' => 'Julafton'), $year.'-12-25' => array('name' => 'Juldagen'), $year.'-12-26' => array('name' => 'Annandag jul'), $year.'-12-31' => array('name' => 'Nyårsafton'), $allHolidays['maundyThursday']->format('Y-m-d') => array('name' => 'Skärtorsdagen', 'halfday' => true), $allHolidays['goodFriday']->format('Y-m-d') => array('name' => 'Långfredagen'), $allHolidays['easterSaturday']->format('Y-m-d') => array('name' => 'Påskafton'), $allHolidays['easterSunday']->format('Y-m-d') => array('name' => 'Påskdagen'), $allHolidays['easterMonday']->format('Y-m-d') => array('name' => 'Annandag påsk'), $allHolidays['ascensionDay']->format('Y-m-d') => array('name' => 'Kristi himmelsfärdsdag'), $allHolidays['pentecostSaturday']->format('Y-m-d') => array('name' => 'Pingstafton'), $allHolidays['pentecostSunday']->format('Y-m-d') => array('name' => 'Pingstdagen'), $midSummerDay->format('Y-m-d') => array('name' => 'Midsommardagen'), $midSummerDay->modify('-1 day')->format('Y-m-d') => array('name' => 'Midsommarafton'), $allSaintsDay->format('Y-m-d') => array('name' => 'Alla helgons dag'), $allSaintsDay->modify('-1 day')->format('Y-m-d') => array('name' => 'Allhelgonaafton', 'halfday' => true));
		}

		ksort($holidays);

		return $holidays;
	}

	/**
	 * Gibt den Allerheiligen in Schweden zurück
	 *
	 * @param int $year Das Jahr aus dem die Allerheiligen Feiertage zurück gegeben werden sollen
	 *
	 * @return \DateTime
	 * @throws dateException
	 */
	protected static function _get_all_saints_day($year)
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new dateException('Error: DateTime or DateTimeZone class not in php installed');
		}

		$date = new \DateTime($year.'-10-31', new \DateTimeZone(TIMEZONE));

		for($i = -1; ++$i < 7;)
		{
			if($date->format('w') == 6)
			{
				break;
			}

			$date->modify('+1 days');
		}

		return $date;
	}

	/**
	 * Gibt das Mittsommer Datum zurück
	 *
	 * @param int $year Das Jahr aus dem die Mittsommer Feiertage zurück gegeben werden sollen
	 *
	 * @return \DateTime
	 * @throws dateException
	 */
	protected static function _get_mid_summer_day($year)
	{
		if(!class_exists('\DateTime') || !class_exists('\DateTimeZone'))
		{
			throw new dateException('Error: DateTime or DateTimeZone class not in php installed');
		}

		$date = new \DateTime($year.'-06-20', new \DateTimeZone(TIMEZONE));

		for($i = -1; ++$i < 7;)
		{
			if($date->format('w') == 6)
			{
				break;
			}

			$date->modify('+1 days');
		}

		return $date;
	}

	/**
	 * Ist ein bestimmtes Jahr ein Schaltjahr
	 *
	 * @param int $year Das Jahr das kontrolliert werden soll ob es ein Schaltjahr ist
	 *
	 * @return bool
	 */
	protected static function _is_year_leap_year($year)
	{
		return (($year % 400) == 0 || (($year % 4) == 0 && ($year % 100) != 0));
	}

	/**
	 * Gibt die Anzahl der Tage eines Monats zurück
	 *
	 * @param int $month               Das Monat aus dem die Anzahl zurück gegeben werden soll
	 * @param int $year                Das Jahr vom Monat
	 * @param int $particular_calendar CAL_GREGORIAN | CAL_JULIAN | CAL_JEWISH | CAL_FRENCH | CAL_NUM_CALS
	 *
	 * @return int
	 * @throws dateException
	 */
	protected static function _get_days_in_month($month, $year = 0, $particular_calendar = CAL_GREGORIAN)
	{
		if(!function_exists('cal_days_in_month'))
		{
			throw new dateException('Error: cal_days_in_month function in php not exists');
		}

		if($month < 1 || $month > 12)
		{
			return 0;
		}

		if(empty($year))
		{
			$year = date('Y');
		}

		return cal_days_in_month($particular_calendar, $month, $year);
	}
}