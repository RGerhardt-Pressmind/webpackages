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
use SplFileInfo;
use system\plugins\Adapter\AdapterPlugins;

class Plugin
{
	private static array $_hooks	=	[];
	private static array $_filter	=	[];

	/**
	 * Call hook
	 *
	 * @param string $name
	 * @param array $params
	 */
	public static function hook(string $name, array $params = [])
	{
		if(isset(self::$_hooks[$name]))
		{
			foreach(self::$_hooks[$name] as $hook)
			{
				call_user_func_array($hook, $params);
			}
		}
	}

	/**
	 * Call registry filter
	 *
	 * @param string $hook
	 * @param mixed  $param
	 *
	 * @return false|mixed
	 */
	public static function call_filter(string $hook, mixed $param)
	{
		if(!empty(self::$_filter[$hook]))
		{
			foreach(self::$_filter[$hook] as $filter)
			{
				$param	=	call_user_func_array($filter, (is_array($param) ? $param : [$param]));
			}
		}

		return $param;
	}

	/**
	 * Add filter
	 *
	 * @param string $hook
	 * @param array  $call
	 *
	 * @return void
	 */
	public static function add_filter(string $hook, array $call)
	{
		self::$_filter[$hook][]	=	$call;
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
		$config	=	Registry::getInstance()->get('config');

		$path	=	ROOT.$config['plugin']['path'].DIRECTORY_SEPARATOR;
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
						$plugin->pluginParameter(($config['plugin'][$dirname] ?? []));
						$plugin->registerHooks();
					}
				}
			}
		}
	}
}
