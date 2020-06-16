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
 * @package       webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core;

class Config
{
	private static $_config = null;

	/**
	 * Get config data
	 *
	 * @return mixed
	 */
	public static function getConfig()
	{
		if(is_null(self::$_config))
		{
			$configFile	=	ROOT.'config.json';

			if(!file_exists($configFile))
			{
				echo 'config.json in root path not exist';
				exit;
			}

			$configContent	=	file_get_contents($configFile);
			$configJSON		=	json_decode($configContent, true);

			if(!$configJSON)
			{
				echo 'config.json content is not json';
				exit;
			}

			self::$_config	=	$configJSON;
		}
		else
		{
			$configJSON	=	self::$_config;
		}

		if(!isset($configJSON[ENV]))
		{
			echo 'config.json has not "'.ENV.'" environment';
			exit;
		}

		return $configJSON[ENV];
	}
}
