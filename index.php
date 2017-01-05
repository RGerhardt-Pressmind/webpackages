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
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

require 'init.php';

$c = DEFAULT_CLASS;
$m = DEFAULT_METHODE;

if(!empty($_GET['c']))
{
	$c = \package\core\security::url('c', 'GET', 'string');
}

if(!empty($_GET['m']))
{
	$m = \package\core\security::url('m', 'GET', 'string');
}

$class = searchInFolder(PAGE_DIR, $c);

if(!$class)
{
	$class	=	getPluginControler($c);
}

if($class)
{
	if(method_exists($class, $m))
	{
		$class->$m();
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
				return \package\core\autoload::get($c);
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

				return \package\core\autoload::get($c, $namespace);
			}
		}
	}

	return null;
}