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
 *  @package	Webpackages
 *  @subpackage controllers
 *  @author	    Robbyn Gerhardt <gerhardt@webpackages.de>
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace unitTest\core;

use package\core\autoload;
use package\implement\IPlugin;
use package\core\plugins;

require_once 'init.php';

class pluginsTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('plugins', '\package\core\\', true);
		plugins::init();

		$back	=	$this->get_plugins(PLUGIN_DIR);

		plugins::$definedPluginsClasses	=	array();

		if(is_array($back) === true && empty($back) === false)
		{
			foreach($back as $t)
			{
				if($t['class'] instanceof IPlugin)
				{
					$class	=	$t['class'];

					$class->setAllClasses(array());
					$class->construct();

					plugins::$definedPluginsClasses[]	=	$class;
				}
			}
		}
	}

	public function testGetAllDefinedPlugins()
	{
		$this->assertEquals('package\plugins\testPlugin', plugins::getAllDefinedPlugins()[0]);
	}


	public function testHookShow()
	{
		plugins::hookShow('before', 'pluginTest', 'testHookShow', array('Hello', 'World'));

		$this->assertTrue(file_exists(CACHE_PATH.'unitTestPlugin.txt'));

		if(file_exists(CACHE_PATH.'unitTestPlugin.txt'))
		{
			@unlink(CACHE_PATH.'unitTestPlugin.txt');
		}
	}


	public function testHookCall()
	{
		$this->assertEquals('Ich bin ein Plugin "Hello World"', plugins::hookCall('before', 'pluginTest', 'testHookCall', array('Hello', 'World')));
	}


	public function testHookTemplate()
	{
		$this->expectOutputString('Hello World');
		plugins::hookTemplate('simple', 'UnitTest', array('Hello', 'World'));
	}


	/**
	 * Sucht im angegebenen Ordner nach der master Plugin Datei
	 *
	 * @param string $dir
	 * @return array
	 */
	protected function get_plugins($dir)
	{
		$directory 	= 	new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator 	= 	new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::LEAVES_ONLY);
		$back		=	array();

		foreach($iterator as $item)
		{
			$file	=	new \SplFileInfo($item);

			if(strpos($file->getFilename(), '.master.class.php') !== false && $file->isDir() === false)
			{
				require_once $file;

				$className	=	str_replace(array('.php', '.php5', '.master.class'), array('', '', ''), $file->getFilename());
				$className	=	'package\plugins\\'.$className;

				if(class_exists($className) === false)
				{
					continue;
				}

				$class	=	new $className();

				$back[]	=	array('class_name' => $className, 'class' => $class);
			}
			elseif($file->isDir() === true)
			{
				$back	=	array_merge($back, $this->get_plugins($file));
			}
		}

		return $back;
	}
}
