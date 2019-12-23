<?php
/**
 *  Copyright (C) 2010 - 2019  <Robbyn Gerhardt>
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
 * @package       truetravel_bootstrap
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2019, pressmind GmbH (https://www.pressmind.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          https://www.pressmind.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace unitTest\system\core;

use package\system\core\curl;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'../../config.php';

class curlTest extends TestCase
{
	public function testExtensionExists()
	{
		$this->assertTrue(curl::curl_extension_exists());
	}


	public function testDownloadFile()
	{
		$url	=	'https://upload.wikimedia.org/wikipedia/commons/8/8a/Curl-logo.svg';

		$this->assertTrue(curl::downloadFile($url, CACHE_PATH.'Curl-logo.svg'));

		unlink(CACHE_PATH.'Curl-logo.svg');
	}


	public function testGetData()
	{
		$url	=	'http://ip.jsontest.com/';

		$json	=	curl::get_data($url);

		$this->assertIsString($json);

		$json	=	json_decode($json, true);

		$this->assertTrue(isset($json['ip']));
		$this->assertNotEmpty($json['ip']);
	}


	public function testGetStatus()
	{
		$url	=	'https://webpackages.de';

		$this->assertEquals(200, curl::get_http_status($url));

		$url	=	'https://webpackages'.mt_rand(0, 10000).'.de';

		$this->assertEquals(404, curl::get_http_status($url));
	}
}
