<?php

namespace system\core\Transfer;

use system\core\Transfer\Adapter\AdapterInterface;

class transfer
{
	/**
	 * Create transfer
	 *
	 * @param TransferConfig $config
	 * @return AdapterInterface
	 */
	public static function create(TransferConfig $config): AdapterInterface
	{
		$class	=	$config->engine;

		if(class_exists($class))
		{
			/**
			 * @var AdapterInterface $transfer
			 */
			$transfer	=	new $class();
		}
		else
		{
			echo 'Failed, transfer engine "'.$class.'" not exist';
			exit;
		}

		$transfer->connection($config->connectionData);

		return $transfer;
	}
}
