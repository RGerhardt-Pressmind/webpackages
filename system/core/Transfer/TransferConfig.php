<?php

namespace system\core\Transfer;

class TransferConfig
{
	public string $engine;
	public array $connectionData;

	public static mixed $_self;

	/**
	 * Create transfer config
	 *
	 * @param string $engine
	 * @param array $connectionData
	 * @return TransferConfig
	 */
	public static function create(string $engine, array $connectionData): TransferConfig
	{
		if(is_null(self::$_self))
		{
			self::$_self					=	new self();
			self::$_self->engine			=	$engine;
			self::$_self->connectionData	=	$connectionData;
		}

		return self::$_self;
	}
}
