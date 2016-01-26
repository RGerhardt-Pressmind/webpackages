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
    
    @category   FileSystemTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\autoload;
use package\FileSystem;

require_once 'init.php';

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('FileSystem', '\package\\', true);
	}

	public function testIsReallyWritable()
	{
		$this->assertTrue(FileSystem::is_really_writable(ROOT.SEP.'unitTest'.SEP.'core'.SEP.'FileSystemTest.php'));
		$this->assertTrue(FileSystem::is_really_writable(ROOT.SEP.'unitTest'.SEP.'core'.SEP));
	}


	public function testGetAllFiles()
	{
		$this->assertEquals('d836f5b875c7facb45646babd66a7380', md5(serialize(FileSystem::get_all_files(PLUGIN_DIR,\RecursiveIteratorIterator::SELF_FIRST))));
	}


	public function testDeleteFiles()
	{
		//Cache files created
		mkdir(CACHE_PATH.'UnitTest1.1', 0777, true);
		file_put_contents(CACHE_PATH.'UnitTest1.1'.SEP.'unitTest5.txt', 'UnitTest5');

		$this->assertTrue(FileSystem::delete_files(CACHE_PATH, false));
	}


	public function testCopyDirectory()
	{
		//Cache files created
		mkdir(CACHE_PATH.'UnitTest1.1'.SEP.'cacheInCache', 0777, true);
		file_put_contents(CACHE_PATH.'UnitTest1.1'.SEP.'unitTest5.txt', 'UnitTest5');
		file_put_contents(CACHE_PATH.'UnitTest1.1'.SEP.'cacheInCache'.SEP.'unitTest6.txt', 'UnitTest6');

		$this->assertTrue(FileSystem::copyDirectory(CACHE_PATH.'UnitTest1.1', CACHE_PATH.'UnitTest1.2'));
		$this->assertTrue(FileSystem::delete_files(CACHE_PATH, false));
	}


	public function testRenameDirectory()
	{
		mkdir(SYSTEM_PATH.'cacheUnit'.SEP.'cacheInCache', 0777, true);
		file_put_contents(SYSTEM_PATH.'cacheUnit'.SEP.'unitTest5.txt', 'UnitTest5');
		file_put_contents(SYSTEM_PATH.'cacheUnit'.SEP.'cacheInCache'.SEP.'unitTest6.txt', 'UnitTest6');

		$this->assertTrue(FileSystem::renameDirectory(SYSTEM_PATH.'cacheUnit', SYSTEM_PATH.'cacheUnit2'));
		$this->assertTrue(FileSystem::delete_files(SYSTEM_PATH.'cacheUnit2', true));
	}
}
