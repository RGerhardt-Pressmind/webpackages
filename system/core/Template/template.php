<?php
/**
 *  Copyright (C) 2010 - 2021  <Robbyn Gerhardt>
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
 * @copyright     Copyright (c) 2010 - 2021
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\Template;

use system\core\Template\Adapter\AdapterInterface;

class template
{
	/**
	 * @param TemplateConfig $config
	 *
	 * @return AdapterInterface
	 */
	public static function create(TemplateConfig $config): AdapterInterface
	{
		$class		=	$config->engine;

		if(class_exists($class))
		{
			/**
			 * @var AdapterInterface $template
			 */
			$template	=	new $class();
		}
		else
		{
			echo 'Failed, template engine "'.$class.'" not exist';
			exit;
		}

		$template->create($config);

		return $template;
	}
}
