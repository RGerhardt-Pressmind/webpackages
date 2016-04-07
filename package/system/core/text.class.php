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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\implement\IStatic;
use package\system\core\initiator;

/**
 * Textmanipulationen
 *
 * Wenn man einen bestimmten Satz kürzen möchte oder ein zufälligen String zurück haben möchte, kann man die text
 * Klasse nutzen.
 *
 * @method static string word_limiter(string $str, $limit = 100, $suffix = '...')
 * @method static string truncate(string $string, int $limit, $suffix = '...')
 * @method static string word_censor(string $str, array $censored, $replacement = '')
 * @method static mixed|string highlight_code(string $str)
 * @method static string random_string($type = 'normal', $length = 10)
 * @method static string reduce_double_slashes(string $str)
 * @method static string strip_quotes(string $str)
 * @method static string trim_slashes(string $str)
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       text
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class text extends initiator implements IStatic
{
	/**
	 * Zum initiailisieren von Daten
	 */
	public static function init()
	{
	}

	/**
	 * Kürzen den String nach einer Anzahl von Wörtern
	 *
	 * @param string $str    Der zu kürzende String
	 * @param int    $limit  Maximale Anzahl an Wörtern im String die erlaubt sein sollen. Standartmäßig 100
	 * @param string $suffix Ein String der nach der kürzung anschließend angehangen wird. Standartmäßig "&#8230;"
	 *
	 * @return string Gibt den gekürzten String zurück
	 */
	protected static function _word_limiter($str, $limit = 100, $suffix = '...')
	{
		if(trim($str) == '')
		{
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,'.(int)$limit.'}/', $str, $matches);

		if(strlen($str) == strlen($matches[0]))
		{
			$suffix = '';
		}

		$back = rtrim($matches[0]).$suffix;

		return $back;
	}

	/**
	 * Kürzt einen Text auf die vorgegebene Länge.
	 *
	 * @param string $string Der zu kürzende String.
	 * @param int    $limit  Die maximale länge des Strings die es haben soll.
	 * @param string $suffix Der String soll am Ende einen weiteren String bekommen. Standartmäßig "..."
	 *
	 * @return string Gibt den gekürzten String zurück
	 */
	protected static function _truncate($string, $limit, $suffix = '...')
	{
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
	 * Zensiert Wörter in einem String
	 *
	 * @param string $str         Der String der Wörter enthält die Zentriert werden sollen.
	 * @param array  $censored    Ein Array das die zu zensierenden Wörter enthält
	 * @param string $replacement Der String der die zu zensierenden Wörte einnehmen soll. Standartmäßig ''
	 *
	 * @return string Gibt den zensierten String zurück
	 */
	protected static function _word_censor($str, $censored, $replacement = '')
	{
		if(empty($censored))
		{
			return $str;
		}

		$str = ' '.$str.' ';

		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach($censored as $badword)
		{
			if(!empty($replacement))
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/i", "\\1{$replacement}\\3", $str);
			}
			else
			{
				$str = preg_replace("/({$delim})(".str_replace('\*', '\w*?', preg_quote($badword, '/')).")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

		return trim($str);
	}

	/**
	 * Highlightet Codefelder
	 *
	 * @param string $str Der zu hervorhebende String.
	 *
	 * @return mixed|string Gibt den String zurück.
	 */
	protected static function _highlight_code($str)
	{
		$str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

		$str = str_replace(array('<?', '?>', '<%', '%>', '\\', '</script>'), array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), $str);

		$str = '<?php '.$str.' ?>'; // <?

		$str = highlight_string($str, true);

		$str = preg_replace('/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i', '<span style="color: #$1">', $str);
		$str = preg_replace('/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is', "$1</span>\n</span>\n</code>", $str);
		$str = preg_replace('/<span style="color: #[A-Z0-9]+"\><\/span>/i', '', $str);

		$str = str_replace(array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'), array('&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'), $str);

		return $str;
	}

	/**
	 * Gibt ein Zufallsstring zurück
	 *
	 * @param string $type
	 * @param int    $length
	 *
	 * @return string
	 */
	protected static function _random_string($type = 'normal', $length = 10)
	{
		if($type == 'alnum')
		{
			$back = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		elseif($type == 'numeric')
		{
			$back = '0123456789';
		}
		elseif($type == 'nozero')
		{
			$back = '123456789';
		}
		elseif($type == 'alpha')
		{
			$back = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		elseif($type == 'md5')
		{
			$back = md5(uniqid(mt_rand(), true));
		}
		elseif($type == 'sha1')
		{
			$back = sha1(uniqid(mt_rand(), true));
		}
		else
		{
			$back = uniqid(mt_rand(), true);
		}

		$back = substr(str_shuffle(str_repeat($back, ceil($length / strlen($back)))), 0, $length);

		return $back;
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
	 * @param string $str Der String der die URL enthällt
	 *
	 * @return string
	 */
	protected static function _reduce_double_slashes($str)
	{
		return preg_replace('#(^|[^:])//+#', '\\1/', $str);
	}

	/**
	 * Entfernt einfache und Doppelte Anführungszeichen aus einem String
	 *
	 * @param string $str Der String bei dem die Anführungszeichen entfernt werden sollen
	 *
	 * @return string
	 */
	protected static function _strip_quotes($str)
	{
		return str_replace(array('"', "'"), array('', ''), $str);
	}

	/**
	 * Entfernt am Anfang und am Ende Slahes aus einem String
	 *
	 * @param string $str Der String bei dem die Slashes am Anfang und Ende entfernt werden sollen
	 *
	 * @return string
	 */
	protected static function _trim_slashes($str)
	{
		return trim($str, '/');
	}
}