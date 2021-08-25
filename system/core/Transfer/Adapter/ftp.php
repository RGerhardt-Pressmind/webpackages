<?php

namespace system\core\Transfer\Adapter;

class ftp implements AdapterInterface
{
	/**
	 * @var array
	 */
	private array $errors	=	[];

	private mixed $ftp;

	/**
	 * @param array $config
	 * @return bool
	 */
	public function connection(array $config): bool
	{
		$host		=	($config['host'] ?? false);
		$ssl		=	($config['ssl'] ?? false);
		$port		=	(int)($config['port'] ?? 21);
		$timeout	=	(int)($config['timeout'] ?? 90);
		$username	=	($config['username'] ?? '');
		$password	=	($config['password'] ?? '');
		$passiv		=	($config['passiv'] ?? false);

		if(empty($host) || empty($username))
		{
			return false;
		}

		if($ssl)
		{
			$this->ftp	=	@ftp_ssl_connect($host, $port, $timeout);
		}
		else
		{
			$this->ftp	=	@ftp_connect($host, $port, $timeout);
		}

		if(!$this->ftp)
		{
			$this->errors[]	=	'Connect not successfully';
			return false;
		}

		$login	=	@ftp_login($this->ftp, $username, $password);

		if(!$login)
		{
			$this->errors[]	=	'Login not successfully';
			return false;
		}

		if(!empty($passiv))
		{
			if(!@ftp_pasv($this->ftp, true))
			{
				$this->errors[]	=	'Passiv mode not work';
				return false;
			}
		}

		return true;
	}

	/**
	 * Get file
	 *
	 * @param string $remotePath
	 * @param string $localePath
	 * @return bool
	 */
	public function getFile(string $remotePath, string $localePath): bool
	{
		return @ftp_get($this->ftp, $localePath, $remotePath);
	}

	/**
	 * @param array $remoteFiles
	 * @return bool
	 */
	public function getFiles(array $remoteFiles): bool
	{
		foreach($remoteFiles as $remoteFile => $localeFile)
		{
			if(!@ftp_get($this->ftp, $localeFile, $remoteFile))
			{
				$this->errors[]	=	'Remote file "'.$remoteFile.'" not successfully downloaded';
			}
		}

		return empty($this->errors);
	}

	/**
	 * @param string $localePath
	 * @param string $remotePath
	 * @return bool
	 */
	public function putFile(string $localePath, string $remotePath): bool
	{
		return @ftp_put($this->ftp, $remotePath, $localePath);
	}

	/**
	 * @param array $files ['localePath' => 'remotePath']
	 * @return bool
	 */
	public function putFiles(array $files): bool
	{
		foreach($files as $localePath => $remotePath)
		{
			if(!@ftp_put($this->ftp, $remotePath, $localePath))
			{
				$this->errors[]	=	'Put file "'.$localePath.'" not uploaded';
			}
		}

		return empty($this->errors);
	}

	/**
	 * @param string $content
	 * @param string $remotePath
	 * @return bool
	 */
	public function putContent(string $content, string $remotePath): bool
	{
		$temp	=	tmpfile();
		fwrite($temp, $content);
		fseek($temp, 0);

		if(!@ftp_put($this->ftp, $remotePath, $temp))
		{
			fclose($temp);
			$this->errors[]	=	'Error upload content';

			return false;
		}

		fclose($temp);

		return true;
	}

	/**
	 * @param string $removePath
	 * @return bool
	 */
	public function removeFile(string $removePath): bool
	{
		return @ftp_delete($this->ftp, $removePath);
	}

	/**
	 * @param array $files
	 * @return bool
	 */
	public function removeFiles(array $files): bool
	{
		foreach($files as $file)
		{
			if(@ftp_delete($this->ftp, $file))
			{
				$this->errors[]	=	'Error remove remote file "'.$file.'"';
			}
		}

		return empty($this->errors);
	}

	/**
	 * @return array
	 */
	public function getErrorLogs(): array
	{
		return $this->errors;
	}

	/**
	 * Get ftp ressource
	 *
	 * @return mixed
	 */
	public function getRessource(): mixed
	{
		return $this->ftp;
	}
}
