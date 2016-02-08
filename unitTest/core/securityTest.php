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

use package\core\security;

require_once 'init.php';

class securityTest extends \PHPUnit_Framework_TestCase
{
	public function testSecurity()
	{
		$_POST['xss']	=	'http://example.com/index.php?user=<script>alert(123)</script>';
		$this->assertEquals('http://example.com/index.php?user=[removed]alert&#40;123&#41;[removed]', security::url('xss', 'POST', 'string'));

		$_POST['xss']	=	'http://example.com/index.php?user=<script>window.onload = function() {var AllLinks=document.getElementsByTagName("a");AllLinks[0].href = "http://badexample.com/malicious.exe"; }</script> ';
		$this->assertEquals('http://example.com/index.php?user=[removed][removed] = function() {var AllLinks=document.getElementsByTagName(&#34;a&#34;);AllLinks[0].href = &#34;http://badexample.com/malicious.exe&#34;; }[removed]', security::url('xss', 'POST', 'string'));

		$_POST['xss']	=	'http://www.example.com/search?q=flowers+%3Cscript%3Eevil_script()%3C/script%3E';
		$this->assertEquals('http://www.example.com/search?q=flowers+[removed]evil_script()[removed]', security::url('xss', 'POST', 'string'));

		$_POST['xss']	=	'index.php?name=%3c%73%63%72%69%70%74%3e%77%69%6e%64%6f%77%2e%6f%6e%6c%6f%61%64%20%3d%20%66%75%6e%63%74%69%6f%6e%28%29%20%7b%76%61%72%20%6c%69%6e%6b%3d%64%6f%63%75%6d%65%6e%74%2e%67%65%74%45%6c%65%6d%65%6e%74%73%42%79%54%61%67%4e%61%6d%65%28%22%61%22%29%3b%6c%69%6e%6b%5b%30%5d%2e%68%72%65%66%3d%22%68%74%74%70%3a%2f%2f%61%74%74%61%63%6b%65%72%2d%73%69%74%65%2e%63%6f%6d%2f%22%3b%7d%3c%2f%73%63%72%69%70%74%3e';
		$this->assertEquals('index.php?name=[removed][removed] = function() {var link=document.getElementsByTagName(&#34;a&#34;);link[0].href=&#34;http://attacker-site.com/&#34;;}[removed]', security::url('xss', 'POST', 'string'));

		//Boolean
		$this->assertTrue(security::control('true', 'bool'));
		$this->assertFalse(security::control('false', 'bool'));
		$this->assertTrue(security::control('yes', 'bool'));
		$this->assertFalse(security::control('no', 'bool'));
		$this->assertTrue(security::control(true, 'bool'));
		$this->assertFalse(security::control(false, 'bool'));

		//Integer
		$this->assertFalse(security::control('abcd', 'int'));
		$this->assertEquals(1234, security::control('1234', 'int'));

		//Email
		$this->assertFalse(security::control('robbyn[at]test.de', 'email'));
		$this->assertEquals('robbyn@test.de', security::control('robbyn@test.de', 'email'));

		//IP
		$this->assertFalse(security::control('0.0.0', 'ip'));
		$this->assertEquals('127.0.0.1', security::control('127.0.0.1', 'ip'));

		//Float
		$this->assertEquals(5.0, security::control('5', 'float'));
		$this->assertFalse(security::control('abc', 'float'));
		$this->assertEquals(12.45, security::control('12.45', 'float'));
		$this->assertFalse(security::control('12,45', 'float'));
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
		$this->assertEquals('f224c4b8d3507412a3b4f0a98fbacd3353287570fed4efb1907d0c20c1287de97abb204752498c032b40b70ec061ef72ce394bcf57e79157065a7e11f2ad5bcc', security::sha_sec('Hello World'));
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