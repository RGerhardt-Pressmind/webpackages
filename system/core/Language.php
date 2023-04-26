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

namespace system\core;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use system\core\Config\LanguageConfig;

class Language
{
	private static array $_translates	=	[];

	private static string $languageFilePath;

	/**
	 * Register language
	 *
	 * @param LanguageConfig $config
	 */
	public static function register(LanguageConfig $config): void
	{
		self::$languageFilePath	=	$config->languageFilePath;

		self::changeLanguage($config->language);
	}

	/**
	 * Change language
	 *
	 * @param string $lng
	 */
	public static function changeLanguage(string $lng): void
	{
		$lng = Plugin::call_filter('changeLanguage', $lng);

		Plugin::hook('beforeChangeLanguage', [$lng]);

		self::$_translates	=	[];

		if(!file_exists(self::$languageFilePath.$lng))
		{
			echo 'Failed, to load language folder "'.self::$languageFilePath.$lng.'"';
			exit;
		}

		$dir	=	new RecursiveDirectoryIterator(self::$languageFilePath.$lng, FilesystemIterator::SKIP_DOTS);
		$files	=	new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST);

		/**
		 * @var SplFileInfo $file
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

		list(self::$_translates)	=	Plugin::call_filter('afterChangeLanguage', [self::$_translates]);

		Plugin::hook('afterChangeLanguge', [self::$_translates]);
	}

	/**
	 * Translate string
	 *
	 * @param string $key
	 * @param array  $parameter
	 * @param null   $file
	 *
	 * @return string|array|bool
	 */
	public static function translate(string $key, array $parameter = [], $file = null): string|array|bool
	{
		$keys = explode('.', $key);

		if($file)
		{
			$values	=	self::$_translates[$file];

			foreach($keys as $key)
			{
				if(!isset($values[$key]))
				{
					return false;
				}

				$values = $values[$key];
			}

			return self::replaceParameter($values, $parameter);
		}
		else
		{
			$current	=	false;

			foreach(self::$_translates as $values)
			{
				if(isset($values[$keys[0]]))
				{
					$current	=	$values[$keys[0]];
					break;
				}
			}

			if($current !== false)
			{
				if(count($keys) > 1)
				{
					unset($keys[0]);

					foreach($keys as $key)
					{
						if(!isset($current[$key]))
						{
							return false;
						}

						$current	=	$current[$key];
					}
				}

				return self::replaceParameter($current, $parameter);
			}
		}

		return false;
	}

	/**
	 * Replace individual parameter
	 *
	 * @param mixed $value
	 * @param array $parameter
	 *
	 * @return array|string|string[]
	 */
	private static function replaceParameter(mixed $value, array $parameter): array|string
	{
		if(!is_string($value))
		{
			return '';
		}

		if(empty($parameter))
		{
			return $value;
		}

		foreach($parameter as $key => $v)
		{
			$value	=	str_replace('{{'.$key.'}}', $v, $value);
		}

		return $value;
	}
}
