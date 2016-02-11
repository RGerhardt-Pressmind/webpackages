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
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2016, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\loggerException;
use package\implement\iLogger as iLogger;
use package\system\core\initiator;

/**
 * Schreibt log Dateien
 *
 * Mithilfe der logger Klasse kann man log Dateien schrieben, lesen oder auch löschen.
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       language
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class logger extends initiator implements iLogger
{
	/**
	 * @var string Der Name der log Datei. Standartmäßig log.txt
	 */
	public $filename = 'log.txt';

	/**
	 * Schreibt einen Log
	 *
	 * @param string $msg   Die Nachricht die gespeichert werden soll.
	 * @param int    $code  Der Code der der Nachricht übergeben werden soll. Standartmäßig "0"
	 * @param string $level Das Level des geschriebenen logs. Standartmäßig "info"
	 *
	 * @return bool
	 */
	public function _write_log($msg, $code = 0, $level = 'info')
	{
		$filename = CACHE_PATH.$this->filename;

		$time = date('d.m.Y H:i:s');

		$writeLog = @file_put_contents($filename, $time.' - '.$level.'('.$code.'): '.$msg."\r\n", FILE_APPEND);

		if($writeLog === false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Löscht den gesamten Log
	 *
	 * @return bool Gibt bei erfolg true ansonsten false zurück.
	 * @throws loggerException
	 */
	public function _delete_log()
	{
		$filename = CACHE_PATH.$this->filename;

		if(file_exists($filename) === false)
		{
			throw new loggerException('log file not exists');
		}

		$unlink = @unlink($filename);

		return $unlink;
	}

	/**
	 * Liest den gesamten Log aus
	 *
	 * @return string Gibt den Inhalt des Logs zurück
	 * @throws loggerException
	 */
	public function _read_log()
	{
		$filename = CACHE_PATH.$this->filename;

		$read = @file_get_contents($filename);

		if($read === false)
		{
			throw new loggerException('Error: file('.$filename.') not read');
		}

		return $read;
	}
} 