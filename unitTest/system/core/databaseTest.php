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

use package\system\core\database;
use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'../../config.php';

class databaseTest extends TestCase
{
	/**
	 * @var database
	 */
	private $db;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);

		$this->db	=	new database('mysql', 'localhost', 'root', 'root', 'unitTest', 3306);

		$this->db->exec('CREATE TABLE `test` (a INT NOT NULL, b VARCHAR(10)) ENGINE=MyISAM');
		$this->db->exec('INSERT INTO `test` SET `a` = 1, `b` = "test";');
	}


	public function testCreateDatabase()
	{
		$getData	=	'
		SELECT
			COUNT(*) as count
		FROM
			`test`
		';

		$getData	=	$this->db->quefetch($getData);

		$this->assertEquals(1, $getData['count']);
	}
}
