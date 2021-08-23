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

namespace system\core;

use system\core\Logger\logger;
use system\core\Logger\LoggerConfig;
use system\core\Template\Adapter\AdapterInterface;
use system\core\Template\template;
use system\core\Template\TemplateConfig;

class Loader
{
	/**
	 * @var AdapterInterface
	 */
	public mixed $template;

	/**
	 * @var \system\core\Logger\Adapter\AdapterInterface|null
	 */
	public mixed $logger;

	/**
	 * @var Curl
	 */
	public mixed $curl;

	/**
	 * @var \system\core\Security\Adapter\AdapterInterface
	 */
	public mixed $security;

	public function __construct()
	{
		// call hook before register all loader
		Plugin::hook('beforeRegisterLoader');

		$config	=	Registry::getInstance()->get('config');

		// Logger
		$loggerConfig	=	LoggerConfig::create($config['logger']['engine'], $config['logger']['path']);

		Registry::getInstance()->add('logger', logger::create($loggerConfig));

		$this->logger	=	Registry::getInstance()->get('logger');


		// Template
		$templateConfig	=	TemplateConfig::create($config['template']['engine'], $config['template']['path'], $config['template']['skin']);

		Registry::getInstance()->add('template', template::create($templateConfig));

		$this->template	=	Registry::getInstance()->get('template');


		// cUrl
		Registry::getInstance()->add('curl', new Curl());

		$this->curl	=	Registry::getInstance()->get('curl');


		// security
		$this->security	=	Registry::getInstance()->get('security');

		// call hook after register all loader
		Plugin::hook('registerLoader');
	}
}
