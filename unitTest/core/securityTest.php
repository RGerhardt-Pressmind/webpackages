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
    
    @category   securityTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\core\security;

require_once 'init.php';

class securityTest extends \PHPUnit_Framework_TestCase
{
	public function testSecurity()
	{
		$_POST['xss']	=	'http://example.com/index.php?user=<script>alert(123)</script>';
		$this->assertEquals('http://example.com/index.php?user=alert(123)', security::url('xss', 'POST', 'string'));

		$_POST['xss']	=	'http://example.com/index.php?user=<script>window.onload = function() {var AllLinks=document.getElementsByTagName("a");AllLinks[0].href = "http://badexample.com/malicious.exe"; }</script> ';
		$this->assertEquals('http://example.com/index.php?user=window.onload = function() {var AllLinks=document.getElementsByTagName("a");AllLinks[0].href = "http://badexample.com/malicious.exe"; }', security::url('xss', 'POST', 'string'));

		$_POST['xss']	=	'http://www.example.com/search?q=flowers+%3Cscript%3Eevil_script()%3C/script%3E';
		$this->assertEquals('http://www.example.com/search?q=flowers+evil_script()', security::url('xss', 'POST', 'string'));

		$_POST['xss']	=	'index.php?name=%3c%73%63%72%69%70%74%3e%77%69%6e%64%6f%77%2e%6f%6e%6c%6f%61%64%20%3d%20%66%75%6e%63%74%69%6f%6e%28%29%20%7b%76%61%72%20%6c%69%6e%6b%3d%64%6f%63%75%6d%65%6e%74%2e%67%65%74%45%6c%65%6d%65%6e%74%73%42%79%54%61%67%4e%61%6d%65%28%22%61%22%29%3b%6c%69%6e%6b%5b%30%5d%2e%68%72%65%66%3d%22%68%74%74%70%3a%2f%2f%61%74%74%61%63%6b%65%72%2d%73%69%74%65%2e%63%6f%6d%2f%22%3b%7d%3c%2f%73%63%72%69%70%74%3e';
		$this->assertEquals('index.php?name=window.onload = function() {var link=document.getElementsByTagName("a");link[0].href="http://attacker-site.com/";}', security::url('xss', 'POST', 'string'));
	}


	public function testGetMimeType()
	{
		$this->assertEquals('text/x-php', security::get_mime_type(CORE_DIR.'security.class.php'));
	}


	public function testGetFileType()
	{
		$this->assertEquals('php', security::get_file_type(CORE_DIR.'security.class.php'));
	}


	public function testShaSec()
	{
		$this->assertEquals('b06044a633b3b57ee70e85a6140bb6b8ade1e534b480f7a2a20756b571e933a88d70ccf38ac39a3f9c07eaa44058bbd2de5168d92dbed18032e01112558f3fc3', security::sha_sec('Hello World'));
	}


	public function testIsBot()
	{
		$bot	=	security::is_bot();

		$this->assertFalse($bot['isBot']);
	}


	public function testGetIpAddress()
	{
		$ip	=	security::get_ip_address();

		$this->assertFalse($ip);
	}
}