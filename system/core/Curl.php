<?php
/**
 *  Copyright (C) 2010 - 2023  <Robbyn Gerhardt>
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
 * @package       webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (C) 2010 - 2023
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @since         Version 2.0.0
 * @filesource
 */

namespace system\core;

class Curl
{
	private mixed $_curl;
	private array $_curlopts	=	[];

	private array $responseData	=	[];

	/**
	 * HTTP get request
	 *
	 * @param string $url
	 * @param null $params
	 */
	public function get(string $url, $params = null)
	{
		if(!empty($params) && is_array($params))
		{
			$url	.=	http_build_query($params);
		}

		$this->_setCurl($url);

		$this->_setCurlopts();
		$this->_callCurl();
	}

	/**
	 * HTTP post request
	 *
	 * @param string $url
	 * @param null $params
	 */
	public function post(string $url, $params = null)
	{
		$this->_setCurl($url);

		$this->setOpt(CURLOPT_POST, true);

		if(!empty($params))
		{
			$this->setOpt(CURLOPT_POSTFIELDS, http_build_query($params));
		}

		$this->_setCurlopts();
		$this->_callCurl();
	}

	/**
	 * HTTP put request
	 *
	 * @param string $url
	 * @param null $params
	 */
	public function put(string $url, $params = null)
	{
		$this->_setCurl($url);

		$this->setOpt(CURLOPT_CUSTOMREQUEST, 'PUT');

		if(!empty($params))
		{
			$this->setOpt(CURLOPT_POSTFIELDS, http_build_query($params));
		}

		$this->_setCurlopts();
		$this->_callCurl();
	}

	/**
	 * HTTP delete request
	 *
	 * @param string $url
	 * @param null $params
	 */
	public function delete(string $url, $params = null)
	{
		if(!empty($params) && is_array($params))
		{
			$url	.=	http_build_query($params);
		}

		$this->_setCurl($url);

		$this->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');

		$this->_setCurlopts();
		$this->_callCurl();
	}

	/**
	 * Clear curl
	 */
	public function close()
	{
		$this->_curlopts	=	[];
		$this->responseData	=	[];

		$this->_curl		=	null;
	}


	/**
	 * Set basic authentication
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function setBasicAuthentication(string $username, string $password)
	{
		$this->_curlopts[CURLOPT_HTTPHEADER][]	=	'Authorization: Basic '.base64_encode($username.':'.$password);
	}

	/**
	 * Set user agent
	 *
	 * @param string $agent
	 */
	public function setUserAgent(string $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0')
	{
		$this->_curlopts[CURLOPT_USERAGENT]	=	$agent;
	}

	/**
	 * Set referrer
	 *
	 * @param string $referrer
	 */
	public function setReferrer(string $referrer)
	{
		$this->_curlopts[CURLOPT_REFERER]	=	$referrer;
	}

	/**
	 * Set individual header
	 *
	 * @param string $header
	 * @param string $value
	 */
	public function setHeader(string $header, string $value)
	{
		$this->_curlopts[CURLOPT_HTTPHEADER][]	=	$header.': '.$value;
	}

	/**
	 * Set cookie
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setCookie(string $key, string $value)
	{
		$this->_curlopts[CURLOPT_HTTPHEADER][]	=	'Cookie: '.$key.'='.$value;
	}

	/**
	 * Set curl opt manually
	 *
	 * @param string $opt
	 * @param mixed $value
	 */
	public function setOpt(string $opt, mixed $value)
	{
		$this->_curlopts[$opt]	=	$value;
	}

	/**
	 * Set curl call port
	 *
	 * @param mixed $port
	 */
	public function setPort(mixed $port)
	{
		$this->_curlopts[CURLOPT_PORT]	=	$port;
	}

	/**
	 * Set curl call maximal timeout
	 *
	 * @param int $timeout
	 */
	public function setTimeout(int $timeout)
	{
		$this->_curlopts[CURLOPT_TIMEOUT]	=	$timeout;
	}

	/**
	 * Set connect timeout
	 *
	 * @param int $timeout
	 */
	public function setConnectTimeout(int $timeout)
	{
		$this->_curlopts[CURLOPT_CONNECTTIMEOUT]	=	$timeout;
	}

	/**
	 * Set all curlopts before call
	 */
	private function _setCurlopts()
	{
		if(!empty($this->_curlopts))
		{
			foreach($this->_curlopts as $key => $value)
			{
				curl_setopt($this->_curl, $key, $value);
			}
		}
	}

	/**
	 * Set curl
	 *
	 * @param string $url
	 */
	private function _setCurl(string $url)
	{
		$this->_curl	=	curl_init($url);
	}

	/**
	 * Get response from last request
	 *
	 * @param bool $jsonDecode
	 *
	 * @return mixed
	 */
	public function getResponse(bool $jsonDecode = false): mixed
	{
		if(isset($this->responseData['response']))
		{
			if($jsonDecode)
			{
				return json_decode($this->responseData['response'], true);
			}

			return $this->responseData['response'];
		}

		return false;
	}

	/**
	 * Get http code from last request
	 *
	 * @return bool|mixed
	 */
	public function getHTTPCode(): mixed
	{
		return $this->_get('httpCode');
	}

	/**
	 * Get filetime from last request
	 *
	 * @return mixed
	 */
	public function getFiletime(): mixed
	{
		return $this->_get('filetime');
	}

	/**
	 * Get total request time from last request
	 *
	 * @return mixed
	 */
	public function getTotalTime(): mixed
	{
		return $this->_get('totaltime');
	}

	/**
	 * Get name loikup time from last request
	 *
	 * @return mixed
	 */
	public function getNameLookupTime(): mixed
	{
		return $this->_get('nameLookupTime');
	}

	/**
	 * Get connect time from last request
	 *
	 * @return mixed
	 */
	public function getConnectTime(): mixed
	{
		return $this->_get('connectTime');
	}

	/**
	 * Get pretransfer time from last request
	 *
	 * @return mixed
	 */
	public function getPretransferTime(): mixed
	{
		return $this->_get('pretransferTime');
	}

	/**
	 * Get start transfer time from last request
	 *
	 * @return mixed
	 */
	public function getStartTransferTime(): mixed
	{
		return $this->_get('startTransferTime');
	}

	/**
	 * Get redirect time
	 *
	 * @return mixed
	 */
	public function getRedirectTime(): mixed
	{
		return $this->_get('redirectTime');
	}

	/**
	 * Get effective url from last request
	 *
	 * @return mixed
	 */
	public function getURL(): mixed
	{
		return $this->_get('effectiveURL');
	}

	/**
	 * Get size upload from last request
	 *
	 * @return mixed
	 */
	public function getSizeUpload(): mixed
	{
		return $this->_get('sizeUpload');
	}

	/**
	 * Get size download from last request
	 *
	 * @return mixed
	 */
	public function getSizeDownload(): mixed
	{
		return $this->_get('sizeDownload');
	}

	/**
	 * Get speed download from last request
	 *
	 * @return mixed
	 */
	public function getSpeedDownload(): mixed
	{
		return $this->_get('speedDownload');
	}

	/**
	 * Get speed upload from last request
	 *
	 * @return mixed
	 */
	public function getSpeedUpload(): mixed
	{
		return $this->_get('speedUpload');
	}

	/**
	 * Get header size from last request
	 *
	 * @return mixed
	 */
	public function getHeaderSize(): mixed
	{
		return $this->_get('headerSize');
	}

	/**
	 * Get header from last request
	 *
	 * @return mixed
	 */
	public function getHeader(): mixed
	{
		return $this->_get('headerOut');
	}

	/**
	 * Get request size from last request
	 *
	 * @return mixed
	 */
	public function getRequestSize(): mixed
	{
		return $this->_get('requestSize');
	}

	/**
	 * Get content length download from last request
	 *
	 * @return mixed
	 */
	public function getContentLengthDownload(): mixed
	{
		return $this->_get('contentLengthDownload');
	}

	/**
	 * Get content length upload from last request
	 *
	 * @return mixed
	 */
	public function getContentLengthUpload(): mixed
	{
		return $this->_get('contentLengthUpload');
	}

	/**
	 * Get content type from last request
	 *
	 * @return mixed
	 */
	public function getContentType(): mixed
	{
		return $this->_get('contentType');
	}

	/**
	 * Get data from last request
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	private function _get(string $key): mixed
	{
		return ($this->responseData[$key] ?? false);
	}


	/**
	 * Call curl with all opts
	 */
	private function _callCurl()
	{
		$data	=	curl_exec($this->_curl);

		$this->responseData['response']	=	$data;

		$getInfos	=	[
			'effectiveURL'			=>	CURLINFO_EFFECTIVE_URL,
			'httpCode'				=>	CURLINFO_HTTP_CODE,
			'filetime'				=>	CURLINFO_FILETIME,
			'totalTime'				=>	CURLINFO_TOTAL_TIME,
			'nameLookupTime'		=>	CURLINFO_NAMELOOKUP_TIME,
			'connectTime'			=>	CURLINFO_CONNECT_TIME,
			'pretransferTime'		=>	CURLINFO_PRETRANSFER_TIME,
			'startTransferTime'		=>	CURLINFO_STARTTRANSFER_TIME,
			'redirectTime'			=>	CURLINFO_REDIRECT_TIME,
			'sizeUpload'			=>	CURLINFO_SIZE_UPLOAD,
			'sizeDownload'			=>	CURLINFO_SIZE_DOWNLOAD,
			'speedDownload'			=>	CURLINFO_SPEED_DOWNLOAD,
			'speedUpload'			=>	CURLINFO_SPEED_UPLOAD,
			'headerSize'			=>	CURLINFO_HEADER_SIZE,
			'headerOut'				=>	CURLINFO_HEADER_OUT,
			'requestSize'			=>	CURLINFO_REQUEST_SIZE,
			'contentLengthDownload'	=>	CURLINFO_CONTENT_LENGTH_DOWNLOAD,
			'contentLengthUpload'	=>	CURLINFO_CONTENT_LENGTH_UPLOAD,
			'contentType'			=>	CURLINFO_CONTENT_TYPE
		];

		foreach($getInfos as $key => $opt)
		{
			$info	=	curl_getinfo($this->_curl, $opt);

			$this->responseData[$key]	=	$info;
		}

		curl_close($this->_curl);
	}
}
