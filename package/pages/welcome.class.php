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

    @category   welcome.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

use package\load_functions;

class welcome extends load_functions
{
	public function __construct()
	{
		\package\benchmark::start_point(true);

		parent::__construct(array(load_functions::$LOAD_TEMPLATE, load_functions::$LOAD_LANGUAGE));
	}


	/**
	 * Ändert die Standard Sprache
	 *
	 * @return void
	 */
	public function change_language()
	{
		$lng	=	\package\security::url('lng', 'GET', 'string');

		$_SESSION['default_lng']	=	$lng;

		$this->template->loc(HTTP);
	}

	/**
	 * Willkommen beim Framework
	 *
	 * @return void
	 */
	public function hello()
	{
		if(!empty($_SESSION['default_lng']))
		{
			\package\language::set_language($_SESSION['default_lng']);
		}
		else
		{
			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

			switch($lang)
			{
				case 'fr':

					$_SESSION['default_lng']	=	'fr_FR';

				break;
				case 'it':

					$_SESSION['default_lng']	=	'it_IT';

				break;
				case 'en':

					$_SESSION['default_lng']	=	'en_US';

				break;
				default:

					$_SESSION['default_lng']	=	'de_DE';

				break;
			}

			\package\language::set_language($_SESSION['default_lng']);
		}

		$this->template->display('template/hello.php', false);
	}
}