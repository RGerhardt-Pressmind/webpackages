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
 * @subpackage    core
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2017, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link          http://webpackages.de
 * @since         Version 2.0.0
 * @filesource
 */

namespace package\core;

use package\exceptions\xmlException;
use package\system\core\initiator;

/**
 * XML Dateien / Strings validieren
 *
 * Um eine XML Datei / String zu benutzen müssen Sie erst validiert und überprüft werden. Dies übernimmt die XML Klasse.
 *
 * @method void loadXML(string $xml)
 * @method array toArray()
 * @method object toObject()
 * @method \SimpleXMLElement getSimpleXML()
 *
 * @package        Webpackages
 * @subpackage     core
 * @category       xml
 * @author         Robbyn Gerhardt <gerhardt@webpackages.de>
 */
class XML extends initiator
{
	private $xml = null;

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		unset($this->xml);
	}

	/**
	 * Lädt ein XML String / Datei / URL
	 *
	 * @param string $xml
	 *
	 * @return void
	 * @throws xmlException
	 */
	protected function _loadXML($xml)
	{
		//XML von einer URL laden
		if(filter_var($xml, FILTER_VALIDATE_URL) != false)
		{
			$xmlData = curl::get_data($xml);

			if(!empty($xmlData))
			{
				$this->xml = simplexml_load_string($xmlData);
			}

			return;
		}

		if(!class_exists('\SplFileInfo'))
		{
			throw new xmlException('Error: Class SplFileInfo not exists');
		}

		$xmlFile = new \SplFileInfo($xml);

		// Wenn es eine lokale Datei ist, laden
		if($xmlFile->isFile())
		{
			$xmlData = file_get_contents($xmlFile);

			if($xmlData != false)
			{
				$this->xml = simplexml_load_string($xmlData);
			}

			return;
		}

		//Kontrollieren ob es sich um ein XML String handelt
		$result = simplexml_load_string($xml, 'SimpleXmlElement', LIBXML_NOERROR + LIBXML_ERR_FATAL + LIBXML_ERR_NONE);

		if($result != false)
		{
			$this->xml = simplexml_load_string($xml);

			return;
		}

		throw new xmlException('Error: Not XML define');
	}

	/**
	 * Gibt das XML als assoziatives Array zurück
	 *
	 * @return array Gibt das XML Objekt als Array Konvertiert zurück
	 * @throws xmlException
	 */
	protected function _toArray()
	{
		if($this->xml == null)
		{
			throw new xmlException('Error: Not XML define');
		}

		$xml = json_encode($this->xml);
		$xml = json_decode($xml, true);

		return $xml;
	}

	/**
	 * Gibt das XML als Objekt Array zurück
	 *
	 * @return object
	 * @throws xmlException
	 */
	protected function _toObject()
	{
		if($this->xml == null)
		{
			throw new xmlException('Error: Not XML define');
		}

		$xml = json_encode($this->xml);
		$xml = json_decode($xml);

		return $xml;
	}

	/**
	 * Gibt das XML als SimpleXML zurück
	 *
	 * @return \SimpleXMLElement
	 * @throws xmlException
	 */
	protected function _getSimpleXML()
	{
		if($this->xml == null)
		{
			throw new xmlException('Error: Not XML define');
		}

		return $this->xml;
	}
}