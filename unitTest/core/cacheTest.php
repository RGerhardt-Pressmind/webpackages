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
 *  @subpackage controllers
 *  @author	    Robbyn Gerhardt <gerhardt@webpackages.de>
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace unitTest\core;

use package\core\autoload;
use package\core\cache;

require_once 'init.php';

class cacheTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('cache', '\package\core\\', true);
		cache::init();
	}

	public function testSetTemplateElement()
	{
		$this->assertTrue(cache::set_template_element('unitTest', '1234'));

		unlink(CACHE_PATH.'unitTest.html');
	}


	public function testGetTemplateElement()
	{
		$this->assertTrue(cache::set_template_element('unitTest', '1234'));
		$this->assertEquals(CACHE_PATH.'unitTest.html', cache::get_template_element('unitTest'));

		unlink(CACHE_PATH.'unitTest.html');
	}


	public function testSetElement()
	{
		$this->assertTrue(cache::set_element('unitTest', '1234'));

		unlink(CACHE_PATH.md5('unitTest').CACHE_EXTENSION);
	}


	public function testGetElement()
	{
		$this->assertTrue(cache::set_element('unitTest', '1234'));
		$this->assertEquals('1234', cache::get_element('unitTest'));

		unlink(CACHE_PATH.md5('unitTest').CACHE_EXTENSION);
	}


	public function testDeleteElement()
	{
		$this->assertTrue(cache::set_element('unitTest', '1234'));
		$this->assertTrue(cache::delete_element('unitTest'));
	}
}
