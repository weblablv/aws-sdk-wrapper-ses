<?php

namespace Weblab\AmazonSdkSesWrapper\ValueObject\Exception;

use InvalidArgumentException;

final class AttachmentFilepathNotValidException extends InvalidArgumentException
{
	/**
	 * @param string $filepath
	 */
	public function __construct(string $filepath)
	{
		parent::__construct(sprintf('Attachment file path "%s" is not valid (not exists or is not readable)', $filepath));
	}
}
