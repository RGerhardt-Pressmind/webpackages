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
    
    @category   XMLTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\core\autoload;

require_once 'init.php';

class XMLTest extends \PHPUnit_Framework_TestCase
{
	private $xml, $testXML;

	public function setUp()
	{
		$this->xml	=	autoload::get('XML', '\package\core\\');

		$this->testXML	=	'
		<root>
			<products>
				<product id="1" language="en">Unit Test 1</product>
				<product id="2" language="en">Unit Test 2</product>
				<product id="3" language="en">Unit Test 3</product>
				<product id="4" language="en">Unit Test 4</product>
			</products>
		</root>
		';

		$this->xml->loadXML($this->testXML);
	}


	public function tearDown()
	{
		$this->xml	=	null;
	}


	public function testToArray()
	{
		$this->assertEquals('1dbc0be0c4feb6065674fb28891752cd', md5(serialize($this->xml->toArray())));
	}
}
