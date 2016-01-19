<?php
/*
    Copyright (C) 2015  <Robbyn Gerhardt>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    @category   index.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

require 'init.php';

$c	=	'welcome';
$m	=	'hello';

if(isset($_GET['c']) === true)
{
	$c	=	\package\security::url('c', 'GET', 'string');
}

if(isset($_GET['m']) === true)
{
	$m	=	\package\security::url('m', 'GET', 'string');
}

function searchInFolder($folder, $c)
{
	$iterator	=	new \RecursiveDirectoryIterator($folder, \RecursiveDirectoryIterator::SKIP_DOTS);

	foreach($iterator as $item)
	{
		$file	=	new SplFileInfo($item);

		if($file->isFile() === true)
		{
			if($file->getFilename() == $c.'.class.php')
			{
				return	\package\autoload::get($c);
			}
		}
		else
		{
			$back	=	searchInFolder($file, $c);

			if($back != null)
			{
				return $back;
			}
		}
	}

	return null;
}


if($c == 'update')
{
	$install	=	\package\autoload::get('update');

	if($m == 'hello')
	{
		$m	=	'step1';
	}

	if(method_exists($install, $m) === true)
	{
		$install->$m();
	}
	else
	{
		throw new Exception('Methode '.$m.' not exists');
	}
}
else
{
	$class	=	searchInFolder(PAGE_DIR, $c);

	if($class != null)
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
}