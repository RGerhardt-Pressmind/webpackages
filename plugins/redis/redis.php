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

namespace plugins;

use system\core\Plugin;
use system\core\Registry;
use plugins\Adapter\AdapterPlugins;

class redis implements AdapterPlugins
{
	/**
	 * @var mixed
	 */
	private mixed $redis = null;

	private mixed $config = null;


	public function pluginParameter(array $parameter)
	{
		// TODO: Implement pluginParameter() method.
	}

	public function registerHooks()
	{
		$this->config	=	Registry::getInstance()->get('config');

		Plugin::register('init', [$this, 'getRedis']);
		Plugin::add_filter('changeLanguage', [$this, 'changeLanguage']);
		Plugin::add_filter('beforeValidateSecurity', [$this, 'beforeValidateSecurity']);
		#Plugin::register('beforeBootstrap', [$this, 'beforeBootstrap']);
		#Plugin::register('afterBootstrap', [$this, 'afterBootstrap']);
	}


	public function beforeValidateSecurity($str, $convert)
	{
		echo $str;

		return [$str, $convert];
	}

	/**
	 * Change language
	 *
	 * @param string $lng
	 *
	 * @return string
	 */
	public function changeLanguage(string $lng): string
	{
		#return 'de_DE';
		return $lng;
	}

	/**
	 * After init webpackages, connect to redis
	 */
	public function getRedis()
	{
		if(class_exists('\Redis'))
		{
			$this->redis	=	new \Redis();

			$redisHost	=	($this->config['redis']['host'] ?? '127.0.0.1');
			$redisPort	=	($this->config['redis']['port'] ?? 6379);

			try{
				if(!$this->redis->connect($redisHost, $redisPort))
				{
					die('Redis can not be connect');
				}
			}catch (\Throwable){
				$this->redis	=	null;
			}
		}
	}

	/**
	 * Before template parse, control redis cache
	 */
	public function beforeBootstrap(): void
	{
		$key	=	md5(serialize($_REQUEST).($_SESSION['lng'] ?? $this->config['langauge']['default']));

		if($this->redis?->exists($key) && !isset($_REQUEST['no-cache']))
		{
			header('wp-redis-cache: active');
			header('wp-redis-cache-ttl: '.$this->redis->ttl($key));
			echo $this->redis->get($key);
			exit;
		}
	}

	/**
	 * After template parse, save in redis cache
	 *
	 * @param mixed $content
	 */
	public function afterBootstrap(mixed $content): void
	{
		$hash	=	md5(serialize($_REQUEST).($_SESSION['lng'] ?? $this->config['langauge']['default']));

		$this->redis?->setex($hash, 6000, $content);
	}
}
