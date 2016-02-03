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
 *  @subpackage core
 *  @author	    Robbyn Gerhardt <gerhardt@webpackages.de>
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace unitTest\core;

use package\core\autoload;
use package\core\text;

require_once 'init.php';

class textTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('text', '\package\core\\', true);
	}


	public function testWordLimiter()
	{
		$str	=	'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.';

		$this->assertEquals('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo...', text::word_limiter($str, 10, '...'));
	}


	public function testTruncate()
	{
		$str	=	'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.';

		$this->assertEquals('Lorem ipsum dol...', text::truncate($str, 15, '...'));
	}


	public function testWordCensor()
	{
		$str	=	'Lorem ipsum dolor sit amet, consectetuer adipiscing elit.';

		$this->assertEquals('***** ipsum dolor sit amet, consectetuer adipiscing elit.', text::word_censor($str, array('Lorem'), '*****'));
	}


	public function testRandomString()
	{
		$this->assertTrue((strlen(text::random_string('normal')) == 10));
	}


	public function testReduceDoubleSlashes()
	{
		$this->assertEquals('http://www.google.de/meineSuche', text::reduce_double_slashes('http://www.google.de//meineSuche'));
	}


	public function testStripQuotes()
	{
		$this->assertEquals('Hallo Welt', text::strip_quotes('Hall"o" We\'lt'));
	}

	public function testTrimSlashes()
	{
		$this->assertEquals('Hallo Welt', text::trim_slashes('///Hallo Welt/////'));
	}
}
