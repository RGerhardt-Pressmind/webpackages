<?php
/**
 *  Copyright (C) 2010 - 2017  <Robbyn Gerhardt>
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
 * @subpackage    core
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2018.0
 * @filesource
 */

use package\core\security;
use package\core\autoload;
use package\core\plugins;

require 'init.php';

$c = DEFAULT_CLASS;
$m = DEFAULT_METHODE;

if(!empty($_GET['c']))
{
	$c = security::url('c');
}

if(!empty($_GET['m']))
{
	$m = security::url('m');
}

$class = searchInFolder(PAGE_DIR, $c);

if(!$class)
{
	$class	=	getPluginControler($c);
}

plugins::callAction('wp_'.$c.'_'.$m);
plugins::callAction('wp_'.$c.'_'.$m.'_before');

plugins::$callDynamicInfos	=	array('class' => $c, 'methode' => $m);

plugins::callAction('wp_all_dynamic_before', array($c, $m));

if(!empty($content))
{
	if(!is_bool($content))
	{
		echo $content;
	}


	exit;
}

if($class)
{
	if(method_exists($class, $m))
	{
		ob_start();
		$class->$m();
		$content	=	ob_get_contents();
		ob_end_clean();

		echo $content;

		plugins::callAction('wp_'.$c.'_'.$m.'_after', array($content));
		plugins::callAction('wp_all_dynamic_after', array($c, $m, $content));
	}
	else
	{
		throw new Exception('Methode "'.$m.'" in class "'.$c.'" not exists');
	}
}
else
{
	throw new Exception('Class "'.$c.'" not exists');
}

function searchInFolder($folder, $c)
{
	$directory = new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator  = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $item)
		{
			if($item->isFile() && $item->getFilename() == $c.'.class.php')
			{
				return autoload::get($c);
			}
		}
	}

	return null;
}


function getPluginControler($c)
{
	$directory = new RecursiveDirectoryIterator(PLUGIN_DIR, RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator  = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

	if(iterator_count($iterator) > 0)
	{
		foreach($iterator as $item)
		{
			if($item->isFile() && $item->getFilename() == $c.'.class.php')
			{
				$namespace	=	null;
				$config		=	$item->getPath().SEP.'config.ini';

				if(file_exists($config))
				{
					$config	=	parse_ini_file($config);

					if(isset($config['namespace']))
					{
						$namespace	=	trim($config['namespace'], '\\').'\\';
					}

					if(isset($config['active']) && ($config['active'] == 0 || $config['active'] == false))
					{
						continue;
					}
				}

				require_once $item->__toString();

				return autoload::get($c, $namespace);
			}
		}
	}

	return null;
}