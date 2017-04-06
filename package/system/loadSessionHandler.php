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
 *  @package	Webpackages
 *  @subpackage core
 *  @author	    Robbyn Gerhardt
 *  @copyright	Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 *  @license	http://opensource.org/licenses/gpl-license.php GNU Public License
 *  @link	    http://webpackages.de
 *  @since	    Version 2017.0
 *  @filesource
 */

require_once CORE_DIR.'database.class.php';

class NewSessionHandler implements SessionHandlerInterface
{
	protected $database;

	public function __construct()
	{
		if(PDO_DATABASE == '' || (PDO_USERNAME == '' && PDO_TYPE != 'sqlite' && PDO_TYPE != 'sqlite2'))
		{
			throw new Exception('Error: Database connection');
		}

		$dsn		=	PDO_TYPE.':';

		if(PDO_TYPE == 'sqlite' || PDO_TYPE == 'sqlite2')
		{
			$dsn	.=	PDO_DATABASE;
		}
		else
		{
			if(PDO_TYPE == 'sqlsrv')
			{
				$dsn	.=	'Server='.PDO_HOST;
			}
			else
			{
				$dsn	.=	'host='.PDO_HOST;
			}

			$addIn	=	true;

			if(PDO_PORT != '')
			{
				if($addIn == true && PDO_TYPE != 'sqlsrv')
				{
					$dsn	.=	';';
				}

				if(PDO_TYPE == 'informix')
				{
					$dsn	.=	'service='.PDO_PORT;
				}
				elseif(PDO_TYPE == 'sqlsrv')
				{
					$dsn	.=	','.PDO_PORT;
				}
				else
				{
					$dsn	.=	'port='.PDO_PORT;
				}

				$addIn	=	true;
			}

			if(PDO_DATABASE != '')
			{
				if($addIn == true)
				{
					$dsn	.=	';';
				}

				$dsn	.=	'dbname='.PDO_DATABASE;
				$addIn	=	true;
			}

			if(PDO_CHARSET != '')
			{
				if($addIn == true)
				{
					$dsn	.=	';';
				}

				$dsn	.=	'charset='.PDO_CHARSET;
			}
		}

		$this->database	=	new \package\core\database($dsn, PDO_USERNAME, PDO_PASSWORD, null, PDO_TYPE);

		$createTable	=	'
		CREATE TABLE IF NOT EXISTS `sessions` (
		  `session_id` VARCHAR(255) NOT NULL,
		  `session_expires` DATETIME NOT NULL,
		  `session_data` TEXT,
		PRIMARY KEY (`session_id`));
		';

		$this->database->exec($createTable);

		session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'destroy')
        );

		session_start();
	}

	/**
	 * Öffnet die Datenbankverbindung
	 *
	 * @param string $save_path
	 * @param string $session_id
	 * @return bool
	 * @throws Exception
	 */
	public function open($save_path, $session_id)
	{
		return true;
	}

	/**
	 * Ließt die Session aus der Datenbank aus
	 *
	 * @param string $session_id
	 * @return string
	 */
	public function read($session_id)
	{
		$getDatas	=	'
		SELECT
			`session_data`
		FROM
			`sessions`
		WHERE
			`session_id`		=	? AND
			`session_expires`	>	NOW();
		';

		$getDatas	=	$this->database->safetyQuery($getDatas, array($session_id), true, true);

		if(!empty($getDatas['session_data']))
		{
        	return $getDatas['session_data'];
		}
		else
		{
			return '';
		}
	}

	/**
	 * Schreibt die Session Infos in die Datenbank
	 *
	 * @param string $session_id
	 * @param string $session_data
	 * @return bool
	 * @throws Exception
	 */
	public function write($session_id, $session_data)
	{
        $newDateTime	=	date('Y-m-d H:i:s',strtotime('+ 1 hour'));

		$writeData	=	'
		REPLACE INTO
			`sessions`
		SET
			`session_id`		=	?,
			`session_expires`	=	?,
			`session_data`		=	?
		';

       return $this->database->safetyQuery($writeData, array($session_id, $newDateTime, $session_data), false);
	}

	/**
	 * Schließt die Datenbankverbindung
	 */
	public function close()
	{
		$this->database	=	null;

		unset($this->database);

		return true;
	}

	/**
	 * Löscht eine Session ID aus der Datenbank
	 *
	 * @param string $session_id
	 * @return bool
	 */
	public function destroy($session_id)
	{
		$removeSessionId	=	'
		DELETE FROM
			`sessions`
		WHERE
			`session_id`	=	?
		LIMIT
			1;
		';

		return $this->database->safetyQuery($removeSessionId, array($session_id), false);
	}

	/**
	 * Löscht alles was nicht dem Zeitstempel mehr entspricht
	 *
	 * @param int $maxlifetime
	 * @return bool
	 */
	public function gc($maxlifetime)
	{
		$removeMaxlifetime	=	'
		DELETE FROM
			`sessions`
		WHERE
			(
				(
					UNIX_TIMESTAMP(session_expires) + '.$maxlifetime.'
				)
				<
				'.$maxlifetime.'
			)
		';

		return $this->database->exec($removeMaxlifetime);
	}
}

if(defined('USE_SESSION_SAVE_HANDLER') && USE_SESSION_SAVE_HANDLER  && defined('PDO_HOST') && PDO_HOST != '')
{
	new NewSessionHandler();
}