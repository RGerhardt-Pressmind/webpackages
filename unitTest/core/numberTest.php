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
    
    @category   numberTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\core\autoload;
use package\core\number;

require_once 'init.php';

class numberTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('number', '\package\core\\', true);
	}


	public function testByteFormat()
	{
		$this->assertEquals('11.7738 MB', number::byte_format(12345678, 4));
	}


	public function testDiff()
	{
		$diff	=	number::diff((time()-2000), time());

		$this->assertEquals(0, $diff->year);
		$this->assertEquals(0, $diff->month);
		$this->assertEquals(0, $diff->day);
		$this->assertEquals(0, $diff->hour);
		$this->assertEquals(33, $diff->min);
		$this->assertEquals(20, $diff->sec);
	}


	public function testGetDiffValue()
	{
		$diff	=	number::diff((time()-2000), time());

		$this->assertEquals('33 Minuten 20 Sekunden', number::get_diff_value($diff));
		$this->assertEquals('33 Min. 20 Sek.', number::get_diff_value($diff, true));
	}
}
