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
 * @package       Webpackages
 * @subpackage    core
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\imagesException;
use package\implement\IStatic;
use package\system\core\initiator;

/**
 * Schneidet Bilder zu
 *
 * Durch die images Klasse kann man Bilder auf ein bestimmtest Format zuschneiden.
 *
 * @method static int|bool getImageHeight(string $image)
 * @method static int|bool getImageWidth(string $image)
 * @method static bool createCroppedThumbnail(string $source, float $width, float $height, string $savePath, float $clipping_x = 0, float $clipping_y = 0, float $clipping_width = 0, float $clipping_height = 0, int $quality = 100)
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       images
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class images extends initiator implements IStatic
{
	private static $image, $image_type;

	public static function init()
	{
	}

	/**
	 * Lädt die Bildressource rein
	 *
	 * @param string $img
	 */
	private static function loadImage($img)
	{
		$image_info       = getimagesize($img);
		self::$image_type = $image_info[2];

		if(self::$image_type == IMAGETYPE_JPEG)
		{
			self::$image = imagecreatefromjpeg($img);
		}
		elseif(self::$image_type == IMAGETYPE_GIF)
		{
			self::$image = imagecreatefromgif($img);
		}
		elseif(self::$image_type == IMAGETYPE_PNG)
		{
			self::$image = imagecreatefrompng($img);
		}
		else
		{
			self::$image	=	null;
		}
	}

	/**
	 * Gibt die Höhe eines Bildes zurück
	 *
	 * @param string $image
	 *
	 * @return int|bool
	 */
	protected static function _getImageHeight($image)
	{
		if(!function_exists('imagesy'))
		{
			return false;
		}

		self::loadImage($image);

		return imagesy(self::$image);
	}

	/**
	 * Gibt die Breite eines Bildes zurück
	 *
	 * @param $image
	 *
	 * @return int|bool
	 */
	protected static function _getImageWidth($image)
	{
		if(!function_exists('imagesx'))
		{
			return false;
		}

		self::loadImage($image);

		return imagesx(self::$image);
	}


	/**
	 * Erstellt ein Thumbnail eines Bildes, mit Bildausschnitt
	 *
	 * @param string $source          Der relative Pfad zur Datei
	 * @param float  $width           Beite des neuen Bildes
	 * @param float  $height          Höhe des neuen Bildes
	 * @param string $savePath        Der Zielpfad des veränderten Ergebnisses
	 * @param float  $clipping_x      X Position des Bildes im Ausschnitt
	 * @param float  $clipping_y      Y Position des Bildes im Ausschnitt
	 * @param float  $clipping_width  Breite des Bilders im Ausschnitt
	 * @param float  $clipping_height Höhe des Bildes im Ausschnitt
	 * @param int    $quality         zwischen 0 (schlechteste Qualität, kleine Datei) und 100 (beste Qualität, größte
	 *                                Datei)
	 *
	 * @return bool
	 * @throws imagesException
	 */
	protected static function _createCroppedThumbnail($source, $width, $height, $savePath, $clipping_x = 0.00, $clipping_y = 0.00, $clipping_width = 0.00, $clipping_height = 0.00, $quality = 100)
	{
		$imagesize = getimagesize($source);

		$savefile = new \SplFileInfo($savePath);

		$sourceWidth  = $imagesize[0];
		$sourceHeight = $imagesize[1];
		$sourceType   = $imagesize[2];

		//1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motoral byte order), 9 = JPC, 10 = JP2, 11 =  JPX, 12 => JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
		switch($sourceType)
		{
			case 1: //GIF

				$image = imagecreatefromgif($source);

			break;
			case 2: // JPG

				$image = imagecreatefromjpeg($source);

			break;
			case 3: // PNG

				$image = imagecreatefrompng($source);

			break;
			default:

				throw new imagesException('Error: unsupported image format');

			break;
		}

		$destinationExtension = $savefile->getExtension();

		$thumb = imagecreatetruecolor($width, $height);

		if($destinationExtension == 'png')
		{
			//Transparenter Hintergrund anstatt Schwarzer
			$color = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
			imagefill($thumb, 0, 0, $color);
			imagesavealpha($thumb, true);
		}
		else
		{
			//Weißer Hintergrund anstatt Schwarzer
			$white = imagecolorallocate($thumb, 255, 255, 255);
			imagefill($thumb, 0, 0, $white);
		}

		imagecopyresampled($thumb, $image, $clipping_x, $clipping_y, 0, 0, $clipping_width, $clipping_height, $sourceWidth, $sourceHeight);

		switch(strtolower($destinationExtension))
		{
			case 'png':

				if(!imagepng($thumb, $savePath, $quality))
				{
					throw new imagesException('Error: file not save png');
				}

			break;
			case 'jpg':
			case 'jpeg':

				if(!imagejpeg($thumb, $savePath, $quality))
				{
					throw new imagesException('Error: file not save jpg');
				}

			break;
			case 'gif':

				if(!imagegif($thumb, $savePath))
				{
					throw new imagesException('Error: file not save gif');
				}

			break;
			default:

				throw new imagesException('Error: not allowed output format. Allowed: jpg,jpeg,png,gif');

			break;
		}

		imagedestroy($thumb);

		return true;
	}
}