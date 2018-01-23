<?php
/**
 *  Copyright (C) 2010 - 2017  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2018.0
 * @filesource
 */

use package\core\load_functions;

class welcome extends load_functions
{
	public function __construct()
	{
		\package\core\benchmark::start_point(true);

		parent::__construct();

		\package\core\template::appendScript('jquery', \package\core\template::getJsPath('jquery.js'), '', 10, 'footer');
		\package\core\template::appendScript('bootstrap', \package\core\template::getJsPath('bootstrap.min.js'), '', 10, 'footer');
		\package\core\template::appendScript('fusioncharts', \package\core\template::getJsPath('fusioncharts.js'), '', 10, 'footer');
		\package\core\template::appendScript('fusioncharts.charts', \package\core\template::getJsPath('fusioncharts.charts.js'), '', 10, 'footer');
		\package\core\template::appendScript('main', \package\core\template::getJsPath('main.js'), '', 10, 'footer');

		\package\core\template::appendStyle('bootstrap', \package\core\template::getCssPath('bootstrap.min.css'));
		\package\core\template::appendStyle('font-awesome', \package\core\template::getCssPath('font-awesome.min.css'));
		\package\core\template::appendStyle('main', \package\core\template::getCssPath('main.css'));
	}

	/**
	 * Ändert die Standard Sprache
	 *
	 * @return void
	 */
	public function change_language()
	{
		$lng = \package\core\security::url('lng', 'GET', 'string');

		$_SESSION['default_lng'] = $lng;

		\package\core\url::loc(HTTP);
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
			\package\core\language::set_language($_SESSION['default_lng']);
		}
		else
		{
			if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			}
			else
			{
				$lang = 'de';
			}

			switch($lang)
			{
				case 'fr':

					$_SESSION['default_lng'] = 'fr_FR';

				break;
				case 'it':

					$_SESSION['default_lng'] = 'it_IT';

				break;
				case 'en':

					$_SESSION['default_lng'] = 'en_US';

				break;
				default:

					$_SESSION['default_lng'] = 'de_DE';

				break;
			}

			\package\core\language::set_language($_SESSION['default_lng']);
		}

		$this->template->display('template/hello.php');
	}
}