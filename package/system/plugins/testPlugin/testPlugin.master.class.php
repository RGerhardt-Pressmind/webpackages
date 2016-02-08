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
 *  @subpackage core
 *  @author	    Robbyn Gerhardt
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace package\plugins;


use package\core\database;
use package\core\template;
use package\implement\IPlugin;

class testPlugin implements IPlugin
{
	private $db, $template;

	public function construct()
	{

	}

	public function setAllClasses($allClasses)
	{
		if(isset($allClasses['db']) && $allClasses['db'] instanceof database)
		{
			$this->db		=	$allClasses['db'];
		}

		if(isset($allClasses['template']) && $allClasses['template'] instanceof template)
		{
			$this->template	=	$allClasses['template'];
		}
	}


	public function before_pluginTest_testHookShow_show($hello, $world)
	{
		$foo	=	$hello.' - '.$world;

		file_put_contents(CACHE_PATH.'unitTestPlugin.txt', $foo);
	}


	public function simple_UnitTest($hello, $world)
	{
		return $hello.' '.$world;
	}


	/**
	 * Template Test Klasse
	 */
	public function before_pluginTest_testHookCall_call($hello, $world)
	{
		return 'Ich bin ein Plugin "'.$hello.' '.$world.'"';
	}
}