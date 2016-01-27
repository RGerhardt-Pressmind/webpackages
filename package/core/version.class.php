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
    
    @category   version.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package\core;


use package\implement\IStatic;

class version implements IStatic
{
	/**
	 * Zum initialisieren von Daten
	 */
	public static function init(){}

	const VERSION	=	'0.8.0'; //Versionsnummer
	const REPO		=	'https://github.com/Robbyn666/webpackage/archive/master.zip'; // ZIP Download Link
	const COMMITS	=	'https://api.github.com/repos/Robbyn666/webpackage/commits'; // API Link zu allen Commits von Robbyn666
	const COMMITTER	=	'Robbyn666'; //Commiter Name
}