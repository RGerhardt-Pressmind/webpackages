<?php
/**
 *  Copyright (C) 2010 - 2018  <Robbyn Gerhardt>
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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2018, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

class skin implements \package\implement\IPlugin
{
	public function getApplyPlugin()
	{
		\package\core\plugins::setFilter('wp_template_setSkin', array($this, 'setSkin'));
	}

	/**
	 * @param array $args array('parameter' => [0 => value1,1 => value2,2 => value3,3 => value4,...])
	 *
	 * @return mixed
	 */
	public function setSkin($args)
	{
		$args['parameter'][0]	=	TEMPLATE_DEFAULT_SKIN;

		return $args;
	}

	public function setAllClasses($allClasses)
	{
		// TODO: Implement setAllClasses() method.
	}
}