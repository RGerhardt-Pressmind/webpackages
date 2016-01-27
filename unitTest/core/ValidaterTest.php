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
    
    @category   ValidaterTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\Validater;

require_once 'init.php';

class ValidaterTest extends \PHPUnit_Framework_TestCase
{
	public function testIsString()
	{
		$this->assertTrue(Validater::isString('Hallo Welt'));
		$this->assertTrue(Validater::isString('1234'));
		$this->assertFalse(Validater::isString(1234));
	}

	public function testIsInteger()
	{
		$this->assertTrue(Validater::isInteger(1234));
		$this->assertTrue(Validater::isInteger('1234'));
		$this->assertFalse(Validater::isInteger('1234sdd'));
	}


	public function testIsBoolean()
	{
		$this->assertTrue(Validater::isBoolean(true));
		$this->assertTrue(Validater::isBoolean(false));
		$this->assertFalse(Validater::isBoolean('false'));
		$this->assertFalse(Validater::isBoolean('true'));
		$this->assertFalse(Validater::isBoolean('1'));
		$this->assertFalse(Validater::isBoolean('0'));
		$this->assertFalse(Validater::isBoolean(1));
		$this->assertFalse(Validater::isBoolean(0));
	}


	public function testIsArray()
	{
		$this->assertTrue(Validater::isArray(array()));
		$this->assertTrue(Validater::isArray([]));
		$this->assertTrue(Validater::isArray(array('foo' => 1234), true));
		$this->assertFalse(Validater::isArray('array()'));
		$this->assertFalse(Validater::isArray('[]'));
	}


	public function testIsFloat()
	{
		$this->assertTrue(Validater::isFloat(123.45));
		$this->assertFalse(Validater::isFloat('123.45'));
		$this->assertFalse(Validater::isFloat('12345'));
		$this->assertFalse(Validater::isFloat(12345));
	}


	public function testIsObject()
	{
		$this->assertTrue(Validater::isObject(new \stdClass()));
		$this->assertFalse(Validater::isObject('object'));
		$this->assertFalse(Validater::isObject(1234));
		$this->assertFalse(Validater::isObject(array()));
	}
}
