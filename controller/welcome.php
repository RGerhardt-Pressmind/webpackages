<?php
/**
 *  Copyright (C) 2010 - 2022  <Robbyn Gerhardt>
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
 * @package       webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2022
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace controller;

use system\core\Http;
use system\core\Language;
use system\core\Loader;

class welcome extends Loader
{
	public function change_language()
	{
		$_SESSION['lng']	=	$_GET['lng'];

		Http::location(Http::getURL());
	}

	public function overview()
	{
		if(!empty($_SESSION['lng']))
		{
			Language::changeLanguage($_SESSION['lng']);
		}

		$params	=	[
		];

		$this->template->parse($params, 'overview');
	}
}
