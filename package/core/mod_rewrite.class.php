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
    
    @category   mod_rewrite.class.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 webpackage
	@license    http://www.gnu.org/licenses/
*/

namespace package;


use package\implement\IStatic;

class mod_rewrite implements IStatic
{
	public static $isModRewriteActiv	=	false, $useModRewrite = false, $useFileExtension = 'html';

	public static function init()
	{
		if(function_exists('apache_get_modules'))
		{
			$allModules	=	apache_get_modules();

			if(in_array('mod_rewrite', $allModules))
			{
				self::$isModRewriteActiv	=	true;
			}
		}
		else
		{
			self::$isModRewriteActiv	=	true;
		}

		self::setUseModRewrite(USE_MOD_REWRITE);
	}

	/**
	 * Setzt ModRewrite auf aktiv oder inaktiv
	 *
	 * @param $mod
	 * @throws \Exception
	 */
	public function setUseModRewrite($mod)
	{
		$this->useModRewrite	=	(bool)$mod;

		if($this->useModRewrite && !$this->isModRewriteActiv)
		{
			throw new \Exception('apache has mod_rewrite not activated');
		}
	}


	/**
	 * Setzt eine Dateiendung
	 *
	 * @param string $extension
	 * @throws \Exception
	 */
	public function setUseFileExtension($extension)
	{
		$extension	=	trim($extension, '.');
		$extension	=	trim($extension);

		if(empty($extension))
		{
			throw new \Exception('mod_rewrite file extension is empty');
		}

		$this->useFileExtension	=	$extension;
	}


	/**
	 * Wandelt einen Link in ModRewrite um oder
	 * gibt Ihn als normalen GET Text zurück
	 *
	 * @param string $httpRoot
	 * @param array $parameters
	 * @return string $link
	 */
	public function getUrl($httpRoot, $parameters)
	{
		$link	=	$httpRoot;

		if(!empty($parameters))
		{
			if($this->useModRewrite)
			{
				foreach($parameters as $par)
				{
					if(is_int($par['value']))
					{
						$link	=	trim($link, '/').'_'.$par['value'].'/';
					}
					else
					{
						$link	.=	$par['value'].'/';
					}
				}

				$link	=	trim($link, '/');
				$link	.=	'.'.$this->useFileExtension;
			}
			else
			{
				foreach($parameters as $k => $par)
				{
					$link	.=	(($k == 0) ? '?' : '&').$par['key'].'='.$par['value'];
				}
			}
		}

		return $link;
	}

	/**
	 * Wandelt einen Link in ModRewrite um oder
	 * gibt Ihn als normalen GET Text zurück
	 *
	 * @param string $httpRoot
	 * @param array $parameters
	 * @return string $link
	 */
	public function getUrlSimple($httpRoot, $parameters)
	{
		$link	=	$httpRoot;

		if(!empty($parameters))
		{
			if($this->useModRewrite)
			{
				foreach($parameters as $v)
				{
					if(is_int($v))
					{
						$link	=	trim($link, '/').'_'.$v.'/';
					}
					else
					{
						$link	.=	$v.'/';
					}
				}

				$link	=	trim($link, '/');
				$link	.=	'.'.$this->useFileExtension;
			}
			else
			{
				$firstRun	=	true;

				foreach($parameters as $k => $v)
				{
					$link	.=	(($firstRun) ? '?' : '&').$k.'='.$v;

					$firstRun	=	false;
				}
			}
		}

		return $link;
	}
}