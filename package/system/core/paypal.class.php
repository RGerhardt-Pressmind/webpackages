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

/**
 * Stellt Anfragen an die PayPal API
 *
 * Mit der Klasse paypal kann man Anfragen und Bezahlungen anweisen oder abfragen.
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       paypal
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class paypal
{
	/**
	 * @var array Letzte Fehlermeldung(en)
	 */
	public $_errors = array();

	/**
	 * API Zugangsdaten
	 * Hier tragen Sie ihre richtigen Zugangsdaten für Live/Sandbox ein
	 *
	 * @var array
	 */
	public $_credentials = array('USER' => 'seller_1297608781_biz_api1.lionite.com', 'PWD' => '1297608792', 'SIGNATURE' => 'A3g66.FS3NAf4mkHn3BDQdpo6JD.ACcPc4wMrInvUEqO3Uapovity47p');

	/**
	 * API Zugrifsspunkt - Sandbox
	 * Sandbox - https://api-3t.sandbox.paypal.com/nvp
	 *
	 * @var string
	 */
	protected $_endPoint_sandbox = 'https://api-3t.sandbox.paypal.com/nvp';

	/**
	 * API Zugriffspunkt - Live
	 * Live - https://api-3t.paypal.com/nvp
	 *
	 * @var string
	 */
	protected $_endPoint_live = 'https://api-3t.paypal.com/nvp';

	/**
	 * API Version
	 *
	 * @var string
	 */
	protected $_version = '74.0';

	/**
	 * @var bool
	 */
	public $isDemo = false;

	/**
	 * Generiert einen API Request
	 *
	 * @param string $method string API Methode zur Anfrage
	 * @param array  $params Zusätzliche Anforderungsparameter
	 *
	 * @return array / boolean Ergebniss als Array / boolean false bei Fehler
	 */
	public function request($method, $params = array())
	{
		if(class_exists('\package\core\plugins') === true)
		{
			plugins::hookShow('before', 'paypal', 'request', array($method, $params));
			$plugins = plugins::hookCall('before', 'paypal', 'request', array($method, $params));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		$this->_errors = array();

		if(empty($method) === true)
		{
			$this->_errors = array('API method is missing');

			return false;
		}

		$requestParams = array('METHOD' => $method, 'VERSION' => $this->_version) + $this->_credentials;

		$request = http_build_query($requestParams + $params);

		if($this->isDemo === true)
		{
			$endpoint = $this->_endPoint_sandbox;
		}
		else
		{
			$endpoint = $this->_endPoint_live;
		}

		$curlOptions = array(CURLOPT_URL => $endpoint, CURLOPT_VERBOSE => 1, CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2, CURLOPT_RETURNTRANSFER => 1, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => $request);

		$ch = curl_init();
		curl_setopt_array($ch, $curlOptions);

		$response = curl_exec($ch);

		if(class_exists('\package\core\plugins') === true)
		{
			$plugins = plugins::hookCall('after', 'paypal', 'request', array($response));

			if($plugins != null)
			{
				return $plugins;
			}
		}

		if(curl_errno($ch))
		{
			$this->_errors = curl_error($ch);
			curl_close($ch);

			return false;
		}
		else
		{
			curl_close($ch);

			$responseArray = array();

			parse_str($response, $responseArray);

			return $responseArray;
		}
	}
}