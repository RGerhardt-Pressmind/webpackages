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
use package\core\language;

require_once 'init.php';

class languageTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('language', '\package\core\\', true);
	}

	public function testSetLanguage()
	{
		language::set_language('de_DE');

		$this->assertEquals('de_DE', language::get_language());
	}


	public function testSetLanguagePath()
	{
		language::set_language_path(LANGUAGE_PATH);

		$this->assertEquals(LANGUAGE_PATH, language::get_language_path());
	}


	public function testSetDefaultLanguage()
	{
		language::set_default_language('en_US');

		$this->assertEquals('en_US', language::get_default_language());
	}


	public function testGetLocale()
	{
		$this->assertEquals('2a4101e9736e8950b56f261ae687529b', md5(serialize(language::getAllSystemLocales())));
	}
}
