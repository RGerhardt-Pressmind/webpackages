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
 * @subpackage    controllers
 * @author        Robbyn Gerhardt <gerhardt@webpackages.de>
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2017.0
 * @filesource
 */

namespace package\core;

use package\exceptions\captchaException;
use package\system\core\initiator;

/**
 * Erstellen von Captcha Bildern
 *
 * Um seine Formulare vor Spam von sogenannten Bots abzusichern, kann man Captchas mit einrichten. Diese Klasse
 * erstellt
 * ein Bild wo eine bestimmte Anzahl von Zeichen zu sehen sind. Ihr Benutzer muss dieses Anzahl von Zeichen in ein
 * dafür
 * vorgesehenes Feld eintippen. Nachdem das Formular abgesendet wurde, können Sie die Eingabe des Benutzer mit den
 * Daten, die die Klasse Captcha zurückliefert vergleichen.
 *
 * @method static array create_better_captcha(string $savePath, int $imageWidth = 200, int $imageHeight = 50, string $allowedLettersType = 'alpha', string $imageType = 'png', array $backgroundColor = array('r' => 255, 'g' => 255, 'b' => 255), int $linesInCaptcha = 3, array $linesInCaptchaColor = array('r' => 64, 'g' => 64, 'b' => 64), int $pointsInCaptcha = 1000, array $pointsInCaptchaColor = array('r' => 0, 'g' => 0, 'b' => 255))
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
	 * @param string $savePath             Der Speicherort des Captchas
	 * @param int    $imageWidth           Die Breite des Captcha Bildes
	 * @param int    $imageHeight          Die Höhe des Captcha Bildes
	 * @param string $allowedLettersType   Erlaubt sind alnum, numeric, nozero und alpha
	 * @param string $imageType            Der Bildtype der erstellt werden soll. Erlaubt sind png, jpg oder gif
	 * @param array  $backgroundColor      Der RGB Farben des Hintergrunds des Captcha Bildes
	 * @param int    $linesInCaptcha       Die Anzahl der Linien die durch das Bild gehen sollen
	 * @param array  $linesInCaptchaColor  Die RGB Farben der Linien
	 * @param int    $pointsInCaptcha      Die Anzahl an Punkten die wilkürlich im Captcha platziert werden
	 * @param array  $pointsInCaptchaColor Die RGB Farben der Punkte
	 *
	 * @return array
	 * @throws captchaException
	 */
	protected static function _create_better_captcha($savePath = CACHE_PATH, $imageWidth = 200, $imageHeight = 50, $allowedLettersType = 'alpha', $imageType = 'png', $backgroundColor = array('r' => 255, 'g' => 255, 'b' => 255), $linesInCaptcha = 3, $linesInCaptchaColor = array('r' => 64, 'g' => 64, 'b' => 64), $pointsInCaptcha = 1000, $pointsInCaptchaColor = array('r' => 0, 'g' => 0, 'b' => 255))
	{
		if(!extension_loaded('gd'))
		{
			throw new captchaException('Error: gd lib is not installed');
		}

		$image = imagecreatetruecolor($imageWidth, $imageHeight);

		if($image == false)
		{
			throw new captchaException("Cannot Initialize new GD image stream");
		}

		if(!isset($linesInCaptchaColor['r']) || !isset($linesInCaptchaColor['g']) || !isset($linesInCaptchaColor['b']))
		{
			throw new captchaException('Error: lines in captcha color is not valid. (array("r" => 0, "g" => 0, "b" => 0))');
		}

		if(!isset($pointsInCaptchaColor['r']) || !isset($pointsInCaptchaColor['g']) || !isset($pointsInCaptchaColor['b']))
		{
			throw new captchaException('Error: points in captcha color is not valid. (array("r" => 0, "g" => 0, "b" => 0))');
		}

		if(!isset($backgroundColor['r']) || !isset($backgroundColor['g']) || !isset($backgroundColor['b']))
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

		$allowedLettersType = strtolower($allowedLettersType);

		$letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		if($allowedLettersType == 'alnum')
		{
			$letters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}
		elseif($allowedLettersType == 'numeric')
		{
			$letters = '0123456789';
		}
		elseif($allowedLettersType == 'nozero')
		{
			$letters = '123456789';
		}

		$len        = strlen($letters);
		$text_color = imagecolorallocate($image, 0, 0, 0);
		$word       = '';

		$factorWidth  = ($imageWidth / 200) * 30;
		$factorHeight = ($imageHeight / 50) * 20;
		$beginFactor  = ($imageWidth / 200) * 15;

		for($i = -1; ++$i < 6;)
		{
			$letter = $letters[rand(0, $len - 1)];
			imagestring($image, 7, $beginFactor + ($i * $factorWidth), $factorHeight, $letter, $text_color);
			$word .= $letter;
		}

		$imgPath = $savePath;

		if(!file_exists($imgPath))
		{
			mkdir($imgPath, 0755, true);
		}

		$imageName = md5(uniqid(mt_rand(), true));
		$imgPath .= $imageName;

		$imageType = strtolower($imageType);

		if($imageType == 'png')
		{
			$imgPath .= '.png';

			imagepng($image, $imgPath);
		}
		elseif($imageType == 'jpg' || $imageType == 'jpeg')
		{
			$imgPath .= '.jpg';

			imagejpeg($image, $imgPath);
		}
		elseif($imageType == 'gif')
		{
			$imgPath .= '.gif';

			imagegif($image, $imgPath);
		}
		else
		{
			imagedestroy($image);
			throw new captchaException('Error: image type not allowed: '.$imageType);
		}

		imagedestroy($image);

		return array('word' => $word, 'name' => $imageName, 'filepath' => $imgPath);
	}
}