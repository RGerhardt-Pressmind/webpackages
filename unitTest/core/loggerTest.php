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
    
    @category   loggerTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\autoload;

require_once 'init.php';


class loggerTest extends \PHPUnit_Framework_TestCase
{
	private $logger;

	public function setUp()
	{
		$this->logger	=	autoload::get('logger', '\package\\');
	}

	public function tearDown()
	{
		$this->logger	=	null;

		if(file_exists(CACHE_PATH.'version.txt'))
		{
			unlink(CACHE_PATH.'version.txt');
		}
	}

	public function testWriteLog()
	{
		$this->logger->write_log('UnitTest', 300, 'error');
	}


	public function testReadLog()
	{
		$this->assertTrue((stripos($this->logger->read_log(), 'UnitTest') !== false));
	}


	public function testDeleteLog()
	{
		$this->assertTrue($this->logger->delete_log());
	}
}
