<?php
/*
    Copyright (C) 2016  <Robbyn Gerhardt>

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
    
    @category   autoloadTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

require_once 'init.php';


use package\autoload;
use package\language;

class autoloadTest extends \PHPUnit_Framework_TestCase
{
	public function testGetter()
	{
		new autoload();

		try{
			autoload::get('language', '\package\\', true);
		}catch(\Exception $e){
			$this->assertEquals(true, false);
			exit;
		}

		$lng	=	language::get_default_language();

		$this->assertEquals('de_DE', $lng);
	}
}
