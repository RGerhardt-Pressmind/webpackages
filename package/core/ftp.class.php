<?php
/*
    Copyright (C) 2016  <Robbyn Gerhardt>

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
    
    @category   ftp.class.php
	@package    webpackages
	@author     Robbyn Gerhardt <robbyn@worldwideboard.de>
	@copyright  2010-2016 Webpackages
	@license    http://www.gnu.org/licenses/
*/

namespace package;


class ftp
{
	private $_ftp;

	/**
	 * Stellt eine FTP Verbindung her
	 *
	 * @param string $host Der Host des FTP Servers mit dem sich verbunden werden soll
	 * @param bool $ssl Wenn es sich um eine SSL Verbindung handelt oder nicht
	 * @param int $port Auf welchem Port verbunden werden soll
	 * @param int $timeout Nach wievielen Sekunden soll der Verbindungsversuch abbrechen
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function connect($host, $ssl = false, $port = 21, $timeout = 90)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'connect', array($host, $ssl, $port, $timeout));

			$plugin	=	plugins::hookCall('before', 'ftp', 'connect', array($host, $ssl, $port, $timeout));

			if($plugin != null)
			{
				$this->_ftp	=	$plugin;
			}
		}

		if($ssl === true)
		{
			$this->_ftp	=	@ftp_ssl_connect($host, $port, $timeout);
		}
		else
		{
			$this->_ftp	=	@ftp_connect($host, $port, $timeout);
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'ftp', 'connect', array($this->_ftp));
		}

		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}
	}


	/**
	 * Stellt nach der FTP Verbindung eine Zugangsverbindung her
	 *
	 * @param string $username Der Benutzername der FTP Verbindung
	 * @param string $password Das Passwort der FTP Verbindung
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function login($username, $password)
	{
		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'login', array($this->_ftp, $username, $password));
			plugins::hookCall('before', 'ftp', 'login', array($this->_ftp, $username, $password));
		}

		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		$login	=	ftp_login($this->_ftp, $username, $password);

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('after', 'ftp', 'login', array($login));
			$plugin	=	plugins::hookCall('after', 'ftp', 'login', array($login));

			if($plugin != null)
			{
				$login	=	$plugin;
			}
		}

		if($login === false)
		{
			throw new \Exception('FTP Login failed');
		}
	}


	/**
	 * Setzt die aktuelle Verbindung in den Passiven Modus
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function set_passive_modus()
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		@ftp_pasv($this->_ftp, true);
	}


	/**
	 * Lädt eine Datei vom FTP Server auf das lokale Verzeichnis
	 *
	 * @param string $remoteFile
	 * @param string $localeFile
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function get_remote_file($remoteFile, $localeFile)
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'getRemoteFile', array($this->_ftp, $remoteFile, $localeFile));
			$plugin	=	plugins::hookCall('before', 'ftp', 'getRemoteFile', array($this->_ftp, $remoteFile, $localeFile));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		return @ftp_get($this->_ftp, $localeFile, $remoteFile, FTP_BINARY);
	}


	/**
	 * Gibt das letzte Bearbeitungsdatum einer FTP Datei zurück
	 *
	 * @param string $remoteFile Der interne FTP-Pfad zur Datei
	 * @param string $timeFormat In welchem Datumsformat soll das Ergebnis zurück kommen
	 * @return bool|int|string Gibt, je nach Parameter, ein false zurück wenn die Bearbeitungszeit nicht ermittels werden konnte. Gibt ein Integer zurück wenn kein Datumsformat angegeben wurde und einen String wenn ein Datumsformat angegeben wurde.
	 * @throws \Exception
	 */
	public function modified_time($remoteFile, $timeFormat = null)
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'modifiedTime', array($this->_ftp, $remoteFile, $timeFormat));
			$plugin	=	plugins::hookCall('before', 'ftp', 'modifiedTime', array($this->_ftp, $remoteFile, $timeFormat));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$time	=	ftp_mdtm($this->_ftp, $remoteFile);

		if($time !== -1 && $timeFormat != null)
		{
			return date($timeFormat, $time);
		}

		return $time;
	}


	/**
	 * Geht zum "Eltern" Ordner, wenn möglich, zurück
	 *
	 * @return bool Gibt ein true zurück wenn der wechsel Erfolgt ist, ansonsten wird eine Exception geschmissen.
	 * @throws \Exception
	 */
	public function up()
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'up', array($this->_ftp));
			$plugin	=	plugins::hookCall('before', 'ftp', 'up', array($this->_ftp));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		$result	=	ftp_cdup($this->_ftp);

		if($result === false)
		{
			throw new \Exception('Unable to get parent folder');
		}

		return true;
	}


	/**
	 * Kontrolliert ob der angegebene interne FTP-Pfad ein Ordner ist
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Ordner der überprüft werden soll
	 * @return bool Gibt ein true zurück wenn es sich um ein Ordner handelt und ein false wenn es keiner ist.
	 * @throws \Exception
	 */
	public function is_dir($remoteDirectory = '.')
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'isDir', array($this->_ftp, $remoteDirectory));
			$plugin	=	plugins::hookCall('before', 'ftp', 'isDir', array($this->_ftp, $remoteDirectory));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		$pwd	=	@ftp_pwd($this->_ftp);

		if($pwd === false)
		{
			throw new \Exception('Unable to resolve the current directory');
		}

		if(@ftp_chdir($this->_ftp, $remoteDirectory) === true)
		{
			@ftp_chdir($this->_ftp, $pwd);
			return true;
		}

		@ftp_chdir($this->_ftp, $pwd);

		return false;
	}


	/**
	 * Gibt die Anzahl an Dateien im angegebenen FTP Verzeichnis zurück
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Verzeichnis
	 * @param string $type Hier kann noch ein Dateityp angegebene werden nach dem nur gesucht werden soll. Standartmäßig auf null
	 * @param bool $recursive Man kann einstellen ob auch alle Unterverzeichnis durchsucht werden sollen oder nicht. Standartmäßig auf true.
	 * @return int
	 * @throws \Exception
	 */
	public function count($remoteDirectory = '.', $type = null, $recursive = true)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'count', array($this->_ftp, $remoteDirectory, $type, $recursive));
			$plugin	=	plugins::hookCall('before', 'ftp', 'count', array($this->_ftp, $remoteDirectory, $type, $recursive));

			if($plugin != null)
			{
				return (int)$plugin;
			}
		}

		if($type === null)
		{
			$items	=	$this->nlist($remoteDirectory, $recursive);
		}
		else
		{
			$items	=	$this->scan_dir($remoteDirectory, $recursive);
		}

        $count 	= 	0;

        foreach($items as $item)
		{
            if($type === null || $item['type'] == $type)
			{
                ++$count;
            }
        }

        return $count;
    }


	/**
	 * Kontrolliert ob ein Verzeichnis leer ist
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Verzeichnis
	 * @return int Gibt die Anzahl an gefundenen Dateien im FTP Verzeichnis zurück
	 * @throws \Exception
	 */
	public function is_empty($remoteDirectory)
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'isEmpty', array($this->_ftp, $remoteDirectory));
			$plugin	=	plugins::hookCall('before', 'ftp', 'isEmpty', array($this->_ftp, $remoteDirectory));

			if($plugin != null)
			{
				return (int)$plugin;
			}
		}

		$countRemoveDirectory	=	$this->count($remoteDirectory, null, false);

		if($countRemoveDirectory === 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Lädt einen Dateibaum auf den FTP Server und
	 * legt die selbe Ordnerstruktur an.
	 *
	 * @param string $local_file Der Pfad zum Server Verzeichnis, das identisch auf dem FTP-Server geladen werden soll
	 * @return bool Wenn es erfolgreich hochgeladen wurde gibt es ein true zurück. Bei einem Fehler false.
	 * @throws \Exception
	 */
	public function put_from_path($local_file)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'putFromPath', array($this->_ftp, $local_file));
			$plugin	=	plugins::hookCall('before', 'ftp', 'putFromPath', array($this->_ftp, $local_file));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

        $remote_file	=	basename($local_file);
        $handle      	=	fopen($local_file, 'r');

        if(@ftp_fput($this->_ftp, $remote_file, $handle, FTP_BINARY) === true)
		{
            @rewind($handle);
            return true;
        }

        return false;
    }


	/**
	 * Lädt einen Ordner zum Ziel FTP Ordner hoch
	 *
	 * @param string $source_directory Der Server Ordner der auf den FTP-Server hochgeladen werden soll.
	 * @param string $target_directory Das Ziel Verzeichnis auf dem FTP-Server.
	 * @param int $mode Der Modus in dem das Verzeichnis hochgeladen werden soll. Standartmäßig FTP_BINARY(2)
	 * @return bool Gibt nach erfolgreichem hochladen ein true zurück oder ein false bei einem Fehler.
	 * @throws \Exception
	 */
	public function put_all($source_directory, $target_directory, $mode = FTP_BINARY)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'putAll', array($this->_ftp, $source_directory, $target_directory, $mode));
			$plugin	=	plugins::hookCall('before', 'ftp', 'putAll', array($this->_ftp, $source_directory, $target_directory, $mode));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

        $d	=	dir($source_directory);

        while($file	= $d->read())
		{
            if($file != "." && $file != "..")
			{
                if(is_dir($source_directory.'/'.$file) === true)
				{
                    if(@ftp_chdir($this->_ftp, $target_directory.'/'.$file) === false)
					{
                        $ftpMkdir	=	@ftp_mkdir($this->_ftp, $target_directory.'/'.$file);

						if($ftpMkdir === false)
						{
							return false;
						}
                    }

                    $this->put_all($source_directory.'/'.$file, $target_directory.'/'.$file, $mode);
                }
				else
				{
                   	$ftpPut	=	 @ftp_put($this->_ftp, $target_directory.'/'.$file, $source_directory.'/'.$file, $mode);

					if($ftpPut === false)
					{
						return false;
					}
                }
            }
        }

        return true;
    }


	/**
	 * Lädt eine Datei auf den FTP Server hoch
	 *
	 * @param string $remote_file Der interne FTP-Pfad wo die Datei hochgeladen werden soll
	 * @param string $content Der Inhalt der Datei.
	 * @return bool Gibt nach erfolgreichem hochladen ein true und bei einem Fehler false zurück.
	 * @throws \Exception
	 */
	public function put_from_string($remote_file, $content)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'putFromString', array($this->_ftp, $remote_file, $content));
			$plugin	=	plugins::hookCall('before', 'ftp', 'putFromString', array($this->_ftp, $remote_file, $content));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

        $handle = fopen('php://temp', 'w');

        @fwrite($handle, $content);

		rewind($handle);

		return @ftp_fput($this->_ftp, $remote_file, $handle, FTP_BINARY);
    }


	/**
	 * Gibt die Ordner Größe des FTP Verzeichnises zurück
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum kontrollierenden Ordner
	 * @param bool $recursive Ob auch alle Unterverzeichnis größen mit einbezogen werden sollen oder nicht. Standartmäßig true.
	 * @return int Gibt die Byte Größe des FTP Verzeichnises zurück
	 * @throws \Exception
	 */
	public function dir_size($remoteDirectory = '.', $recursive = true)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'dirSize', array($this->_ftp, $remoteDirectory, $recursive));
			$plugin	=	plugins::hookCall('before', 'ftp', 'dirSize', array($this->_ftp, $remoteDirectory, $recursive));

			if($plugin != null)
			{
				return (int)$plugin;
			}
		}

        $items	=	$this->scan_dir($remoteDirectory, $recursive);
        $size 	= 	0;

        foreach($items as $item)
		{
			if(empty($item['size']) === false)
			{
            	$size	+=	(int)$item['size'];
			}
        }

        return $size;
    }


	/**
	 * Gibt alle Dateien in einem Verzeichnis zurück
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum kontrollierenden Ordner
	 * @param bool $recursive Ob alle Unterverzeichnise mit einbezogen werden sollen.
	 * @param string $filter Das Ergebnis sortiert zurück geben. Standartmäßig sort (Alle PHP Sortierbefehle erlaubt)
	 *
	 * @return array Gibt ein sortiertes mehrdimensionales Array zurück
	 * @throws \Exception
	 */
	public function nlist($remoteDirectory = '.', $recursive = false, $filter = 'sort')
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'nlist', array($this->_ftp, $remoteDirectory, $recursive, $filter));
			$plugin	=	plugins::hookCall('before', 'ftp', 'nlist', array($this->_ftp, $remoteDirectory, $recursive, $filter));

			if($plugin != null)
			{
				return $plugin;
			}
		}

		if($this->is_dir($remoteDirectory) === false)
		{
			throw new \Exception('Remote path is not directory');
		}

		$files	=	@ftp_nlist($this->_ftp, $remoteDirectory);

		if($files === false)
		{
			throw new \Exception('Unable to list directory');
		}

		$result		=	array();
		$dir_len	=	strlen($remoteDirectory);

		if(($kdot = array_search('.', $files)) !== false)
		{
			unset($files[$kdot]);
		}

		if(($kdot = array_search('..', $files)) !== false)
		{
            unset($files[$kdot]);
        }

		if($recursive === false)
		{
            foreach($files as $file)
			{
                $result[] = $remoteDirectory.'/'.$file;
            }

            $filter($result);
            return $result;
        }

		$flatten = function (array $arr) use (&$flatten) {
            $flat = array();
            foreach($arr as $k => $v)
			{
                if(is_array($v))
				{
                    $flat = array_merge($flat, $flatten($v));
                }
				else
				{
                    $flat[] = $v;
                }
            }
            return $flat;
        };

		foreach($files as $file)
		{
            $file = $remoteDirectory.'/'.$file;

            if(strpos($file, $remoteDirectory, $dir_len) === 0)
			{
                $file = substr($file, $dir_len);
            }

            if($this->is_dir($file) === true)
			{
                $result[] = $file;
                $items    = $flatten($this->nlist($file, true, $filter));

                foreach($items as $item)
				{
                    $result[] = $item;
                }
            }
			else
			{
                $result[] = $file;
            }
        }

		$result = array_unique($result);

        $filter($result);

		return $result;
	}


	/**
	 * Erstellt auf dem FTP Server ein Verzeichnis (Gegebenfalls auch rekursiv)
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Verzeichnis
	 * @param bool $recursive Ob der Ordner auch "Eltern" Ordner anlegen sollen wenn diese noch nicht existieren. Standartmäßig false.
	 * @return bool Gibt ein true zurück wenn alles erfolgreich angelegt wurde oder ein false wenn ein Fehler auftrat.
	 * @throws \Exception
	 */
	public function mkdir($remoteDirectory, $recursive = false)
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'mkdir', array($this->_ftp, $remoteDirectory, $recursive));
			$plugin	=	plugins::hookCall('before', 'ftp', 'mkdir', array($this->_ftp, $remoteDirectory, $recursive));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		if($recursive === false || $this->is_dir($remoteDirectory) === true)
		{
			$ftpMkdir	=	@ftp_mkdir($this->_ftp, $remoteDirectory);

			if($ftpMkdir === false)
			{
				return false;
			}
			else
			{
				return true;
			}
        }

        $result	=	false;
        $pwd    = 	ftp_pwd($this->_ftp);
        $parts  = 	explode('/', $remoteDirectory);

        foreach($parts as $part)
		{
            if(@ftp_chdir($this->_ftp, $part) === false)
			{
				$ftpMkdir	=	@ftp_mkdir($this->_ftp, $part);

				if($ftpMkdir === false)
				{
					return false;
				}
				else
				{
					$result	=	true;
				}

                @ftp_chdir($this->_ftp, $part);
            }
        }

        @ftp_chdir($this->_ftp, $pwd);

        return $result;
	}


	/**
	 * Löscht eine Datei auf dem FTP Server
	 *
	 * @param string $remoteFile Der interne FTP-Pfad zur Datei
	 * @return bool Gibt ein true zurück wenn erfolgreich gelöscht und ein false wenn ein Fehler auftrat.
	 * @throws \Exception
	 */
	public function remove($remoteFile)
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'remove', array($this->_ftp, $remoteFile));
			$plugin	=	plugins::hookCall('before', 'ftp', 'remove', array($this->_ftp, $remoteFile));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		if($this->is_dir($remoteFile) === true)
		{
			return false;
		}

		return @ftp_delete($this->_ftp, $remoteFile);
	}


	/**
	 * Löscht ein Verzeichnis auf dem FTP Server (auch rekursiv)
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Verzeichnis
	 * @param bool $recursive Ob Unterverzeichnise auch gelöscht werden sollen oder nicht. Standartmäßig false.
	 *
	 * @return bool Gibt bei erfolgreicher löschung true ansonsten false zurück.
	 * @throws \Exception
	 */
	public function rmdir($remoteDirectory, $recursive = false)
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'rmdir', array($this->_ftp, $remoteDirectory, $recursive));
			$plugin	=	plugins::hookCall('before', 'ftp', 'rmdir', array($this->_ftp, $remoteDirectory, $recursive));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		if($recursive === true)
		{
			$files	=	$this->nlist($remoteDirectory, false, 'rsort');

			foreach($files as $file)
			{
				if($this->is_dir($file) === false)
				{
					$remove	=	$this->remove($file);

					if($remove === false)
					{
						return false;
					}
				}
				else
				{
					$rmdir	=	$this->rmdir($file, true);

					if($rmdir === false)
					{
						return false;
					}

					$rmdir	=	@ftp_rmdir($this->_ftp, $file);

					if($rmdir === false)
					{
						return false;
					}
				}
			}
		}

		return @ftp_rmdir($this->_ftp, $remoteDirectory);
	}


	/**
	 * Löscht alle Dateien in einem FTP Ordner
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Verzeichnis
	 * @return bool Bei erfolgreichen Reinigung gibt er true ansonsten false zurück.
	 * @throws \Exception
	 */
	public function clean_dir($remoteDirectory)
	{
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'cleanDir', array($this->_ftp, $remoteDirectory));
			$plugin	=	plugins::hookCall('before', 'ftp', 'cleanDir', array($this->_ftp, $remoteDirectory));

			if($plugin != null)
			{
				return (bool)$plugin;
			}
		}

		if(!$files = $this->nlist($remoteDirectory))
		{
            return $this->is_empty($remoteDirectory);
        }

        foreach($files as $file)
		{
            $remove	=	$this->remove($file);

			if($remove === false)
			{
				return false;
			}
        }

        return $this->is_empty($remoteDirectory);
	}


	/**
	 * Scannt die Dateilisten auf den FTP Server
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Verzeichnis. Standartmäßig "."
	 * @param bool $recursive Scannt auch alle UNterzeichnise mit. Standartmßig false
	 * @return array Gibt ein mehrdimensionales Array mit allen gefundenen Dateien zurück
	 * @throws \Exception
	 */
	public function scan_dir($remoteDirectory = '.', $recursive = false)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'scanDir', array($this->_ftp, $remoteDirectory, $recursive));
			$plugin	=	plugins::hookCall('before', 'ftp', 'scanDir', array($this->_ftp, $remoteDirectory, $recursive));

			if($plugin != null)
			{
				return $plugin;
			}
		}

        return $this->parse_raw_list($this->rawlist($remoteDirectory, $recursive));
    }


	/**
	 * Gibt die RAW Liste zurück
	 *
	 * @param string $remoteDirectory Der interne FTP-Pfad zum Verzeichnis. Standartmäßig '.'
	 * @param bool $recursive Auch Unterverzeichnise einbeziehen oder nicht. Standartmäßig false
	 * @return array Gibt eine Liste aller Dateien zurück
	 * @throws \Exception
	 */
	public function rawlist($remoteDirectory = '.', $recursive = false)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'rawlist', array($this->_ftp, $remoteDirectory, $recursive));
			$plugin	=	plugins::hookCall('before', 'ftp', 'rawlist', array($this->_ftp, $remoteDirectory, $recursive));

			if($plugin != null)
			{
				return $plugin;
			}
		}

        if($this->is_dir($remoteDirectory) === false)
		{
            throw new \Exception('"'.$remoteDirectory.'" is not a directory.');
        }

        $list  	=	ftp_rawlist($this->_ftp, $remoteDirectory);
        $items	=	array();

        if($recursive === false)
		{
            foreach($list as $path => $item)
			{
                $chunks = preg_split("/\s+/", $item);

                if(empty($chunks[8]) || $chunks[8] == '.' || $chunks[8] == '..')
				{
                    continue;
                }

                $path	=	$remoteDirectory.'/'.$chunks[8];

                if(isset($chunks[9]))
				{
                    $nbChunks	=	count($chunks);

                    for($i = 8; ++$i <= $nbChunks;)
					{
                        $path .= ' '.$chunks[$i];
                    }
                }

                if(substr($path, 0, 2) == './')
				{
                    $path	=	substr($path, 2);
                }

                $items[$this->raw_to_type($item).'#'.$path] = $item;
            }

            return $items;
        }

        foreach($list as $item)
		{
            $len	=	strlen($item);

            if(!$len || ($item[$len-1] == '.' && $item[$len-2] == ' ' || $item[$len-1] == '.' && $item[$len-2] == '.' && $item[$len-3] == ' '))
			{
                continue;
            }

            $chunks	=	preg_split("/\s+/", $item);

            if(empty($chunks[8]) || $chunks[8] == '.' || $chunks[8] == '..')
			{
                continue;
            }

            $path	=	$remoteDirectory.'/'.$chunks[8];

            if(isset($chunks[9]))
			{
                $nbChunks	=	count($chunks);

				for($i = 8; ++$i <= $nbChunks;)
				{
                    $path .= ' '.$chunks[$i];
                }
            }

            if(substr($path, 0, 2) == './')
			{
                $path	=	substr($path, 2);
            }

            $items[$this->raw_to_type($item).'#'.$path] = $item;

            if($item[0] == 'd')
			{
                $sublist	=	$this->rawlist($path, true);

                foreach($sublist as $subpath => $subitem)
				{
                    $items[$subpath] = $subitem;
                }
            }
        }

        return $items;
    }


	/**
	 * Gibt die RAW Liste zurück
	 *
	 * @param array $rawlist Ein Array erstellt aus der Methode "rawlist"
	 * @return array Gibt Details zu den einzelnen FTP-Dateien zurück
	 * @throws \Exception
	 */
	public function parse_raw_list(array $rawlist)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'parseRawList', array($this->_ftp, $rawlist));
			$plugin	=	plugins::hookCall('before', 'ftp', 'parseRawList', array($this->_ftp, $rawlist));

			if($plugin != null)
			{
				return $plugin;
			}
		}

        $items	=	array();
        $path  	= 	'';

        foreach($rawlist as $key => $child)
		{
            $chunks	=	preg_split("/\s+/", $child);

            if(empty($chunks[8]) === false && ($chunks[8] == '.' || $chunks[8] == '..'))
			{
                continue;
            }

            if(count($chunks) === 1)
			{
                $len	=	strlen($chunks[0]);

                if($len && $chunks[0][$len-1] == ':')
				{
                    $path	=	substr($chunks[0], 0, -1);
                }

                continue;
            }

            $item = array(
                'permissions' => $chunks[0],
                'number'      => $chunks[1],
                'owner'       => $chunks[2],
                'group'       => $chunks[3],
                'size'        => $chunks[4],
                'month'       => $chunks[5],
                'day'         => $chunks[6],
                'time'        => $chunks[7],
                'name'        => $chunks[8],
                'type'        => $this->raw_to_type($chunks[0]),
			);

            if($item['type'] == 'link')
			{
                $item['target'] = $chunks[10];
            }

            if(is_int($key) || strpos($key, $item['name']) === false)
			{
                array_splice($chunks, 0, 8);

                $key	=	$item['type'].'#'.($path ? $path.'/' : '').implode(" ", $chunks);

                if($item['type'] == 'link')
				{
                    $exp	=	explode(' ->', $key);
                    $key	=	rtrim($exp[0]);
                }

                $items[$key]	=	$item;
            }
			else
			{
                $items[$key]	= 	$item;
            }
        }

        return $items;
    }


	/**
	 * Gibt den Typ einer Datei zurück
	 *
	 * @param string $permission Übergebene Typ.
	 * @return string Der umgewandelete, verständlichere, Typ kommt zurück.
	 * @throws \Exception
	 */
	public function raw_to_type($permission)
    {
		if($this->_ftp === false)
		{
			throw new \Exception('No connection');
		}

		if(class_exists('\package\plugins') === true)
		{
			plugins::hookShow('before', 'ftp', 'rawToType', array($this->_ftp, $permission));
			$plugin	=	plugins::hookCall('before', 'ftp', 'rawToType', array($this->_ftp, $permission));

			if($plugin != null)
			{
				return (string)$plugin;
			}
		}

        if(is_string($permission) === false)
		{
            throw new \Exception('The "$permission" argument must be a string, "'.gettype($permission).'" given.');
        }

        if(empty($permission[0]))
		{
            return 'unknown';
        }

		if($permission[0] == '-')
		{
			return 'file';
		}
		elseif($permission[0] == 'd')
		{
			return 'directory';
		}
		elseif($permission[0] == 'l')
		{
			return 'link';
		}
		else
		{
			return 'unknow';
		}
    }
}