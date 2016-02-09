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

$controllPaths	=	array(LANGUAGE_PATH, CACHE_PATH, TEMPLATE_DIR, PACKAGE_DIR, DYNAMIC_DIR, PAGE_DIR, PLUGIN_DIR, CORE_DIR, IMPLEMENT_DIR, LIB_DIR);

foreach($controllPaths as $path)
{
	if($path != '')
	{
		if(is_dir($path) === false)
		{
			$save	=	mkdir($path, 0777, true);

			if($save === false)
			{
				throw new Exception('Folder '.$path.' cant not created');
			}
		}

		$permission	=	substr(sprintf('%o', fileperms($path)), -4);

		if($permission !== '0777' && $permission !== '777')
		{
			@chmod($path, 0777);
		}

		clearstatcache();
	}
}

