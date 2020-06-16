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
 * @package       truetravel_bootstrap
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, pressmind GmbH (https://www.pressmind.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          https://www.pressmind.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace plugins;

use system\core\Plugin;
use system\plugins\Adapter\AdapterPlugins;

class redis implements AdapterPlugins
{
	/**
	 * @var \Redis|null
	 */
	private $redis;

	public function registerHooks()
	{
		Plugin::register('init', [$this, 'getRedis']);
		Plugin::register('beforeBootstrap', [$this, 'beforeBootstrap']);
		Plugin::register('afterBootstrap', [$this, 'afterBootstrap']);
	}

	/**
	 * After init webpackages, connect to redis
	 */
	public function getRedis()
	{
		if(class_exists('\Redis') && 1 == 2)
		{
			$this->redis	=	new \Redis();

			if(!$this->redis->connect('127.0.0.1', 6379))
			{
				die('Redis can not be connect');
			}
		}
	}

	/**
	 * Before template parse, control redis cache
	 */
	public function beforeBootstrap()
	{
		$hash	=	md5(serialize($_REQUEST));

		if($this->redis && $this->redis->exists($hash) && !isset($_REQUEST['no-cache']))
		{
			echo '<script>console.log("Load from redis")</script>';
			echo $this->redis->get($hash);
			exit;
		}
	}

	/**
	 * After template parse, save in redis cache
	 *
	 * @param array $args
	 */
	public function afterBootstrap($args)
	{
		$hash	=	md5(serialize($_REQUEST));

		if($this->redis)
		{
			$this->redis->setex($hash, 6000, $args['content']);
		}
	}
}
