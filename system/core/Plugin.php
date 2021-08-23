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

use FilesystemIterator;
use RecursiveDirectoryIterator;
use SplFileInfo;
use system\plugins\Adapter\AdapterPlugins;

class Plugin
{
	private static array $_hooks	=	[];

	/**
	 * Call hook
	 *
	 * @param string $name
	 * @param null $params
	 */
	public static function hook(string $name, $params = null)
	{
		if(isset(self::$_hooks[$name]))
		{
			foreach(self::$_hooks[$name] as $hook)
			{
				call_user_func($hook, $params);
			}
		}
	}

	/**
	 * Register hook call
	 *
	 * @param string $hook
	 * @param array $call
	 */
	public static function register(string $hook, array $call)
	{
		self::$_hooks[$hook][]	=	$call;
	}

	/**
	 * Load plugin register
	 */
	public static function loadPluginRegister()
	{
		$path	=	ROOT.'system'.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR;
		$dirs	=	new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);

		/**
		 * @var SplFileInfo $dir
		 */
		foreach($dirs as $dir)
		{
			if($dir->isDir())
			{
				$dirname	=	$dir->getFilename();
				$pluginFile	=	$dir->__toString().DIRECTORY_SEPARATOR.$dirname.'.php';

				if(file_exists($pluginFile))
				{
					require_once $pluginFile;

					$class	=	'plugins\\'.$dirname;

					$plugin	=	new $class();

					if($plugin instanceof AdapterPlugins)
					{
						$plugin->registerHooks();
					}
				}
			}
		}
	}
}
