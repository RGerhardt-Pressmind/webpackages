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
 * @subpackage    controllers
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\captchaException;
use package\system\core\initiator;

/**
 * Erstellen von Captcha Bildern
 *
 * Um seine Formulare vor Spam von sogenannten Bots abzusichern, kann man Captchas mit einrichten. Diese Klasse erstellt
 * ein Bild wo eine bestimmte Anzahl von Zeichen zu sehen sind. Ihr Benutzer muss dieses Anzahl von Zeichen in ein dafür
 * vorgesehenes Feld eintippen. Nachdem das Formular abgesendet wurde, können Sie die Eingabe des Benutzer mit den
 * Daten, die die Klasse Captcha zurückliefert vergleichen.
 *
 * @method static array create_better_captcha(string $savePath, int $imageWidth = 200, int $imageHeight = 50, string $allowedLettersType = 'alpha', string $imageType = 'png', array $backgroundColor = array('r' => 255, 'g' => 255, 'b' => 255), int $linesInCaptcha = 3, array $linesInCaptchaColor = array('r' => 64, 'g' => 64, 'b' => 64), int $pointsInCaptcha = 1000, array $pointsInCaptchaColor= array('r' => 0, 'g' => 0, 'b' => 255))
 * @method static array create_captcha($img_path = '', $img_url = '', $font_path = '', $imgWidth = 150, $imgHeight = 30)
 *
 * @package        Webpackages
 * @subpackage     controllers
 * @category       Captcha
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class captcha extends initiator
{
	/**
	 * Erstellt ein zufälliges Captcha Bild
	 *
	 * @param string $savePath Der Speicherort des Captchas
	 * @param int $imageWidth Die Breite des Captcha Bildes
	 * @param int $imageHeight Die Höhe des Captcha Bildes
	 * @param string $allowedLettersType Erlaubt sind alnum, numeric, nozero und alpha
	 * @param string $imageType Der Bildtype der erstellt werden soll. Erlaubt sind png, jpg oder gif
	 * @param array $backgroundColor Der RGB Farben des Hintergrunds des Captcha Bildes
	 * @param int $linesInCaptcha Die Anzahl der Linien die durch das Bild gehen sollen
	 * @param array $linesInCaptchaColor Die RGB Farben der Linien
	 * @param int $pointsInCaptcha Die Anzahl an Punkten die wilkürlich im Captcha platziert werden
	 * @param array $pointsInCaptchaColor Die RGB Farben der Punkte
	 *
	 * @return array
	 * @throws captchaException
	 */
	public static function _create_better_captcha($savePath = CACHE_PATH, $imageWidth = 200, $imageHeight = 50, $allowedLettersType = 'alpha', $imageType = 'png', $backgroundColor = array('r' => 255, 'g' => 255, 'b' => 255), $linesInCaptcha = 3, $linesInCaptchaColor = array('r' => 64, 'g' => 64, 'b' => 64), $pointsInCaptcha = 1000, $pointsInCaptchaColor= array('r' => 0, 'g' => 0, 'b' => 255))
	{
		if(extension_loaded('gd') === false)
		{
			throw new captchaException('Error: gd lib is not installed');
		}

		$image 			  = imagecreatetruecolor($imageWidth, $imageHeight);

		if($image === false)
		{
			throw new captchaException("Cannot Initialize new GD image stream");
		}

		if(isset($linesInCaptchaColor['r']) === false || isset($linesInCaptchaColor['g']) === false || isset($linesInCaptchaColor['b']) === false)
		{
			throw new captchaException('Error: lines in captcha color is not valid. (array("r" => 0, "g" => 0, "b" => 0))');
		}

		if(isset($pointsInCaptchaColor['r']) === false || isset($pointsInCaptchaColor['g']) === false || isset($pointsInCaptchaColor['b']) === false)
		{
			throw new captchaException('Error: points in captcha color is not valid. (array("r" => 0, "g" => 0, "b" => 0))');
		}

		if(isset($backgroundColor['r']) === false || isset($backgroundColor['g']) === false || isset($backgroundColor['b']) === false)
		{
			throw new captchaException('Error background color is not valid. (array("r" => 0, "g" => 0, "b" => 0))');
		}

		$background_color = imagecolorallocate($image, $backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
		$line_color       = imagecolorallocate($image, $linesInCaptchaColor['r'], $linesInCaptchaColor['g'], $linesInCaptchaColor['b']);
		$pixel_color      = imagecolorallocate($image, $pointsInCaptchaColor['r'], $pointsInCaptchaColor['g'], $pointsInCaptchaColor['b']);
		imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $background_color);

		for($i = -1; ++$i < $linesInCaptcha;)
		{
			imageline($image, 0, rand() % $imageHeight, $imageWidth, rand() % $imageHeight, $line_color);
		}

		for($i = -1; ++$i < $pointsInCaptcha;)
		{
			imagesetpixel($image, rand() % $imageWidth, rand() % $imageHeight, $pixel_color);
		}

		$allowedLettersType	=	strtolower($allowedLettersType);

		$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		if($allowedLettersType == 'alnum')
		{
			$letters	=	'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		elseif($allowedLettersType == 'numeric')
		{
			$letters	=	'0123456789';
		}
		elseif($allowedLettersType == 'nozero')
		{
			$letters	=	'123456789';
		}

		$len        = strlen($letters);
		$text_color = imagecolorallocate($image, 0, 0, 0);
		$word       = '';

		$factorWidth	=	($imageWidth / 200) * 30;
		$factorHeight	=	($imageHeight / 50) * 20;
		$beginFactor	=	($imageWidth / 200) * 15;

		for($i = -1; ++$i < 6;)
		{
			$letter = $letters[rand(0, $len - 1)];
			imagestring($image, 7, $beginFactor + ($i * $factorWidth), $factorHeight, $letter, $text_color);
			$word .= $letter;
		}

		$imgPath	=	$savePath;

		if(file_exists($imgPath) === false)
		{
			mkdir($imgPath, 0755, true);
		}

		$imageName	=	md5(uniqid(mt_rand(), true));
		$imgPath	.=	$imageName;

		$imageType	=	strtolower($imageType);

		if($imageType == 'png')
		{
			$imgPath	.=	'.png';

			imagepng($image, $imgPath);
		}
		elseif($imageType == 'jpg' || $imageType == 'jpeg')
		{
			$imgPath	.=	'.jpg';

			imagejpeg($image, $imgPath);
		}
		elseif($imageType == 'gif')
		{
			$imgPath	.=	'.gif';

			imagegif($image, $imgPath);
		}
		else
		{
			imagedestroy($image);
			throw new captchaException('Error: image type not allowed: '.$imageType);
		}

		imagedestroy($image);

		return array(
			'word'			=>	$word,
			'name'			=>	$imageName,
			'filepath'		=>	$imgPath
		);
	}

	/**
	 * Erstellt ein Captcha
	 *
	 * @param string $img_path  Der Ordner wo das Captcha abgelegt wird
	 * @param string $img_url   Der HTTP Pfad zum abgelegten Captcha
	 * @param string $font_path Eine Schriftart mit der das Captcha erstellt werden soll
	 * @param int    $imgWidth  Die Breite des Captchas in Pixeln
	 * @param int    $imgHeight Die Höhe des Captchas in Pixeln
	 *
	 * @deprecated
	 *
	 * @return array Gibt das fertige Captcha zurück
	 * @throws captchaException Bei leeren Parametern oder im Fehlerfall
	 */
	protected static function _create_captcha($img_path = '', $img_url = '', $font_path = '', $imgWidth = 150, $imgHeight = 30)
	{
		$fontSize = 5;

		$img_width  = $imgWidth;
		$img_height = $imgHeight;
		$expiration = 7200;

		if(empty($img_path) === true || empty($img_url) === true)
		{
			throw new captchaException('Error: image path or image url is empty');
		}

		if(is_dir($img_path) === false)
		{
			throw new captchaException('Error: image path is not a directory');
		}

		if(is_writable($img_path) === false)
		{
			throw new captchaException('Error: image path not writable');
		}

		if(extension_loaded('gd') === false)
		{
			throw new captchaException('Error: gd lib not installed');
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
			$str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
		}

		$word = $str;

		// -----------------------------------
		// Determine angle and position
		// -----------------------------------

		$length = strlen($word);
		$angle  = ($length >= 6) ? rand(-($length - 6), ($length - 6)) : 0;
		$x_axis = rand(6, (360 / $length) - 16);
		$y_axis = ($angle >= 0) ? rand($img_height, $img_width) : rand(6, $img_height);

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

		$bg_color     = imagecolorallocate($im, 255, 255, 255);
		$border_color = imagecolorallocate($im, 153, 102, 102);
		$text_color   = imagecolorallocate($im, 204, 153, 153);
		$grid_color   = imagecolorallocate($im, 255, 182, 182);

		// -----------------------------------
		//  Create the rectangle
		// -----------------------------------

		ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);

		// -----------------------------------
		//  Create the spiral pattern
		// -----------------------------------

		$theta   = 1;
		$thetac  = 7;
		$radius  = 16;
		$circles = 20;
		$points  = 32;

		for($i = -1; ++$i <= (($circles * $points) - 1);)
		{
			$theta += $thetac;
			$rad = $radius * ($i / $points);
			$x   = ($rad * cos($theta)) + $x_axis;
			$y   = ($rad * sin($theta)) + $y_axis;
			$theta += $thetac;
			$rad1 = $radius * (($i + 1) / $points);
			$x1   = ($rad1 * cos($theta)) + $x_axis;
			$y1   = ($rad1 * sin($theta)) + $y_axis;

			imageline($im, $x, $y, $x1, $y1, $grid_color);

			$theta -= $thetac;
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
				$y = rand(0, $img_height / 2);
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

		$back = array(
			'word' => $word,
			'time' => $now,
			'image' => $img,
			'filepath' => $img_path.$img_name
		);

		return $back;
	}
} 