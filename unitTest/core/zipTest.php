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
    
    @category   zipTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\core\autoload;
use package\core\FileSystem;

require_once 'init.php';

class zipTest extends \PHPUnit_Framework_TestCase
{
	private $zip;

	public function setUp()
	{
		$this->zip	=	autoload::get('zip', '\package\core\\');
	}

	public function tearDown()
	{
		$this->zip	=	null;
	}

	public function testCreateZipArchive()
	{
		$this->assertTrue($this->zip->createZipArchive(CORE_DIR, CACHE_PATH, 'UnitTest.zip'));
		$this->assertFileExists(CACHE_PATH.'UnitTest.zip');
	}

	public function testAddFileToZipArchive()
	{
		$this->assertTrue($this->zip->addFileToZipArchive(PACKAGE_DIR, SYSTEM_PATH.'loadSessionHandler.php', CACHE_PATH.'UnitTest.zip'));
	}

	public function testExtractZipArchive()
	{
		$this->assertTrue($this->zip->extractZipArchive(CACHE_PATH.'UnitTest.zip', CACHE_PATH, true));
		$this->assertFileNotExists(CACHE_PATH.'UnitTest.zip');

		autoload::get('FileSystem', '\package\core\\', true);
		FileSystem::delete_files(CACHE_PATH, false);
	}
}
