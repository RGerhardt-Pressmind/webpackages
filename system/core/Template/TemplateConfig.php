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

namespace system\core\Template;

class TemplateConfig
{
	public string $templatePath;
	public string $skin;
	public string $engine;

	private static mixed $_self	=	null;

	/**
	 * Create template config
	 *
	 * @param string $engine
	 * @param string $templatePath
	 * @param string $skin
	 *
	 * @return TemplateConfig
	 */
	public static function create(string $engine, string $templatePath, string $skin): TemplateConfig
	{
		if(is_null(self::$_self))
		{
			self::$_self				=	new self();
			self::$_self->engine		=	$engine;
			self::$_self->templatePath	=	$templatePath;
			self::$_self->skin			=	$skin;
		}

		return self::$_self;
	}
}
