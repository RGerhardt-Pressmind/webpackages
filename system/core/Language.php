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

use system\core\Config\LanguageConfig;

class Language
{
	private static $_translates	=	[];

	private static $languageFilePath;

	/**
	 * Register language
	 *
	 * @param LanguageConfig $config
	 */
	public static function register(LanguageConfig $config)
	{
		self::$languageFilePath	=	$config->languageFilePath;

		self::changeLanguage($config->language);
	}

	/**
	 * Change language
	 *
	 * @param string $lng
	 */
	public static function changeLanguage($lng)
	{
		self::$_translates	=	[];

		if(!file_exists(self::$languageFilePath.$lng))
		{
			echo 'Failed, to load language folder "'.self::$languageFilePath.$lng.'"';
			exit;
		}

		$dir	=	new \RecursiveDirectoryIterator(self::$languageFilePath.$lng, \RecursiveDirectoryIterator::SKIP_DOTS);
		$files	=	new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::CHILD_FIRST);

		if(iterator_count($files) > 0)
		{
			/**
			 * @var \SplFileInfo $file
			 */
			foreach($files as $file)
			{
				if($file->getExtension() == 'json')
				{
					$filename	=	str_replace('.'.$file->getExtension(), '', $file->getFilename());
					$json		=	file_get_contents($file->__toString());
					$json		=	json_decode($json, true);

					if($json === false)
					{
						echo 'Failed, language json invalid ('.$filename.')';
						exit;
					}

					self::$_translates[$filename]	=	$json;
				}
			}
		}
	}

	/**
	 * Translate string
	 *
	 * @param string $key
	 * @param null $file
	 *
	 * @return bool|mixed
	 */
	public static function translate($key, $file = null)
	{
		if($file)
		{
			$values	=	self::$_translates[$file];

			foreach($values as $_key => $value)
			{
				if($_key == $value)
				{
					return $value;
				}
			}
		}
		else
		{
			foreach(self::$_translates as $file => $values)
			{
				foreach($values as $_key => $value)
				{
					if($_key == $key)
					{
						return $value;
					}
				}
			}
		}

		return false;
	}
}
