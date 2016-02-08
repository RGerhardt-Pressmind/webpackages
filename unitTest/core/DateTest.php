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
use package\core\Date;

require_once 'init.php';

class DateTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		autoload::get('Date', '\package\core\\', true);
	}

	public function testNow()
	{
		$current	=	time();

		$this->assertTrue((Date::now() >= $current));
	}

	public function testGetTimestampByDate()
	{
		$time	=	time();
		$date	=	date('Y-m-d H:i:s', $time);

		$this->assertEquals($time, Date::get_timestamp_by_date($date));
	}


	public function testGetDateByTimestamp()
	{
		$this->assertEquals(date('Y-m-d'), Date::get_date_by_timestamp(time()));
	}


	public function testGetEasterDayByYear()
	{
		$this->assertEquals('2015-04-05', Date::get_easter_day_by_year(2015));
		$this->assertEquals(1428184800, Date::get_easter_day_by_year(2015, true));
	}


	public function testGetNationHolidaysByYear()
	{
		$holidays_germany	=	Date::get_nation_holidays_by_year(2015, Date::NATION_GERMANY);
		$this->assertEquals('8b7f7675e86aa85c2d221210bd0b18c4', md5(serialize($holidays_germany)));

		$holidays_austrian	=	Date::get_nation_holidays_by_year(2015, Date::NATION_AUSTRIAN);
		$this->assertEquals('36930ac2aff72bd802d832f8f9bebfd1', md5(serialize($holidays_austrian)));

		$holidays_denmark	=	Date::get_nation_holidays_by_year(2015, Date::NATION_DENMARK);
		$this->assertEquals('00110de3695d969759aa059146b00c9e', md5(serialize($holidays_denmark)));

		$holidays_french	=	Date::get_nation_holidays_by_year(2015, Date::NATION_FRENCH);
		$this->assertEquals('e22fbda3a2944ed9715b0963b2ab82e6', md5(serialize($holidays_french)));

		$holidays_italian	=	Date::get_nation_holidays_by_year(2015, Date::NATION_ITALIAN);
		$this->assertEquals('02c878f3af52b2d4aa5f64b6bf4aea28', md5(serialize($holidays_italian)));

		$holidays_norwegian	=	Date::get_nation_holidays_by_year(2015, Date::NATION_NORWEGIAN);
		$this->assertEquals('63b5fe058fe876c4d59fb1248e3411f2', md5(serialize($holidays_norwegian)));

		$holidays_polish	=	Date::get_nation_holidays_by_year(2015, Date::NATION_POLISH);
		$this->assertEquals('fc9ffea8eefa2fc3e103cce29d9eed2a', md5(serialize($holidays_polish)));

		$holidays_swedish	=	Date::get_nation_holidays_by_year(2015, Date::NATION_SWEDISH);
		$this->assertEquals('b196e515f67891f8c04e9c8e2bcec41b', md5(serialize($holidays_swedish)));
	}


	public function testGetAllSaintsDay()
	{
		$this->assertEquals('2015-10-31', Date::get_all_saints_day(2015)->format('Y-m-d'));
	}


	public function testGetMidSummerDay()
	{
		$this->assertEquals('2015-06-20', Date::get_mid_summer_day(2015)->format('Y-m-d'));
	}


	public function testIsYearLeapYear()
	{
		$this->assertFalse(Date::is_year_leap_year(2015));
		$this->assertTrue(Date::is_year_leap_year(2016));
	}


	public function testGetDaysInMonth()
	{
		$this->assertEquals(31, Date::get_days_in_month(1, 2015));
		$this->assertEquals(28, Date::get_days_in_month(2, 2015));
		$this->assertEquals(31, Date::get_days_in_month(3, 2015));
		$this->assertEquals(30, Date::get_days_in_month(4, 2015));
		$this->assertEquals(31, Date::get_days_in_month(5, 2015));
		$this->assertEquals(30, Date::get_days_in_month(6, 2015));
		$this->assertEquals(31, Date::get_days_in_month(7, 2015));
		$this->assertEquals(31, Date::get_days_in_month(8, 2015));
		$this->assertEquals(30, Date::get_days_in_month(9, 2015));
		$this->assertEquals(31, Date::get_days_in_month(10, 2015));
		$this->assertEquals(30, Date::get_days_in_month(11, 2015));
		$this->assertEquals(31, Date::get_days_in_month(12, 2015));
	}
}
