<?php
/**
 *  Copyright (C) 2010 - 2022  <Robbyn Gerhardt>
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
 * @package       webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2022
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\Security\Adapter;

use system\core\Plugin;

class sanitizer implements AdapterInterface
{
	const CONVERT_STRING	=	'string';
	const CONVERT_INT		=	FILTER_VALIDATE_INT;
	const CONVERT_FLOAT		=	FILTER_VALIDATE_FLOAT;
	const CONVERT_EMAIL		=	FILTER_VALIDATE_EMAIL;
	const CONVERT_BOOLEAN	=	FILTER_VALIDATE_BOOLEAN;

	/**
	 * Validate variable
	 *
	 * @param string  $str
	 * @param null $convert
	 *
	 * @return mixed
	 */
	public function validate($str, $convert = null): mixed
	{
		list($str, $convert) = Plugin::call_filter('beforeValidateSecurity', [$str, $convert]);

		Plugin::hook('beforeValidateSecurity', [$str, $convert]);

		if($convert)
		{
			$str	=	$this->convert($str, $convert);
		}

		if(!$convert || $convert == self::CONVERT_STRING)
		{
			// Remove invisible characters
			$str	=	$this->removeInvisibleCharacters($str);

			// Remove XSS
			$str	=	$this->xss_clean($str);

			// Trim empty chars
			$str	=	trim($str);
		}

		list($str, $convert) = Plugin::call_filter('afterValidateSecurity', [$str, $convert]);

		Plugin::hook('afterValidateSecurity', [$str, $convert]);

		return $str;
	}

	/**
	 * Convert char to type
	 *
	 * @param mixed $str
	 * @param string $convert
	 *
	 * @return mixed
	 */
	private function convert(mixed $str, string $convert): mixed
	{
		list($str, $convert)	=	Plugin::call_filter('beforeConvertSecurity', [$str, $convert]);

		Plugin::hook('beforeConvertSecurity', [$str, $convert]);

		switch($convert)
		{
			case self::CONVERT_INT:

				$str	=	(int)$str;

			break;
			case self::CONVERT_FLOAT:

				$str	=	(float)$str;

			break;
			case self::CONVERT_BOOLEAN:

				$str	=	(bool)mb_strtolower($str);

			break;
			case self::CONVERT_EMAIL:

				$str	=	filter_var($str, FILTER_VALIDATE_EMAIL);

			break;
			case self::CONVERT_STRING:

				if(is_array($str))
				{
					$str	=	'array()';
				}
				else if(is_object($str))
				{
					$str	=	'std()';
				}
				else
				{
					$str	=	(string)$str;
				}

			break;
		}

		list($str) = Plugin::call_filter('afterConvertSecurity', [$str, $convert]);

		Plugin::hook('afterConvertSecurity', [$str, $convert]);

		return $str;
	}

	/**
	 * @param string $str
	 * @param bool $url_encoded
	 *
	 * @return string
	 */
	private function removeInvisibleCharacters(string $str, bool $url_encoded = true): string
	{
		list($str, $url_encoded) = Plugin::call_filter('beforeRemoveInvisibleCharacters', [$str, $url_encoded]);

		Plugin::hook('beforeRemoveInvisibleCharacters', [$str, $url_encoded]);

		$non_displayable = [];

		if($url_encoded)
		{
			$non_displayable[] = '/%0[0-8bcef]/';
			$non_displayable[] = '/%1[0-9a-f]/';
		}

		$non_displayable[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';

		while(true)
		{
			$str = preg_replace($non_displayable, '', $str, -1, $count);

			if($count == 0)
			{
				break;
			}
		}

		list($str) = Plugin::call_filter('afterRemoveInvisibleCharacters', [$str, $url_encoded]);

		Plugin::hook('afterRemoveInvisibleCharacters', [$str, $url_encoded]);

		return $str;
	}

	/**
	 * Clear string from xss
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	private function xss_clean(string $str): string
	{
		$str = Plugin::call_filter('beforeXSSClean', $str);

		Plugin::hook('beforeXSSClean', [$str]);

		$str = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $str);
        $str = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $str);
        $str = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $str);
        $str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $str = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $str);

        // Remove javascript: and vbscript: protocols
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $str);
        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $str);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $str);

        // Remove namespaced elements (we do not need them)
        $str = preg_replace('#</*\w+:\w[^>]*+>#i', '', $str);

        do{
			// Remove really unwanted tags
			$old_str = $str;
			$str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
        } while ($old_str !== $str);

        $str = Plugin::call_filter('afterXSSClean', $str);

		Plugin::hook('afterXSSClean', [$str]);

        return $str;
	}

	/**
	 * Get type from variable content
	 *
	 * @param mixed $str
	 *
	 * @return string
	 */
	public function getType($str): string
	{
		return gettype($str);
	}
}
