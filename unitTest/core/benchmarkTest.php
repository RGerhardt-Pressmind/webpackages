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
    
    @category   benchmarkTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

require_once 'init.php';

use package\benchmark;

class benchmarkTest extends \PHPUnit_Framework_TestCase
{
	public function testBenchmark()
	{
		require_once 'benchmark.class.php';

		benchmark::start_point(true);
		benchmark::end_point(true);

		$this->assertTrue((benchmark::finish() > 0));

		benchmark::start_point();
		benchmark::end_point();

		$this->assertTrue((benchmark::finish() > 0));
	}
}
