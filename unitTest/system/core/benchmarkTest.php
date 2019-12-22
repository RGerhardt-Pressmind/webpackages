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

use package\system\core\benchmark;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'../../config.php';

class benchmarkTest extends TestCase
{
	public function testBenchmarkWithMiddlePoint()
	{
		benchmark::start_point(true);

		benchmark::middle_point(true);
		benchmark::middle_point(true);
		benchmark::middle_point(true);
		benchmark::middle_point(true);

		benchmark::end_point(true);

		$finish	=	benchmark::finish();

		$this->assertCount(4, $finish);
	}


	public function testBenchmarkWithoutMiddlePoint()
	{
		benchmark::start_point(true);
		benchmark::end_point(true);

		$finish	=	benchmark::finish();

		$this->assertIsInt($finish);
	}
}
