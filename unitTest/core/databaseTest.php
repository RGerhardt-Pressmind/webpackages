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
    
    @category   databaseTest.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace unitTest\core;

use package\autoload;

require_once 'init.php';

class databaseTest extends \PHPUnit_Framework_TestCase
{
	private $dsn	=	'mysql:dbname=test;host=127.0.0.1;port=3307', $username = 'root', $password = '';

	public function testQuefetch()
	{
		$db	=	autoload::get('database', '\package\\', false, array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password, 'type' => 'mysql'));

		$getDatas	=	'
		SELECT
			COUNT(*) as count
		FROM
			`unit_test`
		LIMIT
			1;
		';

		$getDatas	=	$db->quefetch($getDatas);

		$this->assertTrue(($getDatas['count'] > 0));
	}


	public function testResultArray()
	{
		$db	=	autoload::get('database', '\package\\', false, array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password, 'type' => 'mysql'));

		$getDatas	=	'
		SELECT
			`name`
		FROM
			`unit_test`
		LIMIT
			1;
		';

		$getDatas	=	$db->result_array($getDatas);

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
		$db	=	autoload::get('database', '\package\\', false, array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password, 'type' => 'mysql'));

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

		$this->assertTrue($db->multi_query($insertOne.$insertTwo));

		$deleteIn	=	'
		DELETE FROM
			`unit_test`
		WHERE
			`name` IN("Insert1", "Insert2")
		';

		$this->assertEquals(2, $db->exec($deleteIn));
	}


	public function testVersion()
	{
		$db	=	autoload::get('database', '\package\\', false, array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password, 'type' => 'mysql'));

		$this->assertTrue((stripos($db->version(), 'mysqlnd') !== false));
	}


	public function testInsertId()
	{
		$db	=	autoload::get('database', '\package\\', false, array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password, 'type' => 'mysql'));

		$insertOne	=	'
		INSERT INTO
			`unit_test`
		SET
			`name`	=	"Insert3";
		';

		$db->exec($insertOne);

		$this->assertTrue(($db->insert_id() > 0));

		$deleteOne	=	'
		DELETE FROM
			`unit_test`
		WHERE
			`name`	=	"Insert3";
		';

		$db->exec($deleteOne);
	}


	public function testSecQuery()
	{
		$db	=	autoload::get('database', '\package\\', false, array('dsn' => $this->dsn, 'username' => $this->username, 'password' => $this->password, 'type' => 'mysql'));

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

		$getInfos	=	$db->secQuery($getInfos, array('UnitTest123'), true, true);

		$this->assertEquals('UnitTest123', $getInfos['name']);
	}
}
