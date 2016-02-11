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
 * @package       Webpackages
 * @subpackage    core
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\implement\IStatic;
use package\system\core\initiator;

class version extends initiator implements IStatic
{
	/**
	 * Zum initialisieren von Daten
	 */
	public static function init()
	{
	}

	/**
	 * Versionsnummer
	 */
	const VERSION = '2.0.0';

	/**
	 * Kontrolliert den übergebenen Wert mit der installierten PHP Version.
	 *
	 * @param string $version Kontrolliert ob die übergebene PHP Version größer, kleiner oder gleich der installierten
	 *                        Version ist.
	 *
	 * @return bool
	 */
	public static function _is_php($version)
	{
		if(version_compare(PHP_VERSION, $version) >= 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}