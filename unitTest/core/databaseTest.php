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
 *  @subpackage controllers
 *  @author	    Robbyn Gerhardt <gerhardt@webpackages.de>
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace unitTest\core;

use package\core\autoload;

require_once 'init.php';

class databaseTest extends \PHPUnit_Framework_TestCase
{
	private $username = 'root', $password = 'Moppi666', $db;

	public function setUp()
	{
		$this->db	=	autoload::get('database', '\package\core\\', false, array('driver' => 'mysql', 'host' => '127.0.0.1', 'port' => 3306, 'username' => $this->username, 'password' => $this->password, 'database' => 'test'));
	}

	public function tearDown()
	{
		$this->db	=	null;
	}

	public function testQuefetch()
	{
		$getDatas	=	'
		SELECT
			COUNT(*) as count
		FROM
			`unit_test`
		LIMIT
			1;
		';

		$getDatas	=	$this->db->quefetch($getDatas);

		$this->assertTrue(($getDatas['count'] > 0));
	}


	public function testResultArray()
	{
		$getDatas	=	'
		SELECT
			`name`
		FROM
			`unit_test`
		LIMIT
			1;
		';

		$getDatas	=	$this->db->result_array($getDatas);

		if(!empty($getDatas))
		{
			foreach($getDatas as $data)
			{
				$this->assertNotEmpty($data['name']);
			}
		}
		else
		{
			$this->assertTrue(false);
		}
	}


	public function testMultiQuery()
	{
		$insertOne	=	'
		INSERT INTO
			`unit_test`
		SET
			`name`	=	"Insert1"
		;
		';

		$insertTwo	=	'
		INSERT INTO
			`unit_test`
		SET
			`name`	=	"Insert2"
		;
		';

		$this->assertTrue($this->db->multi_query($insertOne.$insertTwo));

		$deleteIn	=	'
		DELETE FROM
			`unit_test`
		WHERE
			`name` IN("Insert1", "Insert2")
		';

		$this->assertEquals(2, $this->db->exec($deleteIn));
	}


	public function testVersion()
	{
		$this->assertTrue((stripos($this->db->version(), 'mysqlnd') !== false));
	}


	public function testInsertId()
	{
		$insertOne	=	'
		INSERT INTO
			`unit_test`
		SET
			`name`	=	"Insert3";
		';

		$this->db->exec($insertOne);

		$this->assertTrue(($this->db->insert_id() > 0));

		$deleteOne	=	'
		DELETE FROM
			`unit_test`
		WHERE
			`name`	=	"Insert3";
		';

		$this->db->exec($deleteOne);
	}


	public function testSecQuery()
	{
		$getInfos	=	'
		SELECT
			`name`
		FROM
			`unit_test`
		WHERE
			`name`	=	?
		LIMIT
			1;
		';

		$getInfos	=	$this->db->secQuery($getInfos, array('UnitTest123'), true, true);

		$this->assertEquals('UnitTest123', $getInfos['name']);
	}


	public function testUpdateTable()
	{
		$updated	=	$this->db->updateTable('unit_test', array('modified_date' => 'NOW()'), array('name' => 'UnitTest123'));

		$this->assertTrue($updated);
	}


	public function testInsertTable()
	{
		$inserted	=	$this->db->insertTable('unit_test', array('name' => 'InsertTest', 'modified_date' => 'NOW()'));

		$this->assertTrue(($inserted != false));
	}


	public function testDeleteTable()
	{
		$deleted	=	$this->db->deleteTable('unit_test', array('name' => 'InsertTest'));

		$this->assertTrue($deleted);
	}
}
