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
    
    @category   ftpTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\autoload;

require_once 'init.php';

class ftpTest extends \PHPUnit_Framework_TestCase
{
	private $ftp, $ftpServer = 'demo.wftpserver.com', $username = 'demo-user', $password = 'demo-user';

	public function setUp()
	{
		$this->ftp	=	autoload::get('ftp', '\package\\');

		try{
			$this->ftp->connect($this->ftpServer);
		}catch(\Exception $e){
			$this->assertTrue(false);
		}

		$this->ftp->login($this->username, $this->password);

		if(file_exists(CACHE_PATH.'version.txt') === false)
		{
			file_put_contents(CACHE_PATH.'version.txt', 1);
		}
	}


	public function tearDown()
	{
		$this->ftp	=	null;
	}



	public function testGetRemoteFile()
	{
		chmod(CACHE_PATH, 0777);

		$this->assertTrue($this->ftp->get_remote_file('/download/version.txt', CACHE_PATH.'version.txt'));
	}


	public function testModifiedTime()
	{
		$this->assertEquals('07.08.2013', $this->ftp->modified_time('/download/version.txt', 'd.m.Y'));
	}


	public function testIsDir()
	{
		$this->assertTrue($this->ftp->is_dir('/download'));
	}


	public function testCount()
	{
		$this->assertEquals(17, $this->ftp->count('/download', 'file', false));
	}


	public function testIsEmpty()
	{
		$this->assertFalse($this->ftp->is_empty('/download'));
	}


	public function testPutAll()
	{
		$this->assertTrue($this->ftp->put_all(CACHE_PATH, '/upload'));
	}


	public function testPutFromString()
	{
		$this->assertTrue($this->ftp->put_from_string('/upload/version22.txt', file_get_contents(CACHE_PATH.'version.txt')));
	}


	public function testDirSize()
	{
		$this->assertEquals(18424717, $this->ftp->dir_size('/download/'));
	}
}
