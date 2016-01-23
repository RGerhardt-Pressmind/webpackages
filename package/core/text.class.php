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

    @category   text.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class text implements IStatic
{
	/**
	 * Zum initiailisieren von Daten
	 */
	public static function init(){}

	/**
	 * @var array Alle erlaubten locales
	 */
	public static $allLocales	=	array(
		'aa_DJ' 	=> 'Afar (Djibouti)',
		'aa_ER' 	=> 'Afar (Eritrea)',
		'aa_ET' 	=> 'Afar (Ethiopia)',
		'af_ZA' 	=> 'Afrikaans (South Africa)',
		'sq_AL' 	=> 'Albanian (Albania)',
		'sq_MK' 	=> 'Albanian (Macedonia)',
		'am_ET' 	=> 'Amharic (Ethiopia)',
		'ar_DZ' 	=> 'Arabic (Algeria)',
		'ar_BH' 	=> 'Arabic (Bahrain)',
		'ar_EG' 	=> 'Arabic (Egypt)',
		'ar_IN' 	=> 'Arabic (India)',
		'ar_IQ' 	=> 'Arabic (Iraq)',
		'ar_JO' 	=> 'Arabic (Jordan)',
		'ar_KW' 	=> 'Arabic (Kuwait)',
		'ar_LB' 	=> 'Arabic (Lebanon)',
		'ar_LY' 	=> 'Arabic (Libya)',
		'ar_MA' 	=> 'Arabic (Morocco)',
		'ar_OM' 	=> 'Arabic (Oman)',
		'ar_QA' 	=> 'Arabic (Qatar)',
		'ar_SA' 	=> 'Arabic (Saudi Arabia)',
		'ar_SD' 	=> 'Arabic (Sudan)',
		'ar_SY' 	=> 'Arabic (Syria)',
		'ar_TN' 	=> 'Arabic (Tunisia)',
		'ar_AE' 	=> 'Arabic (United Arab Emirates)',
		'ar_YE' 	=> 'Arabic (Yemen)',
		'an_ES' 	=> 'Aragonese (Spain)',
		'hy_AM' 	=> 'Armenian (Armenia)',
		'as_IN' 	=> 'Assamese (India)',
		'ast_ES'	=> 'Asturian (Spain)',
		'az_AZ' 	=> 'Azerbaijani (Azerbaijan)',
		'az_TR' 	=> 'Azerbaijani (Turkey)',
		'eu_FR' 	=> 'Basque (France)',
		'eu_ES' 	=> 'Basque (Spain)',
		'be_BY' 	=> 'Belarusian (Belarus)',
		'bem_ZM'	=> 'Bemba (Zambia)',
		'bn_BD' 	=> 'Bengali (Bangladesh)',
		'bn_IN' 	=> 'Bengali (India)',
		'ber_DZ' 	=> 'Berber (Algeria)',
		'ber_MA' 	=> 'Berber (Morocco)',
		'byn_ER' 	=> 'Blin (Eritrea)',
		'bs_BA' 	=> 'Bosnian (Bosnia and Herzegovina)',
		'br_FR' 	=> 'Breton (France)',
		'bg_BG' 	=> 'Bulgarian (Bulgaria)',
		'my_MM' 	=> 'Burmese (Myanmar [Burma])',
		'ca_AD' 	=> 'Catalan (Andorra)',
		'ca_FR' 	=> 'Catalan (France)',
		'ca_IT' 	=> 'Catalan (Italy)',
		'ca_ES' 	=> 'Catalan (Spain)',
		'zh_CN' 	=> 'Chinese (China)',
		'zh_HK' 	=> 'Chinese (Hong Kong SAR China)',
		'zh_SG' 	=> 'Chinese (Singapore)',
		'zh_TW' 	=> 'Chinese (Taiwan)',
		'cv_RU' 	=> 'Chuvash (Russia)',
		'kw_GB' 	=> 'Cornish (United Kingdom)',
		'crh_UA' 	=> 'Crimean Turkish (Ukraine)',
		'hr_HR' 	=> 'Croatian (Croatia)',
		'cs_CZ' 	=> 'Czech (Czech Republic)',
		'da_DK' 	=> 'Danish (Denmark)',
		'dv_MV' 	=> 'Divehi (Maldives)',
		'nl_AW' 	=> 'Dutch (Aruba)',
		'nl_BE' 	=> 'Dutch (Belgium)',
		'nl_NL' 	=> 'Dutch (Netherlands)',
		'dz_BT' 	=> 'Dzongkha (Bhutan)',
		'en_AG' 	=> 'English (Antigua and Barbuda)',
		'en_AU' 	=> 'English (Australia)',
		'en_BW' 	=> 'English (Botswana)',
		'en_CA' 	=> 'English (Canada)',
		'en_DK' 	=> 'English (Denmark)',
		'en_HK' 	=> 'English (Hong Kong SAR China)',
		'en_IN' 	=> 'English (India)',
		'en_IE' 	=> 'English (Ireland)',
		'en_NZ' 	=> 'English (New Zealand)',
		'en_NG' 	=> 'English (Nigeria)',
		'en_PH' 	=> 'English (Philippines)',
		'en_SG' 	=> 'English (Singapore)',
		'en_ZA' 	=> 'English (South Africa)',
		'en_GB' 	=> 'English (United Kingdom)',
		'en_US' 	=> 'English (United States)',
		'en_ZM' 	=> 'English (Zambia)',
		'en_ZW' 	=> 'English (Zimbabwe)',
		'eo' 		=> 'Esperanto',
		'et_EE' 	=> 'Estonian (Estonia)',
		'fo_FO' 	=> 'Faroese (Faroe Islands)',
		'fil_PH'	=> 'Filipino (Philippines)',
		'fi_FI' 	=> 'Finnish (Finland)',
		'fr_BE' 	=> 'French (Belgium)',
		'fr_CA' 	=> 'French (Canada)',
		'fr_FR' 	=> 'French (France)',
		'fr_LU' 	=> 'French (Luxembourg)',
		'fr_CH' 	=> 'French (Switzerland)',
		'fur_IT'	=> 'Friulian (Italy)',
		'ff_SN' 	=> 'Fulah (Senegal)',
		'gl_ES' 	=> 'Galician (Spain)',
		'lg_UG' 	=> 'Ganda (Uganda)',
		'gez_ER' 	=> 'Geez (Eritrea)',
		'gez_ET' 	=> 'Geez (Ethiopia)',
		'ka_GE' 	=> 'Georgian (Georgia)',
		'de_AT' 	=> 'German (Austria)',
		'de_BE' 	=> 'German (Belgium)',
		'de_DE' 	=> 'German (Germany)',
		'de_LI' 	=> 'German (Liechtenstein)',
		'de_LU' 	=> 'German (Luxembourg)',
		'de_CH' 	=> 'German (Switzerland)',
		'el_CY' 	=> 'Greek (Cyprus)',
		'el_GR' 	=> 'Greek (Greece)',
		'gu_IN' 	=> 'Gujarati (India)',
		'ht_HT' 	=> 'Haitian (Haiti)',
		'ha_NG' 	=> 'Hausa (Nigeria)',
		'iw_IL' 	=> 'Hebrew (Israel)',
		'he_IL' 	=> 'Hebrew (Israel)',
		'hi_IN' 	=> 'Hindi (India)',
		'hu_HU' 	=> 'Hungarian (Hungary)',
		'is_IS' 	=> 'Icelandic (Iceland)',
		'ig_NG' 	=> 'Igbo (Nigeria)',
		'id_ID' 	=> 'Indonesian (Indonesia)',
		'ia' 		=> 'Interlingua',
		'iu_CA' 	=> 'Inuktitut (Canada)',
		'ik_CA' 	=> 'Inupiaq (Canada)',
		'ga_IE' 	=> 'Irish (Ireland)',
		'it_IT' 	=> 'Italian (Italy)',
		'it_CH' 	=> 'Italian (Switzerland)',
		'ja_JP' 	=> 'Japanese (Japan)',
		'kl_GL' 	=> 'Kalaallisut (Greenland)',
		'kn_IN' 	=> 'Kannada (India)',
		'ks_IN' 	=> 'Kashmiri (India)',
		'csb_PL'	=> 'Kashubian (Poland)',
		'kk_KZ' 	=> 'Kazakh (Kazakhstan)',
		'km_KH' 	=> 'Khmer (Cambodia)',
		'rw_RW' 	=> 'Kinyarwanda (Rwanda)',
		'ky_KG' 	=> 'Kirghiz (Kyrgyzstan)',
		'kok_IN' 	=> 'Konkani (India)',
		'ko_KR' 	=> 'Korean (South Korea)',
		'ku_TR' 	=> 'Kurdish (Turkey)',
		'lo_LA' 	=> 'Lao (Laos)',
		'lv_LV' 	=> 'Latvian (Latvia)',
		'li_BE' 	=> 'Limburgish (Belgium)',
		'li_NL' 	=> 'Limburgish (Netherlands)',
		'lt_LT' 	=> 'Lithuanian (Lithuania)',
		'nds_DE' 	=> 'Low German (Germany)',
		'nds_NL' 	=> 'Low German (Netherlands)',
		'mk_MK' 	=> 'Macedonian (Macedonia)',
		'mai_IN' 	=> 'Maithili (India)',
		'mg_MG' 	=> 'Malagasy (Madagascar)',
		'ms_MY' 	=> 'Malay (Malaysia)',
		'ml_IN' 	=> 'Malayalam (India)',
		'mt_MT' 	=> 'Maltese (Malta)',
		'gv_GB' 	=> 'Manx (United Kingdom)',
		'mi_NZ' 	=> 'Maori (New Zealand)',
		'mr_IN' 	=> 'Marathi (India)',
		'mn_MN' 	=> 'Mongolian (Mongolia)',
		'ne_NP' 	=> 'Nepali (Nepal)',
		'se_NO' 	=> 'Northern Sami (Norway)',
		'nso_ZA' 	=> 'Northern Sotho (South Africa)',
		'nb_NO' 	=> 'Norwegian Bokmål (Norway)',
		'nn_NO' 	=> 'Norwegian Nynorsk (Norway)',
		'oc_FR' 	=> 'Occitan (France)',
		'or_IN' 	=> 'Oriya (India)',
		'om_ET' 	=> 'Oromo (Ethiopia)',
		'om_KE' 	=> 'Oromo (Kenya)',
		'os_RU' 	=> 'Ossetic (Russia)',
		'pap_AN' 	=> 'Papiamento (Netherlands Antilles)',
		'ps_AF' 	=> 'Pashto (Afghanistan)',
		'fa_IR' 	=> 'Persian (Iran)',
		'pl_PL' 	=> 'Polish (Poland)',
		'pt_BR' 	=> 'Portuguese (Brazil)',
		'pt_PT' 	=> 'Portuguese (Portugal)',
		'pa_IN' 	=> 'Punjabi (India)',
		'pa_PK' 	=> 'Punjabi (Pakistan)',
		'ro_RO' 	=> 'Romanian (Romania)',
		'ru_RU' 	=> 'Russian (Russia)',
		'ru_UA' 	=> 'Russian (Ukraine)',
		'sa_IN' 	=> 'Sanskrit (India)',
		'sc_IT' 	=> 'Sardinian (Italy)',
		'gd_GB' 	=> 'Scottish Gaelic (United Kingdom)',
		'sr_ME' 	=> 'Serbian (Montenegro)',
		'sr_RS' 	=> 'Serbian (Serbia)',
		'sid_ET' 	=> 'Sidamo (Ethiopia)',
		'sd_IN' 	=> 'Sindhi (India)',
		'si_LK' 	=> 'Sinhala (Sri Lanka)',
		'sk_SK' 	=> 'Slovak (Slovakia)',
		'sl_SI' 	=> 'Slovenian (Slovenia)',
		'so_DJ' 	=> 'Somali (Djibouti)',
		'so_ET' 	=> 'Somali (Ethiopia)',
		'so_KE' 	=> 'Somali (Kenya)',
		'so_SO' 	=> 'Somali (Somalia)',
		'nr_ZA' 	=> 'South Ndebele (South Africa)',
		'st_ZA' 	=> 'Southern Sotho (South Africa)',
		'es_AR' 	=> 'Spanish (Argentina)',
		'es_BO' 	=> 'Spanish (Bolivia)',
		'es_CL' 	=> 'Spanish (Chile)',
		'es_CO' 	=> 'Spanish (Colombia)',
		'es_CR' 	=> 'Spanish (Costa Rica)',
		'es_DO' 	=> 'Spanish (Dominican Republic)',
		'es_EC' 	=> 'Spanish (Ecuador)',
		'es_SV' 	=> 'Spanish (El Salvador)',
		'es_GT' 	=> 'Spanish (Guatemala)',
		'es_HN' 	=> 'Spanish (Honduras)',
		'es_MX' 	=> 'Spanish (Mexico)',
		'es_NI' 	=> 'Spanish (Nicaragua)',
		'es_PA' 	=> 'Spanish (Panama)',
		'es_PY' 	=> 'Spanish (Paraguay)',
		'es_PE' 	=> 'Spanish (Peru)',
		'es_ES' 	=> 'Spanish (Spain)',
		'es_US' 	=> 'Spanish (United States)',
		'es_UY' 	=> 'Spanish (Uruguay)',
		'es_VE' 	=> 'Spanish (Venezuela)',
		'sw_KE' 	=> 'Swahili (Kenya)',
		'sw_TZ' 	=> 'Swahili (Tanzania)',
		'ss_ZA' 	=> 'Swati (South Africa)',
		'sv_FI' 	=> 'Swedish (Finland)',
		'sv_SE' 	=> 'Swedish (Sweden)',
		'tl_PH' 	=> 'Tagalog (Philippines)',
		'tg_TJ' 	=> 'Tajik (Tajikistan)',
		'ta_IN' 	=> 'Tamil (India)',
		'tt_RU' 	=> 'Tatar (Russia)',
		'te_IN' 	=> 'Telugu (India)',
		'th_TH' 	=> 'Thai (Thailand)',
		'bo_CN' 	=> 'Tibetan (China)',
		'bo_IN' 	=> 'Tibetan (India)',
		'tig_ER' 	=> 'Tigre (Eritrea)',
		'ti_ER' 	=> 'Tigrinya (Eritrea)',
		'ti_ET' 	=> 'Tigrinya (Ethiopia)',
		'ts_ZA' 	=> 'Tsonga (South Africa)',
		'tn_ZA' 	=> 'Tswana (South Africa)',
		'tr_CY' 	=> 'Turkish (Cyprus)',
		'tr_TR' 	=> 'Turkish (Turkey)',
		'tk_TM' 	=> 'Turkmen (Turkmenistan)',
		'ug_CN' 	=> 'Uighur (China)',
		'uk_UA' 	=> 'Ukrainian (Ukraine)',
		'hsb_DE' 	=> 'Upper Sorbian (Germany)',
		'ur_PK' 	=> 'Urdu (Pakistan)',
		'uz_UZ' 	=> 'Uzbek (Uzbekistan)',
		've_ZA' 	=> 'Venda (South Africa)',
		'vi_VN' 	=> 'Vietnamese (Vietnam)',
		'wa_BE' 	=> 'Walloon (Belgium)',
		'cy_GB' 	=> 'Welsh (United Kingdom)',
		'fy_DE' 	=> 'Western Frisian (Germany)',
		'fy_NL' 	=> 'Western Frisian (Netherlands)',
		'wo_SN' 	=> 'Wolof (Senegal)',
		'xh_ZA' 	=> 'Xhosa (South Africa)',
		'yi_US' 	=> 'Yiddish (United States)',
		'yo_NG' 	=> 'Yoruba (Nigeria)',
		'zu_ZA' 	=> 'Zulu (South Africa)'
	);

	/**
	 * @var array Alle Zeitzonen. Erst sortiert nach Kontinent und anschließend Alphabetisch.
	 */
	public static $timezone	=	array(
		array(
			'zone' 		=> 'Africa',
			'cities'	=>	array(
				'Africa/Abidjan'		=>	'Abidjan',
				'Africa/Accra'			=>	'Accra',
				'Africa/Addis_Ababa'	=>	'Addis Ababa',
				'Africa/Algiers'		=>	'Algiers',
				'Africa/Asmara'			=>	'Asmara',
				'Africa/Bamako'			=>	'Bamako',
				'Africa/Bangui'			=>	'Bangui',
				'Africa/Banjul'			=>	'Banjul',
				'Africa/Bissau'			=>	'Bissau',
				'Africa/Blantyre'		=>	'Blantyre',
				'Africa/Brazzaville'	=>	'Brazzaville',
				'Africa/Bujumbura'		=>	'Bujumbura',
				'Africa/Cairo'			=>	'Cairo',
				'Africa/Casablanca'		=>	'Casablanca',
				'Africa/Ceuta'			=>	'Ceuta',
				'Africa/Conakry'		=>	'Conakry',
				'Africa/Dakar'			=>	'Dakar',
				'Africa/Dar_es_Salaam'	=>	'Dar es Salaam',
				'Africa/Djibouti'		=>	'Djibouti',
				'Africa/Douala'			=>	'Douala',
				'Africa/El_Aaiun'		=>	'El Aaiun',
				'Africa/Freetown'		=>	'Freetown',
				'Africa/Gaborone'		=>	'Gaborone',
				'Africa/Harare'			=>	'Harare',
				'Africa/Johannesburg'	=>	'Johannesburg',
				'Africa/Juba'			=>	'Juba',
				'Africa/Kampala'		=>	'Kampala',
				'Africa/Khartoum'		=>	'Khartoum',
				'Africa/Kigali'			=>	'Kigali',
				'Africa/Kinshasa'		=>	'Kinshasa',
				'Africa/Lagos'			=>	'Lagos',
				'Africa/Libreville'		=>	'Libreville',
				'Africa/Lome'			=>	'Lome',
				'Africa/Luanda'			=>	'Luanda',
				'Africa/Lubumbashi'		=>	'Lubumbashi',
				'Africa/Lusaka'			=>	'Lusaka',
				'Africa/Malabo'			=>	'Malabo',
				'Africa/Maputo'			=>	'Maputo',
				'Africa/Maseru'			=>	'Maseru',
				'Africa/Mbabane'		=>	'Mbabane',
				'Africa/Mogadishu'		=>	'Mogadishu',
				'Africa/Monrovia'		=>	'Monrovia',
				'Africa/Nairobi'		=>	'Nairobi',
				'Africa/Ndjamena'		=>	'Ndjamena',
				'Africa/Niamey'			=>	'Niamey',
				'Africa/Nouakchott'		=>	'Nouakchott',
				'Africa/Ouagadougou'	=>	'Ouagadougou',
				'Africa/Porto-Novo'		=>	'Porto-Novo',
				'Africa/Sao_Tome'		=>	'Sao Tome',
				'Africa/Tripoli'		=>	'Tripoli',
				'Africa/Tunis'			=>	'Tunis',
				'Africa/Windhoek'		=>	'Windhoek'
			)
		),
		array(
			'zone'		=>	'America',
			'cities'	=>	array(
				'America/Adak'						=>	'Adak',
				'America/Anchorage'					=>	'Anchorage',
				'America/Anguilla'					=>	'Anguilla',
				'America/Antigua'					=>	'Antigua',
				'America/Araguaina'					=>	'Araguaina',
				'America/Argentina/Buenos_Aires'	=>	'Argentina - Buenos Aires',
				'America/Argentina/Catamarca'		=>	'Argentina - Catamarca',
				'America/Argentina/Cordoba'			=>	'Argentina - Cordoba',
				'America/Argentina/Jujuy'			=>	'Argentina - Jujuy',
				'America/Argentina/La_Rioja'		=>	'Argentina - La Rioja',
				'America/Argentina/Mendoza'			=>	'Argentina - Mendoza',
				'America/Argentina/Rio_Gallegos'	=>	'Argentina - Rio Gallegos',
				'America/Argentina/Salta'			=>	'Argentina - Salta',
				'America/Argentina/San_Juan'		=>	'Argentina - San Juan',
				'America/Argentina/San_Luis'		=>	'Argentina - San Luis',
				'America/Argentina/Tucuman'			=>	'Argentina - Tucuman',
				'America/Argentina/Ushuaia'			=>	'Argentina - Ushuaia',
				'America/Aruba'						=>	'Aruba',
				'America/Asuncion'					=>	'Asuncion',
				'America/Atikokan'					=>	'Atikokan',
				'America/Bahia'						=>	'Bahia',
				'America/Bahia_Banderas'			=>	'Bahia Banderas',
				'America/Barbados'					=>	'Barbados',
				'America/Belem'						=>	'Belem',
				'America/Belize'					=>	'Belize',
				'America/Blanc-Sablon'				=>	'Blanc-Sablon',
				'America/Boa_Vista'					=>	'Boa Vista',
				'America/Bogota'					=>	'Bogota',
				'America/Boise'						=>	'Boise',
				'America/Cambridge_Bay'				=>	'Cambridge Bay',
				'America/Campo_Grande'				=>	'Campo Grande',
				'America/Cancun'					=>	'Cancun',
				'America/Caracas'					=>	'Caracas',
				'America/Cayenne'					=>	'Cayenne',
				'America/Cayman'					=>	'Cayman',
				'America/Chicago'					=>	'Chicago',
				'America/Chihuahua'					=>	'Chihuahua',
				'America/Costa_Rica'				=>	'Costa Rica',
				'America/Creston'					=>	'Creston',
				'America/Cuiaba'					=>	'Cuiaba',
				'America/Curacao'					=>	'Curacao',
				'America/Danmarkshavn'				=>	'Danmarkshavn',
				'America/Dawson'					=>	'Dawson',
				'America/Dawson_Creek'				=>	'Dawson Creek',
				'America/Denver'					=>	'Denver',
				'America/Detroit'					=>	'Detroit',
				'America/Dominica'					=>	'Dominica',
				'America/Edmonton'					=>	'Edmonton',
				'America/Eirunepe'					=>	'Eirunepe',
				'America/El_Salvador'				=>	'El Salvador',
				'America/Fortaleza'					=>	'Fortaleza',
				'America/Glace_Bay'					=>	'Glace Bay',
				'America/Godthab'					=>	'Godthab',
				'America/Goose_Bay'					=>	'Goose Bay',
				'America/Grand_Turk'				=>	'Grand Turk',
				'America/Grenada'					=>	'Grenada',
				'America/Guadeloupe'				=>	'Guadeloupe',
				'America/Guatemala'					=>	'Guatemala',
				'America/Guayaquil'					=>	'Guayaquil',
				'America/Guyana'					=>	'Guyana',
				'America/Halifax'					=>	'Halifax',
				'America/Havana'					=>	'Havana',
				'America/Hermosillo'				=>	'Hermosillo',
				'America/Indiana/Indianapolis'		=>	'Indiana - Indianapolis',
				'America/Indiana/Knox'				=>	'Indiana - Knox',
				'America/Indiana/Marengo'			=>	'Indiana - Marengo',
				'America/Indiana/Petersburg'		=>	'Indiana - Petersburg',
				'America/Indiana/Tell_City'			=>	'Indiana - Tell City',
				'America/Indiana/Vevay'				=>	'Indiana - Vevay',
				'America/Indiana/Vincennes'			=>	'Indiana - Vincennes',
				'America/Indiana/Winamac'			=>	'Indiana - Winamac',
				'America/Inuvik'					=>	'Inuvik',
				'America/Iqaluit'					=>	'Iqaluit',
				'America/Jamaica'					=>	'Jamaica',
				'America/Juneau'					=>	'Juneau',
				'America/Kentucky/Louisville'		=>	'Kentucky - Louisville',
				'America/Kentucky/Monticello'		=>	'Kentucky - Monticello',
				'America/Kralendijk'				=>	'Kralendijk',
				'America/La_Paz'					=>	'La Paz',
				'America/Lima'						=>	'Lima',
				'America/Los_Angeles'				=>	'Los Angeles',
				'America/Lower_Princes'				=>	'Lower Princes',
				'America/Maceio'					=>	'Maceio',
				'America/Managua'					=>	'Managua',
				'America/Manaus'					=>	'Manaus',
				'America/Marigot'					=>	'Marigot',
				'America/Martinique'				=>	'Martinique',
				'America/Matamoros'					=>	'Matamoros',
				'America/Mazatlan'					=>	'Mazatlan',
				'America/Menominee'					=>	'Menominee',
				'America/Merida'					=>	'Merida',
				'America/Metlakatla'				=>	'Metlakatla',
				'America/Mexico_City'				=>	'Mexico City',
				'America/Miquelon'					=>	'Miquelon',
				'America/Moncton'					=>	'Moncton',
				'America/Monterrey'					=>	'Monterrey',
				'America/Montevideo'				=>	'Montevideo',
				'America/Montserrat'				=>	'Montserrat',
				'America/Nassau'					=>	'Nassau',
				'America/New_York'					=>	'New York',
				'America/Nipigon'					=>	'Nipigon',
				'America/Nome'						=>	'Nome',
				'America/Noronha'					=>	'Noronha',
				'America/North_Dakota/Beulah'		=>	'North Dakota - Beulah',
				'America/North_Dakota/Center'		=>	'North Dakota - Center',
				'America/North_Dakota/New_Salem'	=>	'North Dakota - New Salem',
				'America/Ojinaga'					=>	'Ojinaga',
				'America/Panama'					=>	'Panama',
				'America/Pangnirtung'				=>	'Pangnirtung',
				'America/Paramaribo'				=>	'Paramaribo',
				'America/Phoenix'					=>	'Phoenix',
				'America/Port-au-Prince'			=>	'Port-au-Prince',
				'America/Port_of_Spain'				=>	'Port of Spain',
				'America/Porto_Velho'				=>	'Porto Velho',
				'America/Puerto_Rico'				=>	'Puerto Rico',
				'America/Rainy_River'				=>	'Rainy River',
				'America/Rankin_Inlet'				=>	'Rankin Inlet',
				'America/Recife'					=>	'Recife',
				'America/Regina'					=>	'Regina',
				'America/Resolute'					=>	'Resolute',
				'America/Rio_Branco'				=>	'Rio Branco',
				'America/Santa_Isabel'				=>	'Santa Isabel',
				'America/Santarem'					=>	'Santarem',
				'America/Santiago'					=>	'Santiago',
				'America/Santo_Domingo'				=>	'Santo Domingo',
				'America/Sao_Paulo'					=>	'Sao Paulo',
				'America/Scoresbysund'				=>	'Scoresbysund',
				'America/Sitka'						=>	'Sitka',
				'America/St_Barthelemy'				=>	'St Barthelemy',
				'America/St_Johns'					=>	'St Johns',
				'America/St_Kitts'					=>	'St Kitts',
				'America/St_Lucia'					=>	'St Lucia',
				'America/St_Thomas'					=>	'St Thomas',
				'America/St_Vincent'				=>	'St Vincent',
				'America/Swift_Current'				=>	'Swift Current',
				'America/Tegucigalpa'				=>	'Tegucigalpa',
				'America/Thule'						=>	'Thule',
				'America/Thunder_Bay'				=>	'Thunder Bay',
				'America/Tijuana'					=>	'Tijuana',
				'America/Toronto'					=>	'Toronto',
				'America/Tortola'					=>	'Tortola',
				'America/Vancouver'					=>	'Vancouver',
				'America/Whitehorse'				=>	'Whitehorse',
				'America/Winnipeg'					=>	'Winnipeg',
				'America/Yakutat'					=>	'Yakutat',
				'America/Yellowknife'				=>	'Yellowknife'
			)
		),
		array(
			'zone'		=>	'Antarctica',
			'cities'	=>	array(
				'Antarctica/Casey'			=>	'Casey',
				'Antarctica/Davis'			=>	'Davis',
				'Antarctica/DumontDUrville'	=>	'DumontDUrville',
				'Antarctica/Macquarie'		=>	'Macquarie',
				'Antarctica/Mawson'			=>	'Mawson',
				'Antarctica/McMurdo'		=>	'McMurdo',
				'Antarctica/Palmer'			=>	'Palmer',
				'Antarctica/Rothera'		=>	'Rothera',
				'Antarctica/Syowa'			=>	'Syowa',
				'Antarctica/Troll'			=>	'Troll',
				'Antarctica/Vostok'			=>	'Vostok'
			)
		),
		array(
			'zone'		=>	'Arctic',
			'cities'	=>	array(
				'Arctic/Longyearbyen'		=>	'Longyearbyen'
			)
		),
		array(
			'zone'		=>	'Asia',
			'cities'	=>	array(
				'Asia/Aden'				=>	'Aden',
				'Asia/Almaty'			=>	'Almaty',
				'Asia/Amman'			=>	'Amman',
				'Asia/Anadyr'			=>	'Anadyr',
				'Asia/Aqtau'			=>	'Aqtau',
				'Asia/Aqtobe'			=>	'Aqtobe',
				'Asia/Ashgabat'			=>	'Ashgabat',
				'Asia/Baghdad'			=>	'Baghdad',
				'Asia/Bahrain'			=>	'Bahrain',
				'Asia/Baku'				=>	'Baku',
				'Asia/Bangkok'			=>	'Bangkok',
				'Asia/Beirut'			=>	'Beirut',
				'Asia/Bishkek'			=>	'Bishkek',
				'Asia/Brunei'			=>	'Brunei',
				'Asia/Chita'			=>	'Chita',
				'Asia/Choibalsan'		=>	'Choibalsan',
				'Asia/Colombo'			=>	'Colombo',
				'Asia/Damascus'			=>	'Damascus',
				'Asia/Dhaka'			=>	'Dhaka',
				'Asia/Dili'				=>	'Dili',
				'Asia/Dubai'			=>	'Dubai',
				'Asia/Dushanbe'			=>	'Dushanbe',
				'Asia/Gaza'				=>	'Gaza',
				'Asia/Hebron'			=>	'Hebron',
				'Asia/Ho_Chi_Minh'		=>	'Ho Chi Minh',
				'Asia/Hong_Kong'		=>	'Hong Kong',
				'Asia/Hovd'				=>	'Hovd',
				'Asia/Irkutsk'			=>	'Irkutsk',
				'Asia/Jakarta'			=>	'Jakarta',
				'Asia/Jayapura'			=>	'Jayapura',
				'Asia/Jerusalem'		=>	'Jerusalem',
				'Asia/Kabul'			=>	'Kabul',
				'Asia/Kamchatka'		=>	'Kamchatka',
				'Asia/Karachi'			=>	'Karachi',
				'Asia/Kathmandu'		=>	'Kathmandu',
				'Asia/Khandyga'			=>	'Khandyga',
				'Asia/Kolkata'			=>	'Kolkata',
				'Asia/Krasnoyarsk'		=>	'Krasnoyarsk',
				'Asia/Kuala_Lumpur'		=>	'Kuala Lumpur',
				'Asia/Kuching'			=>	'Kuching',
				'Asia/Kuwait'			=>	'Kuwait',
				'Asia/Macau'			=>	'Macau',
				'Asia/Magadan'			=>	'Magadan',
				'Asia/Makassar'			=>	'Makassar',
				'Asia/Manila'			=>	'Manila',
				'Asia/Muscat'			=>	'Muscat',
				'Asia/Nicosia'			=>	'Nicosia',
				'Asia/Novokuznetsk'		=>	'Novokuznetsk',
				'Asia/Novosibirsk'		=>	'Novosibirsk',
				'Asia/Omsk'				=>	'Omsk',
				'Asia/Oral'				=>	'Oral',
				'Asia/Phnom_Penh'		=>	'Phnom Penh',
				'Asia/Pontianak'		=>	'Pontianak',
				'Asia/Pyongyang'		=>	'Pyongyang',
				'Asia/Qatar'			=>	'Qatar',
				'Asia/Qyzylorda'		=>	'Qyzylorda',
				'Asia/Rangoon'			=>	'Rangoon',
				'Asia/Riyadh'			=>	'Riyadh',
				'Asia/Sakhalin'			=>	'Sakhalin',
				'Asia/Samarkand'		=>	'Samarkand',
				'Asia/Seoul'			=>	'Seoul',
				'Asia/Shanghai'			=>	'Shanghai',
				'Asia/Singapore'		=>	'Singapore',
				'Asia/Srednekolymsk'	=>	'Srednekolymsk',
				'Asia/Taipei'			=>	'Taipei',
				'Asia/Tashkent'			=>	'Tashkent',
				'Asia/Tbilisi'			=>	'Tbilisi',
				'Asia/Tehran'			=>	'Tehran',
				'Asia/Thimphu'			=>	'Thimphu',
				'Asia/Tokyo'			=>	'Tokyo',
				'Asia/Ulaanbaatar'		=>	'Ulaanbaatar',
				'Asia/Urumqi'			=>	'Urumqi',
				'Asia/Ust-Nera'			=>	'Ust-Nera',
				'Asia/Vientiane'		=>	'Vientiane',
				'Asia/Vladivostok'		=>	'Vladivostok',
				'Asia/Yakutsk'			=>	'Yakutsk',
				'Asia/Yekaterinburg'	=>	'Yekaterinburg',
				'Asia/Yerevan'			=>	'Yerevan'
			)
		),
		array(
			'zone'		=>	'Atlantic',
			'cities'	=>	array(
				'Atlantic/Azores'			=>	'Azores',
				'Atlantic/Bermuda'			=>	'Bermuda',
				'Atlantic/Canary'			=>	'Canary',
				'Atlantic/Cape_Verde'		=>	'Cape Verde',
				'Atlantic/Faroe'			=>	'Faroe',
				'Atlantic/Madeira'			=>	'Madeira',
				'Atlantic/Reykjavik'		=>	'Reykjavik',
				'Atlantic/South_Georgia'	=>	'South Georgia',
				'Atlantic/Stanley'			=>	'Stanley',
				'Atlantic/St_Helena'		=>	'St Helena'
			)
		),
		array(
			'zone'		=>	'Australia',
			'cities'	=>	array(
				'Australia/Adelaide'	=>	'Adelaide',
				'Australia/Brisbane'	=>	'Brisbane',
				'Australia/Broken_Hill'	=>	'Broken Hill',
				'Australia/Currie'		=>	'Currie',
				'Australia/Darwin'		=>	'Darwin',
				'Australia/Eucla'		=>	'Eucla',
				'Australia/Hobart'		=>	'Hobart',
				'Australia/Lindeman'	=>	'Lindeman',
				'Australia/Lord_Howe'	=>	'Lord Howe',
				'Australia/Melbourne'	=>	'Melbourne',
				'Australia/Perth'		=>	'Perth',
				'Australia/Sydney'		=>	'Sydney'
			)
		),
		array(
			'zone'		=>	'Europe',
			'cities'	=>	array(
				'Europe/Amsterdam'		=>	'Amsterdam',
				'Europe/Andorra'		=>	'Andorra',
				'Europe/Athens'			=>	'Athens',
				'Europe/Belgrade'		=>	'Belgrade',
				'Europe/Berlin'			=>	'Berlin',
				'Europe/Bratislava'		=>	'Bratislava',
				'Europe/Brussels'		=>	'Brussels',
				'Europe/Bucharest'		=>	'Bucharest',
				'Europe/Budapest'		=>	'Budapest',
				'Europe/Busingen'		=>	'Busingen',
				'Europe/Chisinau'		=>	'Chisinau',
				'Europe/Copenhagen'		=>	'Copenhagen',
				'Europe/Dublin'			=>	'Dublin',
				'Europe/Gibraltar'		=>	'Gibraltar',
				'Europe/Guernsey'		=>	'Guernsey',
				'Europe/Helsinki'		=>	'Helsinki',
				'Europe/Isle_of_Man'	=>	'Isle of Man',
				'Europe/Istanbul'		=>	'Istanbul',
				'Europe/Jersey'			=>	'Jersey',
				'Europe/Kaliningrad'	=>	'Kaliningrad',
				'Europe/Kiev'			=>	'Kiev',
				'Europe/Lisbon'			=>	'Lisbon',
				'Europe/Ljubljana'		=>	'Ljubljana',
				'Europe/London'			=>	'London',
				'Europe/Luxembourg'		=>	'Luxembourg',
				'Europe/Madrid'			=>	'Madrid',
				'Europe/Malta'			=>	'Malta',
				'Europe/Mariehamn'		=>	'Mariehamn',
				'Europe/Minsk'			=>	'Minsk',
				'Europe/Monaco'			=>	'Monaco',
				'Europe/Moscow'			=>	'Moscow',
				'Europe/Oslo'			=>	'Oslo',
				'Europe/Paris'			=>	'Paris',
				'Europe/Podgorica'		=>	'Podgorica',
				'Europe/Prague'			=>	'Prague',
				'Europe/Riga'			=>	'Riga',
				'Europe/Rome'			=>	'Rome',
				'Europe/Samara'			=>	'Samara',
				'Europe/San_Marino'		=>	'San Marino',
				'Europe/Sarajevo'		=>	'Sarajevo',
				'Europe/Simferopol'		=>	'Simferopol',
				'Europe/Skopje'			=>	'Skopje',
				'Europe/Sofia'			=>	'Sofia',
				'Europe/Stockholm'		=>	'Stockholm',
				'Europe/Tallinn'		=>	'Tallinn',
				'Europe/Tirane'			=>	'Tirane',
				'Europe/Uzhgorod'		=>	'Uzhgorod',
				'Europe/Vaduz'			=>	'Vaduz',
				'Europe/Vatican'		=>	'Vatican',
				'Europe/Vienna'			=>	'Vienna',
				'Europe/Vilnius'		=>	'Vilnius',
				'Europe/Volgograd'		=>	'Volgograd',
				'Europe/Warsaw'			=>	'Warsaw',
				'Europe/Zagreb'			=>	'Zagreb',
				'Europe/Zaporozhye'		=>	'Zaporozhye',
				'Europe/Zurich'			=>	'Zurich'
			)
		),
		array(
			'zone'		=>	'Indian',
			'cities'	=>	array(
				'Indian/Antananarivo'	=>	'Antananarivo',
				'Indian/Chagos'			=>	'Chagos',
				'Indian/Christmas'		=>	'Christmas',
				'Indian/Cocos'			=>	'Cocos',
				'Indian/Comoro'			=>	'Comoro',
				'Indian/Kerguelen'		=>	'Kerguelen',
				'Indian/Mahe'			=>	'Mahe',
				'Indian/Maldives'		=>	'Maldives',
				'Indian/Mauritius'		=>	'Mauritius',
				'Indian/Mayotte'		=>	'Mayotte',
				'Indian/Reunion'		=>	'Reunion'
			)
		),
		array(
			'zone'		=>	'Pacific',
			'cities'	=>	array(
				'Pacific/Apia'			=>	'Apia',
				'Pacific/Auckland'		=>	'Auckland',
				'Pacific/Chatham'		=>	'Chatham',
				'Pacific/Chuuk'			=>	'Chuuk',
				'Pacific/Easter'		=>	'Easter',
				'Pacific/Efate'			=>	'Efate',
				'Pacific/Enderbury'		=>	'Enderbury',
				'Pacific/Fakaofo'		=>	'Fakaofo',
				'Pacific/Fiji'			=>	'Fiji',
				'Pacific/Funafuti'		=>	'Funafuti',
				'Pacific/Galapagos'		=>	'Galapagos',
				'Pacific/Gambier'		=>	'Gambier',
				'Pacific/Guadalcanal'	=>	'Guadalcanal',
				'Pacific/Guam'			=>	'Guam',
				'Pacific/Honolulu'		=>	'Honolulu',
				'Pacific/Johnston'		=>	'Johnston',
				'Pacific/Kiritimati'	=>	'Kiritimati',
				'Pacific/Kosrae'		=>	'Kosrae',
				'Pacific/Kwajalein'		=>	'Kwajalein',
				'Pacific/Majuro'		=>	'Majuro',
				'Pacific/Marquesas'		=>	'Marquesas',
				'Pacific/Midway'		=>	'Midway',
				'Pacific/Nauru'			=>	'Nauru',
				'Pacific/Niue'			=>	'Niue',
				'Pacific/Norfolk'		=>	'Norfolk',
				'Pacific/Noumea'		=>	'Noumea',
				'Pacific/Pago_Pago'		=>	'Pago Pago',
				'Pacific/Palau'			=>	'Palau',
				'Pacific/Pitcairn'		=>	'Pitcairn',
				'Pacific/Pohnpei'		=>	'Pohnpei',
				'Pacific/Port_Moresby'	=>	'Port Moresby',
				'Pacific/Rarotonga'		=>	'Rarotonga',
				'Pacific/Saipan'		=>	'Saipan',
				'Pacific/Tahiti'		=>	'Tahiti',
				'Pacific/Tarawa'		=>	'Tarawa',
				'Pacific/Tongatapu'		=>	'Tongatapu',
				'Pacific/Wake'			=>	'Wake',
				'Pacific/Wallis'		=>	'Wallis'
			)
		)
	);


	/**
	 * @var array Eine Liste verfügbarer Font-Awesome-Icons
	 */
	public static $fontAwesomeIcons	=	array(
		'fa-adjust',
		'fa-anchor',
		'fa-archive',
		'fa-area-chart',
		'fa-arrows',
		'fa-arrows-h',
		'fa-arrows-v',
		'fa-asterisk',
		'fa-at',
		'fa-ban',
		'fa-bar-chart',
		'fa-barcode',
		'fa-bars',
		'fa-beer',
		'fa-bell',
		'fa-bell-o',
		'fa-bell-slash',
		'fa-bell-slash-o',
		'fa-bicycle',
		'fa-binoculars',
		'fa-birthday-cake',
		'fa-bolt',
		'fa-bomb',
		'fa-book',
		'fa-bookmark',
		'fa-bookmark-o',
		'fa-briefcase',
		'fa-bug',
		'fa-building',
		'fa-building-o',
		'fa-bullhorn',
		'fa-bullseye',
		'fa-bus',
		'fa-calculator',
		'fa-calendar',
		'fa-calendar-o',
		'fa-camera',
		'fa-camera-retro',
		'fa-car',
		'fa-caret-square-o-down',
		'fa-caret-square-o-left',
		'fa-caret-square-o-right',
		'fa-caret-square-o-up',
		'fa-cc',
		'fa-certificate',
		'fa-check',
		'fa-check-circle',
		'fa-check-circle-o',
		'fa-check-square',
		'fa-check-square-o',
		'fa-child',
		'fa-circle',
		'fa-circle-o',
		'fa-circle-o-notch',
		'fa-circle-thin',
		'fa-clock-o',
		'fa-cloud',
		'fa-cloud-download',
		'fa-cloud-upload',
		'fa-code',
		'fa-code-fork',
		'fa-coffee',
		'fa-cog',
		'fa-cogs',
		'fa-comment',
		'fa-comment-o',
		'fa-comments',
		'fa-comments-o',
		'fa-compass',
		'fa-copyright',
		'fa-credit-card',
		'fa-crop',
		'fa-crosshairs',
		'fa-cube',
		'fa-cubes',
		'fa-cutlery',
		'fa-database',
		'fa-desktop',
		'fa-dot-circle-o',
		'fa-download',
		'fa-ellipsis-h',
		'fa-ellipsis-v',
		'fa-envelope',
		'fa-envelope-o',
		'fa-envelope-square',
		'fa-eraser',
		'fa-exchange',
		'fa-exclamation',
		'fa-exclamation-circle',
		'fa-exclamation-triangle',
		'fa-external-link',
		'fa-external-link-square',
		'fa-eye',
		'fa-eye-slash',
		'fa-eyedropper',
		'fa-fax',
		'fa-female',
		'fa-fighter-jet',
		'fa-file-archive-o',
		'fa-file-audio-o',
		'fa-file-code-o',
		'fa-file-excel-o',
		'fa-file-image-o',
		'fa-file-pdf-o',
		'fa-file-powerpoint-o',
		'fa-file-video-o',
		'fa-file-word-o',
		'fa-film',
		'fa-filter',
		'fa-fire',
		'fa-fire-extinguisher',
		'fa-flag',
		'fa-flag-checkered',
		'fa-flag-o',
		'fa-flask',
		'fa-folder',
		'fa-folder-o',
		'fa-folder-open',
		'fa-folder-open-o',
		'fa-frown-o',
		'fa-futbol-o',
		'fa-gamepad',
		'fa-gavel',
		'fa-gift',
		'fa-glass',
		'fa-globe',
		'fa-graduation-cap',
		'fa-hdd-o',
		'fa-headphones',
		'fa-heart',
		'fa-heart-o',
		'fa-history',
		'fa-home',
		'fa-inbox',
		'fa-info',
		'fa-info-circle',
		'fa-key',
		'fa-keyboard-o',
		'fa-language',
		'fa-laptop',
		'fa-leaf',
		'fa-lemon-o',
		'fa-level-down',
		'fa-level-up',
		'fa-life-ring',
		'fa-lightbulb-o',
		'fa-line-chart',
		'fa-location-arrow',
		'fa-lock',
		'fa-magic',
		'fa-magnet',
		'fa-male',
		'fa-map-marker',
		'fa-meh-o',
		'fa-microphone',
		'fa-microphone-slash',
		'fa-minus',
		'fa-minus-circle',
		'fa-minus-square',
		'fa-minus-square-o',
		'fa-mobile',
		'fa-money',
		'fa-moon-o',
		'fa-music',
		'fa-newspaper-o',
		'fa-paint-brush',
		'fa-paper-plane',
		'fa-paper-plane-o',
		'fa-paw',
		'fa-pencil',
		'fa-pencil-square',
		'fa-pencil-square-o',
		'fa-phone',
		'fa-phone-square',
		'fa-picture-o',
		'fa-pie-chart',
		'fa-plane',
		'fa-plug',
		'fa-plus',
		'fa-plus-circle',
		'fa-plus-square',
		'fa-plus-square-o',
		'fa-power-off',
		'fa-print',
		'fa-puzzle-piece',
		'fa-qrcode',
		'fa-question',
		'fa-question-circle',
		'fa-quote-left',
		'fa-quote-right',
		'fa-random',
		'fa-recycle',
		'fa-refresh',
		'fa-reply',
		'fa-reply-all',
		'fa-retweet',
		'fa-road',
		'fa-rocket',
		'fa-rss',
		'fa-rss-square',
		'fa-search',
		'fa-search-minus',
		'fa-search-plus',
		'fa-share',
		'fa-share-alt',
		'fa-share-alt-square',
		'fa-share-square',
		'fa-share-square-o',
		'fa-shield',
		'fa-shopping-cart',
		'fa-sign-in',
		'fa-sign-out',
		'fa-signal',
		'fa-sitemap',
		'fa-sliders',
		'fa-smile-o',
		'fa-sort',
		'fa-sort-alpha-asc',
		'fa-sort-alpha-desc',
		'fa-sort-amount-asc',
		'fa-sort-amount-desc',
		'fa-sort-asc',
		'fa-sort-desc',
		'fa-sort-numeric-asc',
		'fa-sort-numeric-desc',
		'fa-space-shuttle',
		'fa-spinner',
		'fa-spoon',
		'fa-square',
		'fa-square-o',
		'fa-star',
		'fa-star-half',
		'fa-star-half-o',
		'fa-star-o',
		'fa-suitcase',
		'fa-sun-o',
		'fa-tablet',
		'fa-tachometer',
		'fa-tag',
		'fa-tags',
		'fa-tasks',
		'fa-taxi',
		'fa-terminal',
		'fa-thumb-tack',
		'fa-thumbs-down',
		'fa-thumbs-o-down',
		'fa-thumbs-o-up',
		'fa-thumbs-up',
		'fa-ticket',
		'fa-times',
		'fa-times-circle',
		'fa-times-circle-o',
		'fa-tint',
		'fa-toggle-off',
		'fa-toggle-on',
		'fa-trash',
		'fa-trash-o',
		'fa-tree',
		'fa-trophy',
		'fa-truck',
		'fa-tty',
		'fa-umbrella',
		'fa-university',
		'fa-unlock',
		'fa-unlock-alt',
		'fa-upload',
		'fa-user',
		'fa-users',
		'fa-video-camera',
		'fa-volume-down',
		'fa-volume-off',
		'fa-volume-up',
		'fa-wheelchair',
		'fa-wifi',
		'fa-wrench',
	);


	/**
	 * Kürzen den String nach einer Anzahl von Wörtern
	 *
	 * @param string $str Der zu kürzende String
	 * @param int $limit Maximale Anzahl an Wörtern im String die erlaubt sein sollen. Standartmäßig 100
	 * @param string $suffix Ein String der nach der kürzung anschließend angehangen wird. Standartmäßig "&#8230;"
	 * @return string Gibt den gekürzten String zurück
	 */
	public static function word_limiter($str, $limit = 100, $suffix = '&#8230;')
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'text', 'wordLimiter', array($str, $limit, $suffix));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(empty(trim($str)))
		{
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,'.(int)$limit.'}/', $str, $matches);

		if(strlen($str) == strlen($matches[0]))
		{
			$suffix	=	'';
		}

		$back	=	rtrim($matches[0]).$suffix;

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'text', 'wordLimiter', array($back));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $back;
	}

	/**
	 * Kürzt einen Text auf die vorgegebene Länge.
	 *
	 * @param string $string Der zu kürzende String.
	 * @param int $limit Die maximale länge des Strings die es haben soll.
	 * @param string $suffix Der String soll am Ende einen weiteren String bekommen. Standartmäßig "..."
	 * @return string Gibt den gekürzten String zurück
	 */
	public static function truncate($string, $limit, $suffix = "...")
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'template', 'truncate', array($string, $limit, $suffix));

			if($plugin != null)
			{
				return $plugin;
			}
		}

	  	$len = strlen($string);

		if($len > $limit)
		{
			return substr($string, 0, $limit).$suffix;
		}
		else
		{
			return $string;
		}
	}


	/**
	 * Kürzt den String nach einer Anzahl von Zeichen
	 *
	 * @param string $str Den zu kürzenden String.
	 * @param int $n Die Anzahl an Zeichen nachdem der Rest abgeschnitten/gekürzt werden soll. Standartmäßig 500
	 * @param string $suffix Ein String der nach der kürzung angehangen werden soll. Standartmäßig "&#8230"
	 * @return string Gibt den gekürzten String zurück
	 */
	public static function character_limiter($str, $n = 500, $suffix = '&#8230;')
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'text', 'characterLimiter', array($str, $n, $suffix));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(strlen($str) < $n)
		{
			return $str;
		}

		$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if(strlen($str) <= $n)
		{
			return $str;
		}

		$out 		= 	'';
		$exploder	=	explode(' ', trim($str));

		foreach($exploder as $val)
		{
			$out .= $val.' ';

			if(strlen($out) >= $n)
			{
				$out 	= 	trim($out);
				$back	=	(strlen($out) == strlen($str)) ? $out : $out.$suffix;

				if(class_exists('\package\plugins') === true)
				{
					$plugin	=	plugins::hookCall('after', 'text', 'characterLimiter', array($back));

					if($plugin != null)
					{
						return $plugin;
					}
				}

				return $back;
			}
		}

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'text', 'characterLimiter', array(''));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return '';
	}


	/**
	 * Zensiert Wörter in einem String
	 *
	 * @param string $str Der String der Wörter enthält die Zentriert werden sollen.
	 * @param array $censored Ein assoziatives Array das die zu zensierenden Wörter enthält
	 * @param string $replacement Der String der die zu zensierenden Wörte einnehmen soll. Standartmäßig ''
	 * @return string Gibt den zensierten String zurück
	 */
	public static function word_censor($str, $censored, $replacement = '')
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'text', 'wordCensor', array($str, $censored, $replacement));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(is_array($censored) === false || empty($censored))
		{
			return $str;
		}

		$str = ' '.$str.' ';

		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach($censored as $badword)
		{
			if(empty($replacement) === false)
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/i", "\\1{$replacement}\\3", $str);
			}
			else
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'text', 'wordCensor', array($str));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return trim($str);
	}


	/**
	 * Highlightet Codefelder
	 *
	 * @param string $str Der zu hervorhebende String.
	 * @return mixed|string Gibt den String zurück.
	 */
	public static function highlight_code($str)
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'text', 'highlightCode', array($str));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

		$str = str_replace(array('<?', '?>', '<%', '%>', '\\', '</script>'),
							array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), $str);

		$str = '<?php '.$str.' ?>'; // <?

		$str = highlight_string($str, true);

		$str = preg_replace('/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i', '<span style="color: #$1">', $str);
		$str = preg_replace('/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is', "$1</span>\n</span>\n</code>", $str);
		$str = preg_replace('/<span style="color: #[A-Z0-9]+"\><\/span>/i', '', $str);

		$str = str_replace(array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'),
							array('&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'), $str);

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'text', 'highlightCode', array($str));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $str;
	}


	/**
	 * Highlitet einen bestimmten Textausschnitt
	 *
	 * @param $str
	 * @param $phrase
	 * @param string $tag_open
	 * @param string $tag_close
	 * @return mixed|string
	 */
	public static function highlight_phrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'text', 'highlightPhrase', array($str, $phrase, $tag_open, $tag_close));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(empty($str))
		{
			return '';
		}

		if(empty($phrase) === false)
		{
			return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);
		}

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'text', 'highlightPhrase', array($str));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $str;
	}


	/**
	 * Gibt die Liste aller Sprachpakete zurück
	 *
	 * @return array
	 */
	public static function getLocale()
	{
		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('before', 'text', 'getLocale');

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(OS == 'WIN')
		{
			$plugin	=	plugins::hookCall('after', 'text', 'getLocale', array(self::$allLocales));

			if($plugin != null)
			{
				return $plugin;
			}

			return self::$allLocales;
		}

		$locale_data 	= 	array();

		$locales	=	shell_exec('locale -a');
		$locales 	= 	explode("\n" , $locales);

		foreach($locales as $l)
		{
			$l	=	trim($l);

			if(empty($l) || $l == 'POSIX' || $l == 'C')
			{
				continue;
			}

			if(strlen($l))
			{
				$locale_data[]	=	$l;
			}
		}

		if(class_exists('\package\plugins') === true)
		{
			$plugin	=	plugins::hookCall('after', 'text', 'getLocale', array($locale_data));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $locale_data;
	}


	/**
	 * Gibt ein Zufallsstring zurück
	 *
	 * @param string $type
	 * @param int $length
	 *
	 * @return string
	 */
	public function random_string($type = 'normal', $length = 10)
	{
		switch ($type)
		{
			default:
			case 'normal':

				$back	=	uniqid(mt_rand(), true);

			break;
			case 'alnum':

				$back 	= 	'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

			break;
			case 'numeric':

				$back	= 	'0123456789';

			break;
			case 'nozero':

				$back 	= 	'123456789';

			break;
			case 'alpha':

				$back 	= 	'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

			break;
			break;
			case 'md5':

				$back	=	md5(uniqid(mt_rand(), true));

			break;
			case 'sha1':

				$back	=	sha1(uniqid(mt_rand(), true));

			break;
		}

		return substr(str_shuffle(str_repeat($back, ceil($length / strlen($back)))), 0, $length);
	}


	/**
	 * Entfernt Doppelte Slashes aus einem String
	 *
	 * Beispiel
	 *
	 * http://www.google.de//meineSuche
	 *
	 * wird
	 *
	 * http://www.google.de/meineSuche
	 *
	 * @param string $str
	 * @return string
	 */
	public static function reduce_double_slashes($str)
	{
		return preg_replace('#(^|[^:])//+#', '\\1/', $str);
	}


	/**
	 * Entfernt einfache und Doppelte Anführungszeichen aus einem String
	 *
	 * @param string $str
	 * @return string
	 */
	public static function strip_quotes($str)
	{
		return str_replace(array('"', "'"), array('', ''), $str);
	}


	/**
	 * Entfernt am Anfang und am Ende Slahes aus einem String
	 *
	 * @param string $str
	 * @return string
	 */
	public static function trim_slashes($str)
	{
		return trim($str, '/');
	}
}