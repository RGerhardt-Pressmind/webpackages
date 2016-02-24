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
 *  @subpackage core
 *  @author	    Robbyn Gerhardt
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace package\implement;


interface IPlugin 
{
	/**
	 * Übergibt alle Systemklassen
	 *
	 * @param array $allClasses Übergibt alle Instanzierten Systemklassen
	 */
	public function setAllClasses($allClasses);

	/**
	 * Der Name der zum Ansprechen des Plugins genutzt werden soll
	 *
	 * @return string
	 */
	public function getClassName();

	/**
	 * Wird direkt nach der Instanzierung alles Plugins aufgerufen.
	 *
	 * @return mixed
	 */
	public function construct();
}