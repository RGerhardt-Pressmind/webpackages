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
    
    @category   XML.class.php
	@package    webpackage
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 webpackage
	@license    http://www.gnu.org/licenses/
*/
namespace package;

class XML
{
	private $xml = null;

	/**
	 * Lädt ein XML String / Datei / URL
	 *
	 * @param string $xml
	 * @throws \Exception
	 */
	public function loadXML($xml)
	{
		//XML von einer URL laden
		if(filter_var($xml, FILTER_VALIDATE_URL) !== false)
		{
			$xmlData	=	curl::getData($xml);

			if(empty($xmlData) === false)
			{
				$this->xml	=	simplexml_load_string($xmlData);
			}

			return;
		}

		$xmlFile	=	new \SplFileInfo($xml);

		// Wenn es eine lokale Datei ist, laden
		if($xmlFile->isFile() === true)
		{
			$xmlData	=	file_get_contents($xmlFile);

			if($xmlData !== false)
			{
				$this->xml	=	simplexml_load_string($xmlData);
			}

			return;
		}

		//Kontrollieren ob es sich um ein XML String handelt
		$result	=	simplexml_load_string($xml, 'SimpleXmlElement', LIBXML_NOERROR+LIBXML_ERR_FATAL+LIBXML_ERR_NONE);

		if($result !== false)
		{
			$this->xml	=	simplexml_load_string($xml);
			return;
		}

		throw new \Exception('Error: Not XML define');
	}


	/**
	 * Gibt das XML als assoziatives Array zurück
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function toArray()
	{
		if($this->xml === null)
		{
			throw new \Exception('Error: Not XML define');
		}

		$xml	=	json_encode($this->xml);
		$xml	=	json_decode($xml, true);

		return $xml;
	}


	/**
	 * Gibt das XML als Objekt Array zurück
	 *
	 * @return object
	 * @throws \Exception
	 */
	public function toObject()
	{
		if($this->xml === null)
		{
			throw new \Exception('Error: Not XML define');
		}

		$xml	=	json_encode($this->xml);
		$xml	=	json_decode($xml);

		return $xml;
	}


	/**
	 * Gibt das XML als SimpleXML zurück
	 *
	 * @return \SimpleXMLElement
	 * @throws \Exception
	 */
	public function getSimpleXML()
	{
		if($this->xml === null)
		{
			throw new \Exception('Error: Not XML define');
		}

		return $this->xml;
	}
}