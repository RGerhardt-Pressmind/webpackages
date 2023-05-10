<?php
/**
 *  Copyright (C) 2010 - 2023  <Robbyn Gerhardt>
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
 * @copyright     Copyright (C) 2010 - 2023
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\Config;

class LanguageConfig
{
	public string $language			=	'';
	public string $languageFilePath	=	'';

	private static mixed $_self		=	null;

	public static function create(string $language, string $languageFilePath): LanguageConfig
	{
		if(is_null(self::$_self))
		{
			self::$_self					=	new self();
			self::$_self->language			=	$language;
			self::$_self->languageFilePath	=	ROOT.trim(trim($languageFilePath, '/'), '\\').SEP;
		}

		return self::$_self;
	}
}
