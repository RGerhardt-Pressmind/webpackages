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
 *  @author	    Robbyn Gerhardt <gerhardt@webpackages.de>
 *  @copyright	Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2.0.0
 *  @filesource
 */

namespace package\core;


/**
 * Erstellen von Captcha Bildern
 *
 * Um seine Formulare vor Spam von sogenannten Bots abzusichern, kann man Captchas mit einrichten. Diese Klasse erstellt
 * ein Bild wo eine bestimmte Anzahl von Zeichen zu sehen sind. Ihr Benutzer muss dieses Anzahl von Zeichen in ein dafür
 * vorgesehenes Feld eintippen. Nachdem das Formular abgesendet wurde, können Sie die Eingabe des Benutzer mit den
 * Daten, die die Klasse Captcha zurückliefert vergleichen.
 *
 * @package		Webpackages
 * @subpackage	core
 * @category	Captcha
 * @author		Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class captcha
{
	/**
	 * Erstellt ein Captcha
	 *
	 * @param string $img_path Der Ordner wo das Captcha abgelegt wird
	 * @param string $img_url Der HTTP Pfad zum abgelegten Captcha
	 * @param string $font_path Eine Schriftart mit der das Captcha erstellt werden soll
	 * @param int $fontSize Die Schriftgröße der Schriftart in Pixel
	 * @param int $imgWidth Die Breite des Captchas in Pixeln
	 * @param int $imgHeight Die Höhe des Captchas in Pixeln
	 * @return array Gibt das fertige Captcha zurück
	 * @throws \Exception Bei leeren Parametern oder im Fehlerfall
	 */
	public static function create_captcha($img_path = '', $img_url = '', $font_path = '', $fontSize = 5, $imgWidth = 150, $imgHeight = 30)
	{
		$fontSize = 5;

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'captcha', 'createCaptcha', array($img_path, $img_url, $font_path));
			$plugins	=	plugins::hookCall('before', 'captcha', 'createCaptcha', array($img_path, $img_url, $font_path));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$img_width	=	$imgWidth;
		$img_height	=	$imgHeight;
		$expiration	=	7200;

		if(empty($img_path) || empty($img_url))
		{
			throw new \Exception('Error: image path or image url is empty');
		}

		if(is_dir($img_path) === false)
		{
			throw new \Exception('Error: image path is not a directory');
		}

		if(is_writable($img_path) === false)
		{
			throw new \Exception('Error: image path not writable');
		}

		if(extension_loaded('gd') === false)
		{
			throw new \Exception('Error: gd lib not installed');
		}

		// -----------------------------------
		// Remove old images
		// -----------------------------------

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		$current_dir = @opendir($img_path);

		while($filename = @readdir($current_dir))
		{
			if($filename != "." && $filename != ".." && $filename != "index.html")
			{
				$name = str_replace(".jpg", "", $filename);

				if(($name + $expiration) < $now)
				{
					@unlink($img_path.$filename);
				}
			}
		}

		@closedir($current_dir);

		// -----------------------------------
		// Do we have a "word" yet?
		// -----------------------------------

		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$str = '';
		for($i = -1; ++$i <= 8;)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}

		$word = $str;

		// -----------------------------------
		// Determine angle and position
		// -----------------------------------

		$length	=	strlen($word);
		$angle	= 	($length >= 6) ? rand(-($length - 6), ($length - 6)) : 0;
		$x_axis	= 	rand(6, (360 / $length) - 16);
		$y_axis = 	($angle >= 0 ) ? rand($img_height, $img_width) : rand(6, $img_height);

		// -----------------------------------
		// Create image
		// -----------------------------------

		// PHP.net recommends imagecreatetruecolor(), but it isn't always available
		if(function_exists('imagecreatetruecolor'))
		{
			$im = imagecreatetruecolor($img_width, $img_height);
		}
		else
		{
			$im = imagecreate($img_width, $img_height);
		}

		// -----------------------------------
		//  Assign colors
		// -----------------------------------

		$bg_color		=	imagecolorallocate ($im, 255, 255, 255);
		$border_color	= 	imagecolorallocate ($im, 153, 102, 102);
		$text_color		= 	imagecolorallocate ($im, 204, 153, 153);
		$grid_color		= 	imagecolorallocate($im, 255, 182, 182);

		// -----------------------------------
		//  Create the rectangle
		// -----------------------------------

		ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);

		// -----------------------------------
		//  Create the spiral pattern
		// -----------------------------------

		$theta		= 	1;
		$thetac		= 	7;
		$radius		= 	16;
		$circles	= 	20;
		$points		= 	32;

		for($i = -1; ++$i <= (($circles * $points) - 1);)
		{
			$theta 	+= 	$thetac;
			$rad 	= 	$radius * ($i / $points );
			$x 		= 	($rad * cos($theta)) + $x_axis;
			$y 		= 	($rad * sin($theta)) + $y_axis;
			$theta 	+= 	$thetac;
			$rad1 	= 	$radius * (($i + 1) / $points);
			$x1 	= 	($rad1 * cos($theta)) + $x_axis;
			$y1 	= 	($rad1 * sin($theta )) + $y_axis;

			imageline($im, $x, $y, $x1, $y1, $grid_color);

			$theta 	-= $thetac;
		}

		// -----------------------------------
		//  Write the text
		// -----------------------------------

		$use_font = ($font_path != '' && file_exists($font_path) && function_exists('imagettftext')) ? true : false;

		$font_size = $fontSize;

		if($use_font == false)
		{
			$x = rand(0, $img_width / ($length / 3));
		}
		else
		{
			$x = rand(0, $img_width / ($length / 1.5));
		}

		for($i = -1; ++$i < strlen($word);)
		{
			if($use_font == false)
			{
				$y = rand(0 , $img_height / 2);
				imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
				$x += ($font_size * 2);
			}
			else
			{
				$y = rand($img_height / 2, $img_height - 3);
				imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
				$x += $font_size;
			}
		}


		// -----------------------------------
		//  Create the border
		// -----------------------------------

		imagerectangle($im, 0, 0, ($img_width - 1), ($img_height - 1), $border_color);

		// -----------------------------------
		//  Generate the image
		// -----------------------------------

		$img_name = $now.'.jpg';

		ImageJPEG($im, $img_path.$img_name);

		$img = "<img src=\"$img_url$img_name\" width=\"$img_width\" height=\"$img_height\" style=\"border:0;\" alt=\" \" />";

		ImageDestroy($im);

		$back	=	array('word' => $word, 'time' => $now, 'image' => $img, 'filepath' => $img_path.$img_name);

		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('after', 'captcha', 'createCaptcha', array($back));
			$plugins	=	plugins::hookCall('after', 'captcha', 'createCaptcha', array($back));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		return $back;
	}
} 