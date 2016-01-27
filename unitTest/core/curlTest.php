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
    
    @category   curlTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\core\autoload;
use package\core\curl;

require_once 'init.php';

class curlTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('curl', '\package\core\\', true);
	}

	public function testCurlExtensionExists()
	{
		$this->assertTrue(curl::curl_extension_exists());
	}

	public function testGetData()
	{
		$back	=	curl::get_data('http://webpackages.de/test/todo');

		$this->assertEquals('ok', $back);
	}

	public function testGetStatus()
	{
		$back	=	curl::get_status('http://webpackages.de/test/todo');

		$this->assertEquals(200, $back);
	}

	public function testGetCityCoordinates()
	{
		$back	=	curl::get_city_coordinates('Berlin');

		$this->assertEquals('O:8:"stdClass":2:{s:3:"lat";d:52.520006599999987884075380861759185791015625;s:3:"lng";d:13.404954000000000036152414395473897457122802734375;}', serialize($back));
	}

	public function testGetCityNameByIp()
	{
		$back	=	curl::get_city_name_by_ip();

		$this->assertEquals('Not found', $back);
	}
}
