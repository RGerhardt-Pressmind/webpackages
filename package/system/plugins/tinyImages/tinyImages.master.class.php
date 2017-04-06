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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2017.0
 * @filesource
 */

namespace package\system\plugins\tinyImages;

use package\implement\IPlugin;

class tinyImages implements IPlugin
{
	public function setAllClasses($allClasses)
	{

	}

	public function getClassName()
	{
		return 'tinyImages';
	}

	public function construct()
	{
	}

	public function getApplyPlugin()
	{
		// TODO: Implement getApplyPlugin() method.
	}

	/**
	 * Optimiert Bilder
	 *
	 * @param string $image absolute path to image
	 * @param int $min_quality
	 * @param int $max_quality
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function optimizeImage($image, $min_quality = 60, $max_quality = 90)
	{
		$image	=	new \SplFileInfo($image);

		if(!$image->isFile())
		{
			throw new \Exception('Error: $image is not a file: '.$image->__toString());
		}

		if(OS == 'UNIX')
		{
			return self::optimizeUnixImage($image, $min_quality, $max_quality);
		}
		else
		{
			return self::optimizeWinImage($image, $min_quality, $max_quality);
		}
	}

	/**
	 * Konvertiert für Unix Systeme
	 *
	 * @param \SplFileInfo $image
	 * @param int $min_quality
	 * @param int $max_quality
	 *
	 * @return string
	 * @throws \Exception
	 */
	private static function optimizeUnixImage($image, $min_quality, $max_quality)
	{
		$optimizeImage	=	$image->getPath().SEP.str_replace('.'.$image->getExtension(), '', $image->getFilename());
		$optimizeImage	.=	$min_quality.'_'.$max_quality.'.'.$image->getExtension();

		if(file_exists($optimizeImage))
		{
			return $optimizeImage;
		}

		$pngquant	=	__DIR__.SEP.'pngquant'.SEP.'UNIX'.SEP.'pngquant';

		$compressed_png_content = shell_exec($pngquant." --quality=".$min_quality."-".$max_quality." - < ".escapeshellarg(    $image->__toString()));

		if (!$compressed_png_content)
		{
        	throw new \Exception('Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?');
    	}

    	file_put_contents($optimizeImage, $compressed_png_content);

		return $optimizeImage;
	}

	/**
	 * Konvertiert für Win Systeme
	 *
	 * @param \SplFileInfo $image
	 * @param int $min_quality
	 * @param int $max_quality
	 *
	 * @return string
	 */
	private static function optimizeWinImage($image, $min_quality, $max_quality)
	{
		//@todo Win optimizer
		return $image;
	}
}