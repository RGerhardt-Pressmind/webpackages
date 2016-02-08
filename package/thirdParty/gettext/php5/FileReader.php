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

    @category   FileReader.php
	@package    Packages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2015 Packages
	@license    http://www.gnu.org/licenses/
*/

class FileReader
{
	private $_pos, $_fd, $_length;

	public function __construct($filename)
	{
		if(is_file($filename) === true)
		{
			$this->_length	=	filesize($filename);
			$this->_pos 	= 	0;
			$this->_fd 		= 	fopen($filename,'rb');

			if(!$this->_fd)
			{
				$this->error = 3;
			}
		}
		else
		{
			$this->error = 2;
		}
	}

	public function read($bytes)
	{
		if($bytes && $this->_fd != null)
		{
	  		fseek($this->_fd, $this->_pos);

	  		$data = '';

	  		while($bytes > 0)
	  		{
				$chunk  = fread($this->_fd, $bytes);
				$data  .= $chunk;
				$bytes -= strlen($chunk);
	  		}

	  		$this->_pos = ftell($this->_fd);

	  		return $data;
		}
		else
		{
			return '';
		}
	}

	public function seekto($pos)
	{
		fseek($this->_fd, $pos);
		$this->_pos = ftell($this->_fd);
		return $this->_pos;
	}
} 