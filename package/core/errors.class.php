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

    @category   errors.class.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

namespace package;

class errors
{
	/**
	 * Eine Exception Fehlermeldung
	 *
	 * @param string $message
	 * @throws \Exception
	 */
	public function createException($message)
	{
		throw new \Exception($message);
	}


	/**
	 * Erstellt eine 200 Meldung
	 *
	 * @return void
	 */
	public function create200()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create200');
			plugins::hookCall('before', 'error', 'create200');
		}

		header('HTTP/1.1 200 OK');
		exit;
	}


	/**
	 * Erstellt eine 201 Meldung
	 *
	 * @return void
	 */
	public function create201()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create201');
			plugins::hookCall('before', 'error', 'create201');
		}

		header('HTTP/1.1 201 Created');
		exit;
	}


	/**
	 * Erstellt eine 202 Meldung
	 *
	 * @return void
	 */
	public function create202()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create202');
			plugins::hookCall('before', 'error', 'create202');
		}

		header('HTTP/1.1 202 Accepted');
		exit;
	}


	/**
	 * Erstellt eine 203 Meldung
	 *
	 * @return void
	 */
	public function create203()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create203');
			plugins::hookCall('before', 'error', 'create203');
		}

		header('HTTP/1.1 203 Non-Authoritative Information');
		exit;
	}


	/**
	 * Erstellt eine 204 Meldung
	 *
	 * @return void
	 */
	public function create204()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create204');
			plugins::hookCall('before', 'error', 'create204');
		}

		header('HTTP/1.1 204 No Content');
		exit;
	}


	/**
	 * Erstellt eine 205 Meldung
	 *
	 * @return void
	 */
	public function create205()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create205');
			plugins::hookCall('before', 'error', 'create205');
		}

		header('HTTP/1.1 205 Reset Content');
		exit;
	}


	/**
	 * Erstellt eine 206 Meldung
	 *
	 * @return void
	 */
	public function create206()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create206');
			plugins::hookCall('before', 'error', 'create206');
		}

		header('HTTP/1.1 206 Partial Content');
		exit;
	}


	/**
	 * Erstellt eine 300 Meldung
	 *
	 * @return void
	 */
	public function create300()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create300');
			plugins::hookCall('before', 'error', 'create300');
		}

		header('HTTP/1.1 300 Multiple Choices');
		exit;
	}


	/**
	 * Erstellt eine 301 Meldung
	 *
	 * @return void
	 */
	public function create301()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create301');
			plugins::hookCall('before', 'error', 'create301');
		}

		header('HTTP/1.1 301 Moved Permanently');
		exit;
	}


	/**
	 * Erstellt eine 302 Meldung
	 *
	 * @return void
	 */
	public function create302()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create302');
			plugins::hookCall('before', 'error', 'create302');
		}

		header('HTTP/1.1 302 Found');
		exit;
	}


	/**
	 * Erstellt eine 304 Meldung
	 *
	 * @return void
	 */
	public function create304()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create304');
			plugins::hookCall('before', 'error', 'create304');
		}

		header('HTTP/1.1 304 Not Modified');
		exit;
	}


	/**
	 * Erstellt eine 305 Meldung
	 *
	 * @return void
	 */
	public function create305()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create305');
			plugins::hookCall('before', 'error', 'create305');
		}

		header('HTTP/1.1 305 Use Proxy');
		exit;
	}


	/**
	 * Erstellt eine 307 Meldung
	 *
	 * @return void
	 */
	public function create307()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create307');
			plugins::hookCall('before', 'error', 'create307');
		}

		header('HTTP/1.1 307 Temporary Redirect');
		exit;
	}


	/**
	 * Erstellt eine 400 Meldung
	 *
	 * @return void
	 */
	public function create400()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create400');
			plugins::hookCall('before', 'error', 'create400');
		}

		header('HTTP/1.1 400 Bad Request');
		exit;
	}


	/**
	 * Erstellt eine 401 Meldung
	 *
	 * @return void
	 */
	public function create401()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create401');
			plugins::hookCall('before', 'error', 'create401');
		}

		header('HTTP/1.1 401 Unauthorized');
		exit;
	}


	/**
	 * Erstellt eine 403 Meldung
	 *
	 * @return void
	 */
	public function create403()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create403');
			plugins::hookCall('before', 'error', 'create403');
		}

		header('HTTP/1.1 403 Forbidden');
		exit;
	}


	/**
	 * Erstellt eine 404 Meldung
	 *
	 * @return void
	 */
	public function create404()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create404');
			plugins::hookCall('before', 'error', 'create404');
		}

		header('HTTP/1.1 404 Not Found');
		exit;
	}


	/**
	 * Erstellt eine 405 Meldung
	 *
	 * @return void
	 */
	public function create405()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create405');
			plugins::hookCall('before', 'error', 'create405');
		}

		header('HTTP/1.1 405 Method Not Allowed');
		exit;
	}


	/**
	 * Erstellt eine 406 Meldung
	 *
	 * @return void
	 */
	public function create406()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create406');
			plugins::hookCall('before', 'error', 'create406');
		}

		header('HTTP/1.1 406 Not Acceptable');
		exit;
	}


	/**
	 * Erstellt eine 407 Meldung
	 *
	 * @return void
	 */
	public function create407()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create407');
			plugins::hookCall('before', 'error', 'create407');
		}

		header('HTTP/1.1 407 Proxy Authentication Required');
		exit;
	}


	/**
	 * Erstellt eine 408 Meldung
	 *
	 * @return void
	 */
	public function create408()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create408');
			plugins::hookCall('before', 'error', 'create408');
		}

		header('HTTP/1.1 408 Request Timeout');
		exit;
	}


	/**
	 * Erstellt eine 409 Meldung
	 *
	 * @return void
	 */
	public function create409()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create409');
			plugins::hookCall('before', 'error', 'create409');
		}

		header('HTTP/1.1 409 Conflict');
		exit;
	}


	/**
	 * Erstellt eine 410 Meldung
	 *
	 * @return void
	 */
	public function create410()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create410');
			plugins::hookCall('before', 'error', 'create410');
		}

		header('HTTP/1.1 410 Gone');
		exit;
	}


	/**
	 * Erstellt eine 411 Meldung
	 *
	 * @return void
	 */
	public function create411()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create411');
			plugins::hookCall('before', 'error', 'create411');
		}

		header('HTTP/1.1 411 Length Required');
		exit;
	}


	/**
	 * Erstellt eine 412 Meldung
	 *
	 * @return void
	 */
	public function create412()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create412');
			plugins::hookCall('before', 'error', 'create412');
		}

		header('HTTP/1.1 412 Precondition Failed');
		exit;
	}


	/**
	 * Erstellt eine 413 Meldung
	 *
	 * @return void
	 */
	public function create413()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create413');
			plugins::hookCall('before', 'error', 'create413');
		}

		header('HTTP/1.1 413 Request Entity Too Large');
		exit;
	}


	/**
	 * Erstellt eine 414 Meldung
	 *
	 * @return void
	 */
	public function create414()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create414');
			plugins::hookCall('before', 'error', 'create414');
		}

		header('HTTP/1.1 414 Request-URI Too Long');
		exit;
	}


	/**
	 * Erstellt eine 415 Meldung
	 *
	 * @return void
	 */
	public function create415()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create415');
			plugins::hookCall('before', 'error', 'create415');
		}

		header('HTTP/1.1 415 Unsupported Media Type');
		exit;
	}


	/**
	 * Erstellt eine 416 Meldung
	 *
	 * @return void
	 */
	public function create416()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create416');
			plugins::hookCall('before', 'error', 'create416');
		}

		header('HTTP/1.1 416 Requested Range Not Satisfiable');
		exit;
	}


	/**
	 * Erstellt eine 417 Meldung
	 *
	 * @return void
	 */
	public function create417()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create417');
			plugins::hookCall('before', 'error', 'create417');
		}

		header('HTTP/1.1 417 Expectation Failed');
		exit;
	}


	/**
	 * Erstellt eine 500 Meldung
	 *
	 * @return void
	 */
	public function create500()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create500');
			plugins::hookCall('before', 'error', 'create500');
		}

		header('HTTP/1.1 500 Internal Server Error');
		exit;
	}


	/**
	 * Erstellt eine 501 Meldung
	 *
	 * @return void
	 */
	public function create501()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create501');
			plugins::hookCall('before', 'error', 'create501');
		}

		header('HTTP/1.1 501 Not Implemented');
		exit;
	}


	/**
	 * Erstellt eine 502 Meldung
	 *
	 * @return void
	 */
	public function create502()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create502');
			plugins::hookCall('before', 'error', 'create502');
		}

		header('HTTP/1.1 502 Bad Gateway');
		exit;
	}


	/**
	 * Erstellt eine 503 Meldung
	 *
	 * @return void
	 */
	public function create503()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create503');
			plugins::hookCall('before', 'error', 'create503');
		}

		header('HTTP/1.1 503 Service Unavailable');
		exit;
	}


	/**
	 * Erstellt eine 504 Meldung
	 *
	 * @return void
	 */
	public function create504()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create504');
			plugins::hookCall('before', 'error', 'create504');
		}

		header('HTTP/1.1 504 Gateway Timeout');
		exit;
	}


	/**
	 * Erstellt eine 505 Meldung
	 *
	 * @return void
	 */
	public function create505()
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'error', 'create505');
			plugins::hookCall('before', 'error', 'create505');
		}

		header('HTTP/1.1 505 HTTP Version Not Supported');
		exit;
	}
}