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

/**
 * Textmanipulationen
 *
 * Wenn man einen bestimmten Satz kürzen möchte oder ein zufälligen String zurück haben möchte, kann man die text
 * Klasse nutzen.
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       text
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class text implements IStatic
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
	public static function word_limiter($str, $limit = 100, $suffix = '...')
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'wordLimiter', [$str, $limit, $suffix]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(empty(trim($str)) === true)
		{
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,'.(int)$limit.'}/', $str, $matches);

		if(strlen($str) == strlen($matches[0]))
		{
			$suffix = '';
		}

		$back = rtrim($matches[0]).$suffix;

		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('after', 'text', 'wordLimiter', [$back]);

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
	 * @param int    $limit  Die maximale länge des Strings die es haben soll.
	 * @param string $suffix Der String soll am Ende einen weiteren String bekommen. Standartmäßig "..."
	 *
	 * @return string Gibt den gekürzten String zurück
	 */
	public static function truncate($string, $limit, $suffix = '...')
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'template', 'truncate', [$string, $limit, $suffix]);

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
	 * Zensiert Wörter in einem String
	 *
	 * @param string $str         Der String der Wörter enthält die Zentriert werden sollen.
	 * @param array  $censored    Ein Array das die zu zensierenden Wörter enthält
	 * @param string $replacement Der String der die zu zensierenden Wörte einnehmen soll. Standartmäßig ''
	 *
	 * @return string Gibt den zensierten String zurück
	 */
	public static function word_censor($str, $censored, $replacement = '')
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'wordCensor', [$str, $censored, $replacement]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(empty($censored) === true)
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

		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('after', 'text', 'wordCensor', [$str]);

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
	 *
	 * @return mixed|string Gibt den String zurück.
	 */
	public static function highlight_code($str)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'highlightCode', [$str]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$str = str_replace(['&lt;', '&gt;'], ['<', '>'], $str);

		$str = str_replace(['<?', '?>', '<%', '%>', '\\', '</script>'], ['phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'], $str);

		$str = '<?php '.$str.' ?>'; // <?

		$str = highlight_string($str, true);

		$str = preg_replace('/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i', '<span style="color: #$1">', $str);
		$str = preg_replace('/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is', "$1</span>\n</span>\n</code>", $str);
		$str = preg_replace('/<span style="color: #[A-Z0-9]+"\><\/span>/i', '', $str);

		$str = str_replace(['phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'], ['&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'], $str);

		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('after', 'text', 'highlightCode', [$str]);

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
	 * @param        $str
	 * @param        $phrase
	 * @param string $tag_open
	 * @param string $tag_close
	 *
	 * @return mixed|string
	 */
	public static function highlight_phrase($str, $phrase, $tag_open = '<strong>', $tag_close = '</strong>')
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'highlightPhrase', [$str, $phrase, $tag_open, $tag_close]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if(empty($str) === true)
		{
			return '';
		}

		if(empty($phrase) === false)
		{
			return preg_replace('/('.preg_quote($phrase, '/').')/i', $tag_open."\\1".$tag_close, $str);
		}

		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('after', 'text', 'highlightPhrase', [$str]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

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
	public static function random_string($type = 'normal', $length = 10)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'random_string', [$type, $length]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if($type === 'alnum')
		{
			$back = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		elseif($type === 'numeric')
		{
			$back = '0123456789';
		}
		elseif($type === 'nozero')
		{
			$back = '123456789';
		}
		elseif($type == 'alpha')
		{
			$back = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		elseif($type === 'md5')
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

		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('after', 'text', 'random_string', [$back]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

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
	public static function reduce_double_slashes($str)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'reduce_double_slashes', [$str]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return preg_replace('#(^|[^:])//+#', '\\1/', $str);
	}

	/**
	 * Entfernt einfache und Doppelte Anführungszeichen aus einem String
	 *
	 * @param string $str Der String bei dem die Anführungszeichen entfernt werden sollen
	 *
	 * @return string
	 */
	public static function strip_quotes($str)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'strip_quotes', [$str]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return str_replace(['"', "'"], ['', ''], $str);
	}

	/**
	 * Entfernt am Anfang und am Ende Slahes aus einem String
	 *
	 * @param string $str Der String bei dem die Slashes am Anfang und Ende entfernt werden sollen
	 *
	 * @return string
	 */
	public static function trim_slashes($str)
	{
		if(class_exists('\package\core\plugins') === true)
		{
			$plugin = plugins::hookCall('before', 'text', 'trim_slashes', [$str]);

			if($plugin != null)
			{
				return $plugin;
			}
		}

		return trim($str, '/');
	}
}