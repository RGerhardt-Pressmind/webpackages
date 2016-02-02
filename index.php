<?php
/**
 *  Copyright (C) 2010 - 2016  <Robbyn Gerhardt>
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
 *  @package	Webpackages
 *  @subpackage core
 *  @author	    Robbyn Gerhardt
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

require 'init.php';

$c	=	'welcome';
$m	=	'hello';

if(empty($_GET['c']) === false)
{
	$c	=	\package\core\security::url('c', 'GET', 'string');
}

if(empty($_GET['m']) === false)
{
	$m	=	\package\core\security::url('m', 'GET', 'string');
}

$class	=	searchInFolder(PAGE_DIR, $c);

if($class !== null)
{
	if(method_exists($class, $m) === true)
	{
		$class->$m();
	}
	else
	{
		throw new Exception('Methode '.$m.' not exists');
	}
}
else
{
	throw new Exception('Class '.$c.' not exists');
}

function searchInFolder($folder, $c)
{
	$directory	=	new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS);
	$iterator	=	new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

	foreach($iterator as $item)
	{
		if($item->isFile() === true && $item->getFilename() == $c.'.class.php')
		{
			return	\package\core\autoload::get($c);
		}
	}

	return null;
}