<?php
/*
    Copyright (C) 2015  <Robbyn Gerhardt>

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

    @category   iLogger.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package\implement;


interface iLogger 
{
	/**
	 * @param $msg Der String der geloggt werden soll
	 * @param int $code Der Code des Logs
	 * @param string $level Der Level des Logs
	 * @return mixed
	 */
	public function writeLog($msg, $code = 0, $level = 'info');

	/**
	 * Löscht einen Log
	 *
	 * @return mixed
	 */
	public function deleteLog();

	/**
	 * Liest den Log aus
	 *
	 * @return mixed
	 */
	public function readLog();
} 