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
use package\core\url;

require_once 'init.php';

class mod_rewriteTest extends \PHPUnit_Framework_TestCase
{
	private $url;

	public function setUp()
	{
		$this->url	=	autoload::get('url', '\package\core\\');
		url::init();
	}

	public function testSetUseModRewrite()
	{
		url::set_use_mod_rewrite(true);

		$this->assertTrue(url::$useModRewrite);
	}


	public function testSetUseFileExtension()
	{
		$this->url->set_use_file_extension('.unitTest');

		$this->assertEquals('unitTest', url::$useFileExtension);
	}


	public function testGetUrlSimple()
	{
		$this->assertEquals('http://localhost/unitClass/unitMethode_6.unitTest', $this->url->get_url_simple(HTTP, array('class' => 'unitClass', 'methode' => 'unitMethode', 'id' => 6)));
	}


	public function testGetCurrentURL()
	{
		$this->assertEquals('http://localhost', url::getCurrentUrl());
	}


	public function testCreateValidUrlString()
	{
		$this->assertEquals('max-mustermnchen', url::createValidUrlString('Max Mustermänchen'));
	}
}
