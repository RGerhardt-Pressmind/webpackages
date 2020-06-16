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
 * @package       Webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\system\plugins\redis;

use package\system\core\plugins;
use package\system\implement\IPlugin;

class redis implements IPlugin
{
	/**
	 * @var \Redis
	 */
	private $redis;

	/**
	 * @var array
	 */
	private $ini = array();

	/**
	 * @var bool
	 */
	private $isConnected = false;

	/**
	 * redis constructor.
	 */
	public function __construct()
	{
		if(class_exists('\Redis'))
		{
			$this->ini = parse_ini_file(dirname(__FILE__).SEP.'config.ini');

			$this->redis = new \Redis();

			if(@$this->redis->connect($this->ini['redis_host'], $this->ini['redis_port']))
			{
				$this->isConnected = true;
			}
		}
	}

	public function getApplyPlugin()
	{
		plugins::setAction('wp_template_display', array($this, 'template_display'));
		plugins::setAction('wp_template_display_after', array($this, 'template_display_after'));

		plugins::setAction('wp_all_dynamic_before', array($this, 'callAllDynamic'));
	}

	/**
	 * After call
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	public function callAllDynamic($args)
	{
		if($this->isConnected)
		{
			if($args['methode'] == 'change_language')
			{
				$this->redis->delete($this->redis->keys('*'));
			}
		}
	}

	/**
	 * Save template in redis
	 *
	 * @param array $args
	 *
	 * @return void
	 */
	public function template_display_after($args)
	{
		if($this->isConnected)
		{
			$content	=	array_pop($args['args']);

			$this->redis->setex($args['class'].'_'.$args['methode'], $this->ini['redis_timeout'], $content);
		}
	}

	/**
	 * Get template from redis
	 *
	 * @param array $args
	 * @return void
	 */
	public function template_display($args)
	{
		if($this->isConnected)
		{
			$content	=	$this->redis->get($args['class'].'_'.$args['methode']);

			if($content !== false)
			{
				echo '<script>console.log("Load from redis")</script>';
				echo $content;
				exit;
			}
			else
			{
				echo '<script>console.log("Not load from redis")</script>';
			}
		}
	}

	/**
	 * @param array $allClasses
	 */
	public function setAllClasses($allClasses)
	{
		// TODO: Implement setAllClasses() method.
	}
}
