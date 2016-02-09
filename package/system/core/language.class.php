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

use package\implement\IStatic;

/**
 * Übersetzt Begriffe
 *
 * Mit der language Klasse können einfach Begrifflichkeiten in andere Sprachen übersetzt werden.
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       language
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class language implements IStatic
{
	private static $userLng, $lngPath, $defaultLng = 'de_DE', $loadLanguageFile;

	/**
	 * @var array Alle erlaubten locales
	 */
	public static $allLocales = ['aa_DJ' => 'Afar (Djibouti)', 'aa_ER' => 'Afar (Eritrea)', 'aa_ET' => 'Afar (Ethiopia)', 'af_ZA' => 'Afrikaans (South Africa)', 'sq_AL' => 'Albanian (Albania)', 'sq_MK' => 'Albanian (Macedonia)', 'am_ET' => 'Amharic (Ethiopia)', 'ar_DZ' => 'Arabic (Algeria)', 'ar_BH' => 'Arabic (Bahrain)', 'ar_EG' => 'Arabic (Egypt)', 'ar_IN' => 'Arabic (India)', 'ar_IQ' => 'Arabic (Iraq)', 'ar_JO' => 'Arabic (Jordan)', 'ar_KW' => 'Arabic (Kuwait)', 'ar_LB' => 'Arabic (Lebanon)', 'ar_LY' => 'Arabic (Libya)', 'ar_MA' => 'Arabic (Morocco)', 'ar_OM' => 'Arabic (Oman)', 'ar_QA' => 'Arabic (Qatar)', 'ar_SA' => 'Arabic (Saudi Arabia)', 'ar_SD' => 'Arabic (Sudan)', 'ar_SY' => 'Arabic (Syria)', 'ar_TN' => 'Arabic (Tunisia)', 'ar_AE' => 'Arabic (United Arab Emirates)', 'ar_YE' => 'Arabic (Yemen)', 'an_ES' => 'Aragonese (Spain)', 'hy_AM' => 'Armenian (Armenia)', 'as_IN' => 'Assamese (India)', 'ast_ES' => 'Asturian (Spain)', 'az_AZ' => 'Azerbaijani (Azerbaijan)', 'az_TR' => 'Azerbaijani (Turkey)', 'eu_FR' => 'Basque (France)', 'eu_ES' => 'Basque (Spain)', 'be_BY' => 'Belarusian (Belarus)', 'bem_ZM' => 'Bemba (Zambia)', 'bn_BD' => 'Bengali (Bangladesh)', 'bn_IN' => 'Bengali (India)', 'ber_DZ' => 'Berber (Algeria)', 'ber_MA' => 'Berber (Morocco)', 'byn_ER' => 'Blin (Eritrea)', 'bs_BA' => 'Bosnian (Bosnia and Herzegovina)', 'br_FR' => 'Breton (France)', 'bg_BG' => 'Bulgarian (Bulgaria)', 'my_MM' => 'Burmese (Myanmar [Burma])', 'ca_AD' => 'Catalan (Andorra)', 'ca_FR' => 'Catalan (France)', 'ca_IT' => 'Catalan (Italy)', 'ca_ES' => 'Catalan (Spain)', 'zh_CN' => 'Chinese (China)', 'zh_HK' => 'Chinese (Hong Kong SAR China)', 'zh_SG' => 'Chinese (Singapore)', 'zh_TW' => 'Chinese (Taiwan)', 'cv_RU' => 'Chuvash (Russia)', 'kw_GB' => 'Cornish (United Kingdom)', 'crh_UA' => 'Crimean Turkish (Ukraine)', 'hr_HR' => 'Croatian (Croatia)', 'cs_CZ' => 'Czech (Czech Republic)', 'da_DK' => 'Danish (Denmark)', 'dv_MV' => 'Divehi (Maldives)', 'nl_AW' => 'Dutch (Aruba)', 'nl_BE' => 'Dutch (Belgium)', 'nl_NL' => 'Dutch (Netherlands)', 'dz_BT' => 'Dzongkha (Bhutan)', 'en_AG' => 'English (Antigua and Barbuda)', 'en_AU' => 'English (Australia)', 'en_BW' => 'English (Botswana)', 'en_CA' => 'English (Canada)', 'en_DK' => 'English (Denmark)', 'en_HK' => 'English (Hong Kong SAR China)', 'en_IN' => 'English (India)', 'en_IE' => 'English (Ireland)', 'en_NZ' => 'English (New Zealand)', 'en_NG' => 'English (Nigeria)', 'en_PH' => 'English (Philippines)', 'en_SG' => 'English (Singapore)', 'en_ZA' => 'English (South Africa)', 'en_GB' => 'English (United Kingdom)', 'en_US' => 'English (United States)', 'en_ZM' => 'English (Zambia)', 'en_ZW' => 'English (Zimbabwe)', 'eo' => 'Esperanto', 'et_EE' => 'Estonian (Estonia)', 'fo_FO' => 'Faroese (Faroe Islands)', 'fil_PH' => 'Filipino (Philippines)', 'fi_FI' => 'Finnish (Finland)', 'fr_BE' => 'French (Belgium)', 'fr_CA' => 'French (Canada)', 'fr_FR' => 'French (France)', 'fr_LU' => 'French (Luxembourg)', 'fr_CH' => 'French (Switzerland)', 'fur_IT' => 'Friulian (Italy)', 'ff_SN' => 'Fulah (Senegal)', 'gl_ES' => 'Galician (Spain)', 'lg_UG' => 'Ganda (Uganda)', 'gez_ER' => 'Geez (Eritrea)', 'gez_ET' => 'Geez (Ethiopia)', 'ka_GE' => 'Georgian (Georgia)', 'de_AT' => 'German (Austria)', 'de_BE' => 'German (Belgium)', 'de_DE' => 'German (Germany)', 'de_LI' => 'German (Liechtenstein)', 'de_LU' => 'German (Luxembourg)', 'de_CH' => 'German (Switzerland)', 'el_CY' => 'Greek (Cyprus)', 'el_GR' => 'Greek (Greece)', 'gu_IN' => 'Gujarati (India)', 'ht_HT' => 'Haitian (Haiti)', 'ha_NG' => 'Hausa (Nigeria)', 'iw_IL' => 'Hebrew (Israel)', 'he_IL' => 'Hebrew (Israel)', 'hi_IN' => 'Hindi (India)', 'hu_HU' => 'Hungarian (Hungary)', 'is_IS' => 'Icelandic (Iceland)', 'ig_NG' => 'Igbo (Nigeria)', 'id_ID' => 'Indonesian (Indonesia)', 'ia' => 'Interlingua', 'iu_CA' => 'Inuktitut (Canada)', 'ik_CA' => 'Inupiaq (Canada)', 'ga_IE' => 'Irish (Ireland)', 'it_IT' => 'Italian (Italy)', 'it_CH' => 'Italian (Switzerland)', 'ja_JP' => 'Japanese (Japan)', 'kl_GL' => 'Kalaallisut (Greenland)', 'kn_IN' => 'Kannada (India)', 'ks_IN' => 'Kashmiri (India)', 'csb_PL' => 'Kashubian (Poland)', 'kk_KZ' => 'Kazakh (Kazakhstan)', 'km_KH' => 'Khmer (Cambodia)', 'rw_RW' => 'Kinyarwanda (Rwanda)', 'ky_KG' => 'Kirghiz (Kyrgyzstan)', 'kok_IN' => 'Konkani (India)', 'ko_KR' => 'Korean (South Korea)', 'ku_TR' => 'Kurdish (Turkey)', 'lo_LA' => 'Lao (Laos)', 'lv_LV' => 'Latvian (Latvia)', 'li_BE' => 'Limburgish (Belgium)', 'li_NL' => 'Limburgish (Netherlands)', 'lt_LT' => 'Lithuanian (Lithuania)', 'nds_DE' => 'Low German (Germany)', 'nds_NL' => 'Low German (Netherlands)', 'mk_MK' => 'Macedonian (Macedonia)', 'mai_IN' => 'Maithili (India)', 'mg_MG' => 'Malagasy (Madagascar)', 'ms_MY' => 'Malay (Malaysia)', 'ml_IN' => 'Malayalam (India)', 'mt_MT' => 'Maltese (Malta)', 'gv_GB' => 'Manx (United Kingdom)', 'mi_NZ' => 'Maori (New Zealand)', 'mr_IN' => 'Marathi (India)', 'mn_MN' => 'Mongolian (Mongolia)', 'ne_NP' => 'Nepali (Nepal)', 'se_NO' => 'Northern Sami (Norway)', 'nso_ZA' => 'Northern Sotho (South Africa)', 'nb_NO' => 'Norwegian Bokmål (Norway)', 'nn_NO' => 'Norwegian Nynorsk (Norway)', 'oc_FR' => 'Occitan (France)', 'or_IN' => 'Oriya (India)', 'om_ET' => 'Oromo (Ethiopia)', 'om_KE' => 'Oromo (Kenya)', 'os_RU' => 'Ossetic (Russia)', 'pap_AN' => 'Papiamento (Netherlands Antilles)', 'ps_AF' => 'Pashto (Afghanistan)', 'fa_IR' => 'Persian (Iran)', 'pl_PL' => 'Polish (Poland)', 'pt_BR' => 'Portuguese (Brazil)', 'pt_PT' => 'Portuguese (Portugal)', 'pa_IN' => 'Punjabi (India)', 'pa_PK' => 'Punjabi (Pakistan)', 'ro_RO' => 'Romanian (Romania)', 'ru_RU' => 'Russian (Russia)', 'ru_UA' => 'Russian (Ukraine)', 'sa_IN' => 'Sanskrit (India)', 'sc_IT' => 'Sardinian (Italy)', 'gd_GB' => 'Scottish Gaelic (United Kingdom)', 'sr_ME' => 'Serbian (Montenegro)', 'sr_RS' => 'Serbian (Serbia)', 'sid_ET' => 'Sidamo (Ethiopia)', 'sd_IN' => 'Sindhi (India)', 'si_LK' => 'Sinhala (Sri Lanka)', 'sk_SK' => 'Slovak (Slovakia)', 'sl_SI' => 'Slovenian (Slovenia)', 'so_DJ' => 'Somali (Djibouti)', 'so_ET' => 'Somali (Ethiopia)', 'so_KE' => 'Somali (Kenya)', 'so_SO' => 'Somali (Somalia)', 'nr_ZA' => 'South Ndebele (South Africa)', 'st_ZA' => 'Southern Sotho (South Africa)', 'es_AR' => 'Spanish (Argentina)', 'es_BO' => 'Spanish (Bolivia)', 'es_CL' => 'Spanish (Chile)', 'es_CO' => 'Spanish (Colombia)', 'es_CR' => 'Spanish (Costa Rica)', 'es_DO' => 'Spanish (Dominican Republic)', 'es_EC' => 'Spanish (Ecuador)', 'es_SV' => 'Spanish (El Salvador)', 'es_GT' => 'Spanish (Guatemala)', 'es_HN' => 'Spanish (Honduras)', 'es_MX' => 'Spanish (Mexico)', 'es_NI' => 'Spanish (Nicaragua)', 'es_PA' => 'Spanish (Panama)', 'es_PY' => 'Spanish (Paraguay)', 'es_PE' => 'Spanish (Peru)', 'es_ES' => 'Spanish (Spain)', 'es_US' => 'Spanish (United States)', 'es_UY' => 'Spanish (Uruguay)', 'es_VE' => 'Spanish (Venezuela)', 'sw_KE' => 'Swahili (Kenya)', 'sw_TZ' => 'Swahili (Tanzania)', 'ss_ZA' => 'Swati (South Africa)', 'sv_FI' => 'Swedish (Finland)', 'sv_SE' => 'Swedish (Sweden)', 'tl_PH' => 'Tagalog (Philippines)', 'tg_TJ' => 'Tajik (Tajikistan)', 'ta_IN' => 'Tamil (India)', 'tt_RU' => 'Tatar (Russia)', 'te_IN' => 'Telugu (India)', 'th_TH' => 'Thai (Thailand)', 'bo_CN' => 'Tibetan (China)', 'bo_IN' => 'Tibetan (India)', 'tig_ER' => 'Tigre (Eritrea)', 'ti_ER' => 'Tigrinya (Eritrea)', 'ti_ET' => 'Tigrinya (Ethiopia)', 'ts_ZA' => 'Tsonga (South Africa)', 'tn_ZA' => 'Tswana (South Africa)', 'tr_CY' => 'Turkish (Cyprus)', 'tr_TR' => 'Turkish (Turkey)', 'tk_TM' => 'Turkmen (Turkmenistan)', 'ug_CN' => 'Uighur (China)', 'uk_UA' => 'Ukrainian (Ukraine)', 'hsb_DE' => 'Upper Sorbian (Germany)', 'ur_PK' => 'Urdu (Pakistan)', 'uz_UZ' => 'Uzbek (Uzbekistan)', 've_ZA' => 'Venda (South Africa)', 'vi_VN' => 'Vietnamese (Vietnam)', 'wa_BE' => 'Walloon (Belgium)', 'cy_GB' => 'Welsh (United Kingdom)', 'fy_DE' => 'Western Frisian (Germany)', 'fy_NL' => 'Western Frisian (Netherlands)', 'wo_SN' => 'Wolof (Senegal)', 'xh_ZA' => 'Xhosa (South Africa)', 'yi_US' => 'Yiddish (United States)', 'yo_NG' => 'Yoruba (Nigeria)', 'zu_ZA' => 'Zulu (South Africa)'];

	/**
	 * Setzt die Standard Werte
	 *
	 * language constructor.
	 */
	public static function init()
	{
		if(empty(LANGUAGE_PATH) === false)
		{
			self::set_language_path(LANGUAGE_PATH);
		}

		if(empty(DEFAULT_LANGUAGE) === false)
		{
			self::set_default_language(DEFAULT_LANGUAGE);
		}

		self::load_lang(true);
	}

	/**
	 * Setzt die Benutzer Sprache
	 *
	 * @param string $lng Der Sprachcode
	 *
	 * @return void
	 */
	public static function set_language($lng)
	{
		self::$userLng = $lng;
		self::load_lang(true);
	}

	/**
	 * Gibt die Benutzer Sprache zurück
	 *
	 * @return mixed
	 */
	public static function get_language()
	{
		return self::$userLng;
	}

	/**
	 * Setzt den Sprach-Haupt-Ordner
	 *
	 * @param string $path Der Pfad zu den language Dateien
	 *
	 * @return void
	 */
	public static function set_language_path($path)
	{
		self::$lngPath = $path;
	}

	/**
	 * Gibt den Sprach-Haupt-Ordner zurück
	 *
	 * @return mixed
	 */
	public static function get_language_path()
	{
		return self::$lngPath;
	}

	/**
	 * Setzt die Standard Sprache
	 *
	 * @param string $lng Der Sprachcode
	 *
	 * @return void
	 */
	public static function set_default_language($lng)
	{
		self::$defaultLng = $lng;
	}

	/**
	 * Gibt die Standard Sprache zurück
	 *
	 * @return string
	 */
	public static function get_default_language()
	{
		return self::$defaultLng;
	}

	/**
	 * Lädt die Sprachfunktionalität
	 *
	 * @param bool $ignoreFileExists Soll ignorieren ob die Sprachdatei existiert oder nicht. Standartmäßig false.
	 *
	 * @return boolean
	 */
	public static function load_lang($ignoreFileExists = false)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'language', 'loadLang', [$ignoreFileExists]);
			$plugins = plugins::hookCall('before', 'language', 'loadLang', [$ignoreFileExists]);

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		if(empty(self::$lngPath) === true)
		{
			return false;
		}

		if(empty(self::$userLng) === true)
		{
			self::$userLng = self::$defaultLng;
		}

		putenv('LC_ALL='.self::$userLng);
		setlocale(LC_ALL, self::$userLng);

		bindtextdomain(self::$userLng, self::$lngPath);

		$folderName = explode('.', self::$userLng);

		$moFile = self::$lngPath.$folderName[0].SEP.'LC_MESSAGES'.SEP.self::$userLng.'.mo';

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'language', 'loadLang', [$moFile]);
			$plugins = plugins::hookCall('after', 'language', 'loadLang', [$moFile]);

			if($plugins != null)
			{
				return (bool)$plugins;
			}
		}

		return true;
	}

	/**
	 * Tauscht die Variablen aus
	 *
	 * @param string $text Der String der übersetzt/ersetzt werden soll
	 *
	 * @return mixed Gibt den übersetzten String zurück
	 */
	public static function translate($text)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'language', 'translate', [$text]);
			$plugins = plugins::hookCall('before', 'language', 'translate', [$text]);

			if($plugins != null)
			{
				return $plugins;
			}
		}

		textdomain(self::$userLng);

		return gettext($text);
	}


	/**
	 * Gibt die Liste aller Sprachpakete zurück
	 *
	 * @return array Gibt alle Sprachcodes, die auf dem Server installiert sind zurück. Geht nur bei UNIX Systemen
	 */
	public static function getAllSystemLocales()
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'language', 'getAllSystemLocales');

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(OS === 'WIN')
		{
			if(class_exists('\package\core\plugins') === true)
			{
				$plugin = plugins::hookCall('after', 'language', 'getAllSystemLocales', [self::$allLocales]);

				if($plugin != null)
				{
					return $plugin;
				}
			}

			return self::$allLocales;
		}

		$locale_data = [];

		$locales = shell_exec('locale -a');
		$locales = explode("\n", $locales);

		foreach($locales as $l)
		{
			$l = trim($l);

			if(empty($l) === true || $l === 'POSIX' || $l === 'C')
			{
				continue;
			}

			if(strlen($l) > 0)
			{
				$locale_data[] = $l;
			}
		}

		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('after', 'language', 'getAllSystemLocales', [$locale_data]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return $locale_data;
	}
}