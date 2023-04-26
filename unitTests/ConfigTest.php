<?php

namespace test;

require_once __DIR__.DIRECTORY_SEPARATOR.'../config.php';

use PHPUnit\Framework\TestCase;
use system\core\Config;

class ConfigTest extends TestCase
{
	/**
	 * @throws Config\exception\ConfigException
	 */
	public function testGetConfig()
	{
		$this->assertSame(Config::getConfig('development'), [
			'timezone'	=>	'Europe/Berlin',
			'database'	=>	[
				'engine'		=>	'mysql',
				'host'			=>	'',
				'username'		=>	'',
				'password'		=>	'',
				'database'		=>	'wp',
				'port'			=>	3306,
				'tablePrefix'	=>	''
			],
			'template'	=>	[
				'engine'	=>	'system\\core\\Template\\Adapter\\filesystem',
				'skin'		=>	'webpackages',
				'path'		=>	'system/view'
			],
			'logger'		=>	[
				'engine'	=>	'system\\core\\Logger\\Adapter\\filelogger',
				'path'		=>	'cache/logger'
			],
			'transfer'		=>	[
				'engine'		=>	'system\\core\\Transfer\\Adapter\\ftp',
				'connection'	=>	[
					'host'		=>	'',
					'username'	=>	'',
					'password'	=>	'',
					'ssl'		=>	false,
					'port'		=>	21,
					'passiv'	=>	false
				]
			],
			'security'	=>	[
				'engine'	=>	'system\\core\\Security\\Adapter\\sanitizer'
			],
			'language'	=>	[
				'default'	=>	'de_DE',
				'path'		=>	'system/language'
			],
			'plugin' 	=> 	[],
			'router'	=>	[
				'engine'	=>	'system\\core\\Router\\Adapter\\modRewrite'
			],
			'defaultClass'	=>	'welcome',
			'defaultMethod'	=>	'overview'
		]);

		try{
			Config::getConfig('foo');
		}catch(Config\exception\ConfigException $e){
			$this->assertEquals('config.json has not "foo" environment', $e->getMessage());
		}

		Config::clearConfig();

		rename(__DIR__.DIRECTORY_SEPARATOR.'../config.json', __DIR__.DIRECTORY_SEPARATOR.'../config_rename.json');

		try{
			Config::getConfig('foo2');
		}catch(Config\exception\ConfigException $e){
			$this->assertEquals('config.json in root path not exist', $e->getMessage());
		}

		Config::clearConfig();

		file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'../config.json', 'null');

		try{
			Config::getConfig('unitTest');
		}catch(Config\exception\ConfigException $e){
			$this->assertEquals('config.json content is not json', $e->getMessage());
		}

		rename(__DIR__.DIRECTORY_SEPARATOR.'../config_rename.json', __DIR__.DIRECTORY_SEPARATOR.'../config.json');
	}
}
