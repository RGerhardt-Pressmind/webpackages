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

namespace system\core;

use system\core\Logger\logger;
use system\core\Logger\LoggerConfig;
use system\core\Template\template;
use system\core\Template\TemplateConfig;
use system\core\Transfer\transfer;
use system\core\Transfer\TransferConfig;

/**
 * @property \system\core\Template\Adapter\AdapterInterface $template
 * @property \system\core\Logger\Adapter\AdapterInterface $logger
 * @property Curl $curl
 * @property \system\core\Security\Adapter\AdapterInterface $security
 * @property \system\core\Transfer\Adapter\AdapterInterface $transfer
 */
class Loader
{
	/**
	 * Dynamic class
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get(string $name): mixed
	{
		$config	=	Registry::getInstance()->get('config');

		switch($name)
		{
			case 'template':

				if(!Registry::getInstance()->exist('template'))
				{
					$templateConfig	=	TemplateConfig::create($config['template']['engine'], $config['template']['path'], $config['template']['skin']);

					Registry::getInstance()->add('template', template::create($templateConfig));
				}

				return Registry::getInstance()->get('template');
			case 'logger':

				if(!Registry::getInstance()->exist('logger'))
				{
					$loggerConfig	=	LoggerConfig::create($config['logger']['engine'], $config['logger']['path']);

					Registry::getInstance()->add('logger', logger::create($loggerConfig));
				}

				return Registry::getInstance()->get('logger');
			case 'curl':

				if(!Registry::getInstance()->exist('curl'))
				{
					Registry::getInstance()->add('curl', new Curl());
				}

				return Registry::getInstance()->get('curl');
			case 'transfer':

				if(!Registry::getInstance()->exist('transfer'))
				{
					$transferConfig	=	TransferConfig::create($config['transfer']['engine'], $config['transfer']['connection']);

					Registry::getInstance()->add('transfer', transfer::create($transferConfig));
				}

				return Registry::getInstance()->get('transfer');
			case 'security':

				return Registry::getInstance()->get('security');
			default:

				die('Class "'.$name.'" not exist');
		}
	}
}
