<?php
/**
 *  Copyright (C) 2010 - 2020  <Robbyn Gerhardt>
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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\system\core;

use package\system\implement\IStatic;

/**
 * Minify Klasse
 *
 * Die Minify Klasse hilft dabei Codeschnipsel zu minimieren um so Ladezeiten von JavaScript, HTML und CSS zu optimieren
 *
 * @method static string|null|string[] minifyJs(string $input)
 * @method static string|null|string[] minifyCss(string $input)
 * @method static string|null|string[] minifyHtml(string $input)
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       minify
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class minify extends initiator implements IStatic
{
	/**
	 * Zum initialisieren der Daten
	 */
	public static function init()
	{
	}

	/**
	 * Minify javascript string
	 *
	 * @param string $input
	 *
	 * @return null|string|string[]
	 */
	protected static function _minifyJs($input)
	{
		if(trim($input) === '')
		{
			return $input;
		}

		return preg_replace(['#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#', '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s', '#;+\}#', '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i', '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'], ['$1', '$1$2', '}', '$1$3', '$1.$3'], $input);
	}

	/**
	 * Minify css string
	 *
	 * @param string $input
	 *
	 * @return null|string|string[]
	 */
	protected static function _minifyCss($input)
	{
		if(trim($input) === '')
		{
			return $input;
		}

		return preg_replace(['#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s', '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si', '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si', '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i', '#(background-position):0(?=[;\}])#si', '#(?<=[\s:,\-])0+\.(\d+)#s', '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si', '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si', '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i', '#(?<=[\{;])(border|outline):none(?=[;\}\!])#', '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'], ['$1', '$1$2$3$4$5$6$7', '$1', ':0', '$1:0 0', '.$1', '$1$3', '$1$2$4$5', '$1$2$3', '$1:0', '$1$2'], $input);
	}

	/**
	 * Minify html string
	 *
	 * @param string $input
	 *
	 * @return null|string|string[]
	 */
	protected static function _minifyHtml($input)
	{
		if(trim($input) === "")
		{
			return $input;
		}

		$input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches){
			return '<'.$matches[1].preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]).$matches[3].'>';
		}, str_replace("\r", "", $input));

		if(strpos($input, ' style=') !== false)
		{
			$input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches){
				return '<'.$matches[1].' style='.$matches[2].self::minifyCss($matches[3]).$matches[2];
			}, $input);
		}

		if(strpos($input, '</style>') !== false)
		{
			$input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches){
				return '<style'.$matches[1].'>'.self::minifyCss($matches[2]).'</style>';
			}, $input);
		}

		if(strpos($input, '</script>') !== false)
		{
			$input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches){
				return '<script'.$matches[1].'>'.self::minifyCss($matches[2]).'</script>';
			}, $input);
		}

		return preg_replace(['#<(img|input)(>| .*?>)#s', '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s', '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', '#<(img|input)(>| .*?>)<\/\1>#s', '#(&nbsp;)&nbsp;(?![<\s])#', '#(?<=\>)(&nbsp;)(?=\<)#', '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'], ['<$1$2</$1>', '$1$2$3', '$1$2$3', '$1$2$3$4$5', '$1$2$3$4$5$6$7', '$1$2$3', '<$1$2', '$1 ', '$1', ""], $input);
	}
}
