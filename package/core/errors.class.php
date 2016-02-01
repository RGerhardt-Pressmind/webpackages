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
 * Erzeugt Fehlermeldungen
 *
 * Mit der Klasse errors kann man leicht header Fehlermeldungen mittels HTTP-Statuscodes erzeugen
 *
 * @package		Webpackages
 * @subpackage	core
 * @category	Errors
 * @author		Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class errors
{
	public $callErrors	=	array(
		200		=>	'OK',
		201		=>	'Created',
		202		=>	'Accepted',
		203		=>	'Non-Authoritative Information',
		204		=>	'No Content',
		205		=>	'Reset Content',
		206		=>	'Parital Content',
		300		=>	'Multiple Choices',
		301		=>	'Moved Permanently',
		302		=>	'Found',
		304		=>	'Not Modified',
		305		=>	'Use Proxy',
		307		=>	'Temporary Redirect',
		400		=>	'Bad Request',
		401		=>	'Unauthorized',
		403		=>	'Forbidden',
		404		=>	'Not Found',
		405		=>	'Method Not Allowed',
		406		=>	'Not Acceptable',
		407		=>	'Proxy Authentication Required',
		408		=>	'Request Timeout',
		409		=>	'Conflict',
		410		=>	'Gone',
		411		=>	'Length Required',
		412		=>	'Precondition Failed',
		413		=>	'Request Entity Too Large',
		414		=>	'Request-URI Too Long',
		415		=>	'Unsupported Media Type',
		416		=>	'Requested Range Not Satisfiable',
		417		=>	'Expectation Failed',
		500		=>	'Internal Server Error',
		501		=>	'Not Implemented',
		502		=>	'Bad Gateway',
		503		=>	'Service Unavailable',
		504		=>	'Gateway Timeout',
		505		=>	'HTTP Version Not Supported'
	);

	/**
	 * Eine Exception Fehlermeldung erzeugen
	 *
	 * @param string $message Die Nachricht die bei der Fehlermeldung ausgegeben werden soll
	 * @throws \Exception
	 */
	public function createException($message)
	{
		throw new \Exception($message);
	}

	/**
	 * Gibt einen HTTP-Statucode 1.1 Fehler aus
	 *
	 * @param int $errorCode Der HTTP-Statuscode
	 * @return void
	 * @throws \Exception
	 */
	public function create_error($errorCode)
	{
		if(empty($errorCode) === true || empty($this->callErrors[$errorCode]) === true)
		{
			throw new \Exception('Error: error code '.$errorCode.' not allowed. Allowed: '.implode(',', $this->callErrors));
		}

		header('HTTP 1.1/'.$errorCode.' '.$this->callErrors[$errorCode]);
		exit;
	}
}