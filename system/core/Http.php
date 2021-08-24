<?php
/**
 *  Copyright (C) 2010 - 2021  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2021
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core;

class Http
{
	/**
	 * Get url
	 *
	 * @return string
	 */
	public static function getURL(): string
	{
		return self::_getBaseURL();
	}

	/**
	 * Get skin url
	 *
	 * @return string
	 */
	public static function getSkinURL(): string
	{
		$config	=	Registry::getInstance()->get('config');

		$templatePath	=	trim(trim($config['template']['path'], '/'), '\\');
		$skin			=	$config['template']['skin'];

		Plugin::hook('getSkinURL', [&$templatePath, &$skin]);

		return self::_getBaseURL().$templatePath.'/'.$skin.'/';
	}

	/**
	 * Header location to url
	 *
	 * @param string $url
	 * @return void
	 */
	public static function location(string $url)
	{
		Plugin::hook('location', [&$url]);

		header('Location: '.$url);
		exit;
	}

	/**
	 * Get base url with http protocol
	 *
	 * @return string
	 */
	private static function _getBaseURL(): string
	{
		$url	=	(self::is_https() ? 'https' : 'http').'://'.(!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost').substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], basename($_SERVER['SCRIPT_FILENAME'])));

		Plugin::hook('getBaseURL', [&$url]);

		return $url;
	}

	/**
	 * Is https
	 *
	 * @return bool
	 */
	private static function is_https(): bool
	{
		$is	=	(!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) != 'off');

		Plugin::hook('isHTTPS', [&$is]);

		return $is;
	}
}
