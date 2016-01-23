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
    
    @category   test.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

class test implements \package\implement\iDynamic
{
	private static $db;

	public function __get($varName)
	{
		return 1;
	}

	public function getClassName()
	{
		return 'USER';
	}

	public function setAllClasses($allClasses)
	{
		self::$db	=	$allClasses['db'];
	}


	public function hello_body()
	{
		return 'Hallo Welt';
	}

	public function loadData()
	{
	}
}