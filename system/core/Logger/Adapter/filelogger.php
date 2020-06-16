<?php
/**
 *  Copyright (C) 2010 - 2020  <Robbyn Gerhardt>
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
 * @package       truetravel_bootstrap
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, pressmind GmbH (https://www.pressmind.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          https://www.pressmind.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core\Logger\Adapter;

use system\core\Logger\LoggerConfig;
use system\core\Registry;

class filelogger implements AdapterInterface
{
	const OUTPUT_SCREEN	=	'screen';
	const OUTPUT_FILE	=	'file';

	private $logPath;
	private $globalConfig;

	public function connection(LoggerConfig $config)
	{
		$this->logPath	=	ROOT.trim(trim($config->path, '/'), '\\').DIRECTORY_SEPARATOR;

		if(!file_exists($this->logPath))
		{
			mkdir($this->logPath, 0755, true);
		}

		$this->globalConfig	=	Registry::getInstance()->get('config');
	}

	/**
	 * Write log
	 *
	 * @param string $log
	 * @param string $output
	 * @param string $filename
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function write($log, $output = self::OUTPUT_SCREEN, $filename = 'message.log')
	{
		$datetime	=	new \DateTime('now', new \DateTimeZone($this->globalConfig['timezone']));

		$log	=	'['.$datetime->format('d.m.Y H:i:s').'] '.print_r($log, true);

		if($output == self::OUTPUT_FILE)
		{
			file_put_contents($this->logPath.$filename, $log."\r\n", FILE_APPEND);
		}
		else
		{
			echo '<pre>'.$log.'</pre>';
		}

		return $log;
	}
}
