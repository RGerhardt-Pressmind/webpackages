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

use package\system\core\cache;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'../../config.php';

class cacheTest extends TestCase
{
	public function testCacheDir()
	{
		cache::init();

		$cachePath	=	cache::get_cache_dir();

		$this->assertIsString($cachePath);

		$cachePath	=	CACHE_PATH.'cacheUnitTest'.SEP;

		cache::set_cache_dir($cachePath);

		$this->assertTrue(file_exists($cachePath));
		$this->assertEquals($cachePath, cache::get_cache_dir());

		rmdir($cachePath);
	}

	public function testCacheExtension()
	{
		cache::init();

		$this->assertEquals('.cache', cache::get_cache_extension());

		$cacheExtension	=	'.cch';

		cache::set_cache_extension($cacheExtension);

		$this->assertEquals('.cch', cache::get_cache_extension());

		$cacheExtension	=	' cch ';

		cache::set_cache_extension($cacheExtension);

		$this->assertEquals('.cch', cache::get_cache_extension());

		$cacheExtension	=	' .cch ';

		cache::set_cache_extension($cacheExtension);

		$this->assertEquals('.cch', cache::get_cache_extension());
	}

	public function testTemplateElement()
	{
		cache::init();

		cache::set_cache_active(false);

		$this->assertFalse(cache::set_template_element('unitTestTemplate', 'Unit Test content'));

		$template	=	cache::get_template_element('unitTestTemplate');

		$this->assertFalse($template);

		cache::set_cache_active(true);

		$this->assertTrue(cache::set_template_element('unitTestTemplate', 'Unit Test content'));

		$template	=	cache::get_template_element('unitTestTemplate');

		$this->assertIsString($template);
		$this->assertTrue(file_exists($template));
		$this->assertEquals('Unit Test content', file_get_contents($template));

		sleep(2);

		$template	=	cache::get_template_element('unitTestTemplate', 1);

		$this->assertFalse($template);
	}


	public function testElement()
	{
		cache::init();

		cache::set_cache_active(false);

		$this->assertFalse(cache::set_element('unitTest', 'Unit Test content', 2));

		$cacheElement	=	cache::get_element('unitTest');

		$this->assertFalse($cacheElement);

		cache::set_cache_active(true);

		$this->assertTrue(cache::set_element('unitTest', 'Unit Test content', 2));

		$cacheElement	=	cache::get_element('unitTest');

		$this->assertEquals('Unit Test content', $cacheElement);

		sleep(3);

		$cacheElement	=	cache::get_element('unitTest');

		$this->assertFalse($cacheElement);
	}


	public function testDelete()
	{
		cache::init();

		cache::set_cache_active(false);

		$this->assertFalse(cache::set_element('unitTest', 'Unit Test content', 2));
		$this->assertFalse(cache::delete_element('unitTest'));
		$this->assertFalse(cache::get_element('unitTest'));

		$this->assertFalse(cache::set_template_element('unitTest', 'Unit Test content'));
		$this->assertFalse(cache::delete_template_element('unitTest'));
		$this->assertFalse(cache::get_template_element('unitTest'));

		cache::set_cache_active(true);

		$this->assertTrue(cache::set_element('unitTest', 'Unit Test content', 2));
		$this->assertTrue(cache::delete_element('unitTest'));
		$this->assertFalse(cache::get_element('unitTest'));

		$this->assertTrue(cache::set_template_element('unitTest', 'Unit Test content'));
		$this->assertTrue(cache::delete_template_element('unitTest'));
		$this->assertFalse(cache::get_template_element('unitTest'));
	}
}
