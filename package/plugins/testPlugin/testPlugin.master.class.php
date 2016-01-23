<?php
/*
    Copyright (C) 2015  <Robbyn Gerhardt>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    @category   testPlugin.master.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package\plugins;


use package\implement\IPlugin;

class testPlugin implements IPlugin
{
	private $db, $template;

	public function construct()
	{

	}

	public function setAllClasses($allClasses)
	{
		$this->db		=	$allClasses['db'];
		$this->template	=	$allClasses['template'];
	}


	/**
	 * Template Test Klasse
	 */
	public function hello_body()
	{
		return 'Ich bin ein Plugin "Hello World"';
	}
}