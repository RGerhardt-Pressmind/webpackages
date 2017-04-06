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

namespace package\system\core;

/**
 * REST Klasse
 *
 * Stellt Rest API Anfragen an Systeme und gibt dessen Ergebnisse zurück.
 * Möglich sind GET, PUT, POST und DELETE Anfragen. Je nach System,
 * welches man Ansprechen möchte, können positive sowie negative
 * Ergebnisse zurück gegeben werden.
 *
 * @method mixed call(string $url, string $method = 'GET', array $data = array(), array $params = array())
 * @method mixed get(string $url, array $params = array())
 * @method mixed post(string $url, array $data = array(), array $params = array())
 * @method mixed put(string $url, array $data = array(), array $params = array())
 * @method mixed delete(string $url, array $params = array())
 */
class restClient extends initiator
{
	const METHODE_GET    = 'GET';
	const METHODE_PUT    = 'PUT';
	const METHODE_POST   = 'POST';
	const METHODE_DELETE = 'DELETE';

	protected $validMethods = array(self::METHODE_GET, self::METHODE_PUT, self::METHODE_POST, self::METHODE_DELETE);

	protected $apiUrl;

	protected $cURL;

	public function __construct($apiUrl, $username, $apiKey)
	{
		parent::__construct();

		$this->apiUrl = rtrim($apiUrl, '/').'/';
		$this->cURL   = curl_init();

		curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
		curl_setopt($this->cURL, CURLOPT_USERPWD, $username.':'.$apiKey);
		curl_setopt($this->cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',));
	}

	/**
	 * Ruft eine Rest Anfrage auf
	 *
	 * @param        $url
	 * @param string $method
	 * @param array  $data
	 * @param array  $params
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function _call($url, $method = self::METHODE_GET, $data = array(), $params = array())
	{
		if(!in_array($method, $this->validMethods))
		{
			throw new \Exception('Invalid HTTP-Methode: '.$method);
		}
		$queryString = '';

		if(!empty($params))
		{
			$queryString = http_build_query($params);
		}

		$url        = rtrim($url, '?').'?';
		$url        = $this->apiUrl.$url.$queryString;
		$dataString = json_encode($data);
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $dataString);
		$result = curl_exec($this->cURL);

		return $this->prepareResponse($result);
	}

	/**
	 * Ruft eine GET Rest Anfrage auf
	 *
	 * @param string $url
	 * @param array  $params
	 *
	 * @return mixed
	 */
	protected function _get($url, $params = array())
	{
		return $this->call($url, self::METHODE_GET, array(), $params);
	}

	/**
	 * Ruft eine POST Rest Anfrage auf
	 *
	 * @param string $url
	 * @param array  $data
	 * @param array  $params
	 *
	 * @return mixed
	 */
	protected function _post($url, $data = array(), $params = array())
	{
		return $this->call($url, self::METHODE_POST, $data, $params);
	}

	/**
	 * Ruft eine PUT Rest Anfrage auf
	 *
	 * @param string $url
	 * @param array  $data
	 * @param array  $params
	 *
	 * @return mixed
	 */
	protected function _put($url, $data = array(), $params = array())
	{
		return $this->call($url, self::METHODE_PUT, $data, $params);
	}

	/**
	 * Ruft eine DELETE Rest Anfrage auf
	 *
	 * @param string $url
	 * @param array  $params
	 *
	 * @return mixed
	 */
	protected function _delete($url, $params = array())
	{
		return $this->call($url, self::METHODE_DELETE, array(), $params);
	}

	/**
	 * Gibt das azsgewertete Ergebnisse zurück
	 *
	 * @param $result
	 *
	 * @return mixed|string
	 */
	protected function prepareResponse($result)
	{
		if(null === $decodedResult = json_decode($result, true))
		{
			$jsonErrors = array(JSON_ERROR_NONE => 'Es ist kein Fehler aufgetreten', JSON_ERROR_DEPTH => 'Die maximale Stacktiefe wurde erreicht', JSON_ERROR_CTRL_CHAR => 'Steuerzeichenfehler, möglicherweise fehlerhaft kodiert', JSON_ERROR_SYNTAX => 'Syntaxfehler',);

			return $jsonErrors[json_last_error()];
		}
		if(!isset($decodedResult['success']))
		{
			return 'Invalid Response';
		}
		if(!$decodedResult['success'])
		{
			return 'No Success: '.$decodedResult['message'];
		}

		return $decodedResult;
	}
}