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
 * @package       Webpackages
 * @subpackage    core
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2020.0
 * @filesource
 */

namespace package\controllers;

use package\system\core\load_functions;
use package\system\core\benchmark;
use package\system\core\template;
use package\system\core\security;
use package\system\core\url;
use package\system\core\language;

class welcome extends load_functions
{
	public function __construct()
	{
		benchmark::start_point(true);

		parent::__construct();

		$this->loadScripts();
		$this->loadStyles();
	}

	/**
	 * Load styles to template header
	 */
	private function loadStyles()
	{
		template::appendStyle('bootstrap', template::getCssPath('bootstrap.min.css'), '', 10, template::POSITION_HEADER, true);
		template::appendStyle('font-awesome', template::getCssPath('font-awesome.min.css'), '', 10, template::POSITION_HEADER, true);
		template::appendStyle('main', template::getCssPath('main.css'), '', 10, template::POSITION_HEADER, true);
	}

	/**
	 * Load scripts to template header
	 *
	 * @return void
	 */
	private function loadScripts()
	{
		template::appendScript('jquery', template::getJsPath('jquery.js'), '', 10, template::POSITION_FOOTER);
		template::appendScript('bootstrap', template::getJsPath('bootstrap.min.js'), '', 10, template::POSITION_FOOTER, true);
		template::appendScript('fusioncharts', template::getJsPath('fusioncharts.js'), '', 10, template::POSITION_FOOTER);
		template::appendScript('fusioncharts.charts', template::getJsPath('fusioncharts.charts.js'), '', 10, template::POSITION_FOOTER, true);
		template::appendScript('main', template::getJsPath('main.js'), '', 10, template::POSITION_FOOTER, true);
	}

	/**
	 * Ändert die Standard Sprache
	 *
	 * @return void
	 */
	public function change_language()
	{
		$lng = security::url('lng', 'GET', 'string');

		$_SESSION['default_lng'] = $lng;

		url::loc(HTTP);
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
			language::set_language($_SESSION['default_lng']);
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

			language::set_language($_SESSION['default_lng']);
		}

		$this->template->setData([
			'USER'	=>	$this->USER
		]);
		$this->template->display('template/hello.php');
	}
}
