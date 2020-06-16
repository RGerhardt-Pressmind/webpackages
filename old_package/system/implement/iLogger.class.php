<?php
/**
 *  Copyright (C) 2010 - 2020  <Robbyn Gerhardt>
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
 *  @author	    Robbyn Gerhardt
 *  @copyright	Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2020.0
 *  @filesource
 */

namespace package\system\implement;


interface iLogger 
{
	/**
	 * @param $msg Der String der geloggt werden soll
	 * @param int $code Der Code des Logs
	 * @param string $level Der Level des Logs
	 * @return mixed
	 */
	public function _write_log($msg, $code = 0, $level = 'info');

	/**
	 * Löscht einen Log
	 *
	 * @return mixed
	 */
	public function _delete_log();

	/**
	 * Liest den Log aus
	 *
	 * @return mixed
	 */
	public function _read_log();
} 
