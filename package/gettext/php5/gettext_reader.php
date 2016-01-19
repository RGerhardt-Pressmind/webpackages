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

    @category   gettext_reader.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

class gettext_reader
{
	public $error = 0; // public variable that holds error code (0 if no error)

	private $BYTEORDER = 0, $STREAM = NULL, $short_circuit = false, $enable_cache = false, $originals = NULL, $translations = NULL, $pluralheader = NULL, $total = 0, $table_originals = NULL, $table_translations = NULL, $cache_translations = NULL;

  /**
   * Constructor
   *
   * @param FileReader $Reader the StreamReader object
   * @param boolean $enable_cache Enable or disable caching of strings (default on)
   */
	public function __construct(FileReader $Reader, $enable_cache = true)
	{
		if(!$Reader || isset($Reader->error))
		{
	 		$this->short_circuit = true;
		}

		$this->enable_cache = $enable_cache;

		$MAGIC1 = "\x95\x04\x12\xde";
		$MAGIC2 = "\xde\x12\x04\x95";

		$this->STREAM = $Reader;
		$magic = $this->read(4);

		if($magic == $MAGIC1)
		{
	  		$this->BYTEORDER = 1;
		}
		elseif($magic == $MAGIC2)
		{
	  		$this->BYTEORDER = 0;
		}
		else
		{
	  		$this->error = 1;
	  		return false;
		}

		$revision = $this->readint();

		$this->total 		=	$this->readint();
		$this->originals 	=	$this->readint();
		$this->translations =	$this->readint();
	}

   /**
   	* Reads a 32bit Integer from the Stream
   	*
   	* @access private
   	* @return Integer from the Stream
   	*/
  	private function readint()
  	{
     	if($this->BYTEORDER == 0)
     	{
        	$input	=	unpack('V', $this->STREAM->read(4));
        	return array_shift($input);
      	}
      	else
      	{
        	$input	=	unpack('N', $this->STREAM->read(4));
        	return array_shift($input);
      	}
    }

	private function read($bytes)
	{
    	return $this->STREAM->read($bytes);
  	}

  	/**
	* Reads an array of Integers from the Stream
	*
	* @param int $count How many elements should be read
	* @return Array of Integers
	*/
	private function readintarray($count)
	{
		if($this->BYTEORDER == 0)
		{
			return unpack('V'.$count, $this->STREAM->read(4 * $count));
	  	}
	  	else
	  	{
			return unpack('N'.$count, $this->STREAM->read(4 * $count));
	  	}
	}

	/**
	* Loads the translation tables from the MO file into the cache
	* If caching is enabled, also loads all strings into a cache
	* to speed up translation lookups
	*
	* @access private
	*/
	private function load_tables()
	{
		if(is_array($this->cache_translations) && is_array($this->table_originals) && is_array($this->table_translations))
		{
			return;
		}

		if(!is_array($this->table_originals))
		{
	  		$this->STREAM->seekto($this->originals);
	  		$this->table_originals = $this->readintarray($this->total * 2);
			}
		if(!is_array($this->table_translations))
		{
	  		$this->STREAM->seekto($this->translations);
	  		$this->table_translations = $this->readintarray($this->total * 2);
		}

		if($this->enable_cache)
		{
	  		$this->cache_translations = array ();

	  		for($i = -1; ++$i < $this->total;)
	  		{
				$this->STREAM->seekto($this->table_originals[$i * 2 + 2]);
				$original = $this->STREAM->read($this->table_originals[$i * 2 + 1]);
				$this->STREAM->seekto($this->table_translations[$i * 2 + 2]);
				$translation = $this->STREAM->read($this->table_translations[$i * 2 + 1]);
				$this->cache_translations[$original] = $translation;
	  		}
		}
	}

	/**
	* Returns a string from the "originals" table
	*
	* @access private
	* @param int num Offset number of original string
	* @return string Requested string if found, otherwise ''
	*/
	private function get_original_string($num)
	{
		$length = $this->table_originals[$num * 2 + 1];
		$offset = $this->table_originals[$num * 2 + 2];

		if(!$length)
		{
			return '';
		}

		$this->STREAM->seekto($offset);
		$data = $this->STREAM->read($length);

		return (string)$data;
	}

	/**
	* Returns a string from the "translations" table
	*
	* @access private
	* @param int num Offset number of original string
	* @return string Requested string if found, otherwise ''
	*/
	private function get_translation_string($num)
	{
		$length = $this->table_translations[$num * 2 + 1];
		$offset = $this->table_translations[$num * 2 + 2];

		if(!$length)
		{
			return '';
		}

		$this->STREAM->seekto($offset);
		$data = $this->STREAM->read($length);
		return (string)$data;
	}

	/**
	* Binary search for string
	*
	* @access private
	* @param string string
	* @param int start (internally used in recursive function)
	* @param int end (internally used in recursive function)
	* @return int string number (offset in originals table)
	*/
	private function find_string($string, $start = -1, $end = -1)
	{
		if(($start == -1) || ($end == -1))
		{
	  		$start = 0;
	  		$end = $this->total;
		}

		if(abs($start - $end) <= 1)
		{
	  		$txt = $this->get_original_string($start);

	  		if($string == $txt)
			{
				return $start;
			}
			else
			{
				return -1;
			}

		}
		else if($start > $end)
		{
	  		return $this->find_string($string, $end, $start);
		}
		else
		{
	  		$half = (int)(($start + $end) / 2);
	 		$cmp = strcmp($string, $this->get_original_string($half));

	  		if($cmp == 0)
			{
				return $half;
			}
	  		else if($cmp < 0)
			{
				return $this->find_string($string, $start, $half);
			}
	 		else
			{
				return $this->find_string($string, $half, $end);
			}
		}
	}

	/**
	* Translates a string
	*
	* @access public
	* @param string string to be translated
	* @return string translated string (or original, if not found)
	*/
	public function translate($string)
	{
		if($this->short_circuit)
		{
			return $string;
		}

		$this->load_tables();

		if($this->enable_cache)
		{
			if(array_key_exists($string, $this->cache_translations))
			{
				return $this->cache_translations[$string];
			}
			else
			{
				return $string;
			}
		}
		else
		{
			$num = $this->find_string($string);

		  	if($num == -1)
		  	{
				return $string;
			}
		  	else
		  	{
				return $this->get_translation_string($num);
		  	}
		}
	}


	/**
	* Sanitize plural form expression for use in PHP eval call.
	*
	* @access private
	* @param $expr
	* @return string sanitized plural form expression
	*/
	private function sanitize_plural_expression($expr)
	{
		$expr = preg_replace('@[^a-zA-Z0-9_:;\(\)\?\|\&=!<>+*/\%-]@', '', $expr);

		$expr .= ';';
		$res 	= '';
		$p 		= 0;

		for($i = -1; ++$i < strlen($expr);)
		{
	  		$ch = $expr[$i];

	  		switch($ch)
	  		{
				case '?':
					$res .= ' ? (';
					++$p;
				break;
	  			case ':':
					$res .= ') : (';
				break;
	  			case ';':
					$res .= str_repeat( ')', $p) . ';';
					$p = 0;
				break;
	  			default:
					$res .= $ch;
				break;
	  		}
		}

		return $res;
	}


	/**
	* Parse full PO header and extract only plural forms line.
	*
	* @access private
	* @param $header
	* @return string verbatim plural form header field
	*/
	private function extract_plural_forms_header_from_po_header($header)
	{
		if(preg_match("/(^|\n)plural-forms: ([^\n]*)\n/i", $header, $regs))
		{
			$expr = $regs[2];
		}
		else
		{
			$expr = "nplurals=2; plural=n == 1 ? 0 : 1;";
		}

		return $expr;
	}


	/**
	* Get possible plural forms from MO header
	*
	* @access private
	* @return string plural form header
	*/
	private function get_plural_forms()
	{
		$this->load_tables();

		if(!is_string($this->pluralheader))
		{
	  		if($this->enable_cache)
	  		{
				$header = $this->cache_translations[""];
	  		}
	  		else
	  		{
				$header = $this->get_translation_string(0);
	  		}

	  		$expr = $this->extract_plural_forms_header_from_po_header($header);
	  		$this->pluralheader = $this->sanitize_plural_expression($expr);
		}

		return $this->pluralheader;
	}


	/**
	* Detects which plural form to take
	*
	* @access private
	* @param $n
	* @return int array index of the right plural form
	*/
	private function select_string($n)
	{
		$string = $this->get_plural_forms();
		$string = str_replace('nplurals',"\$total",$string);
		$string = str_replace("n",$n,$string);
		$string = str_replace('plural',"\$plural",$string);

		$total = 0;
		$plural = 0;

		eval($string);

		if($plural >= $total)
		{
			$plural = $total - 1;
		}

		return $plural;
	}


	/**
	* Plural version of gettext
	*
	* @access public
	* @param string $single
	* @param string $plural
	* @param string $number
	* @return translated plural form
	*/
	public function ngettext($single, $plural, $number)
	{
		if($this->short_circuit)
		{
	 		if($number != 1)
			{
				return $plural;
			}
	 		else
			{
				return $single;
			}
		}

		$select = $this->select_string($number);

		$key = $single . chr(0) . $plural;

		if($this->enable_cache)
		{
	  		if(!array_key_exists($key, $this->cache_translations))
	  		{
				return ($number != 1) ? $plural : $single;
	  		}
	  		else
	  		{
				$result = $this->cache_translations[$key];
				$list = explode(chr(0), $result);
				return $list[$select];
	  		}
		}
		else
		{
	  		$num = $this->find_string($key);

	  		if($num == -1)
	  		{
				return ($number != 1) ? $plural : $single;
	  		}
	  		else
	  		{
				$result = $this->get_translation_string($num);
				$list = explode(chr(0), $result);
				return $list[$select];
	  		}
		}
	}


	public function pgettext($context, $msgid)
	{
		$key = $context . chr(4) . $msgid;
		$ret = $this->translate($key);

		if(strpos($ret, "\004") !== false)
		{
	  		return $msgid;
		}
		else
		{
	  		return $ret;
		}
	}


	public function npgettext($context, $singular, $plural, $number)
	{
		$key = $context . chr(4) . $singular;
		$ret = $this->ngettext($key, $plural, $number);

		if(strpos($ret, "\004") !== false)
		{
			return $singular;
		}
		else
		{
		 	return $ret;
		}
	}
} 