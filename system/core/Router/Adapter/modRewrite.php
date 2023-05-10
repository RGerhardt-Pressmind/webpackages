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

namespace system\core\Router\Adapter;

use system\core\Plugin;
use system\core\Registry;

class modRewrite implements AdapterInterface
{
	/**
	 * Parse mod rewrite route
	 */
	public function parse()
	{
		$config		=	Registry::getInstance()->get('config');

		/**
		 * @var \system\core\Security\Adapter\AdapterInterface $security
		 */
		$security	=	Registry::getInstance()->get('security');

		$class	=	$config['controller']['defaultClass'];
		$method	=	$config['controller']['defaultMethod'];

		if(!empty($_GET['c']))
		{
			$class	=	$security->validate($_GET['c']);
		}

		if(!empty($_GET['m']))
		{
			$method	=	$security->validate($_GET['m']);
		}

		$controllerPath	=	ROOT.$config['controller']['path'].SEP;
		$controllerFile	=	$controllerPath.$class.'.php';

		if(file_exists($controllerFile))
		{
			require_once $controllerFile;

			$classNamespace	=	'controller\\'.$class;

			list($classNamespace, $class, $method) = Plugin::call_filter('beforeBootstrap', [$classNamespace, $class, $method]);

			Plugin::hook('beforeBootstrap', [$classNamespace, $class, $method]);

			$instance	=	new $classNamespace();

			if(method_exists($instance, $method))
			{
				ob_start();

				$instance->$method();

				$content	=	ob_get_contents();

				ob_end_clean();

				$content	=	Plugin::call_filter('afterBootstrap', $content);

				Plugin::hook('afterBootstrap', [$content]);

				echo $content;
			}
			else
			{
				echo 'Failed, method "'.$method.'" not exist';
				exit;
			}
		}
		else
		{
			echo 'Failed, controller "'.$class.'" not exist';
			exit;
		}
	}
}
