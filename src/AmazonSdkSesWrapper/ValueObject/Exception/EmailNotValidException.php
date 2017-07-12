<?php

namespace Weblab\AmazonSdkSesWrapper\ValueObject\Exception;

use InvalidArgumentException;

final class EmailNotValidException extends InvalidArgumentException
{
	/**
	 * @param string $email
	 */
	public function __construct(string $email)
	{
		parent::__construct(sprintf('"%s" is not a valid email address', $email));
	}
}
