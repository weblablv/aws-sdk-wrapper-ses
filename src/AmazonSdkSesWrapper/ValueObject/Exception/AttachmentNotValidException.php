<?php

namespace WebLabLv\AmazonSdkSesWrapper\ValueObject\Exception;

use InvalidArgumentException;

final class AttachmentNotValidException extends InvalidArgumentException
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * @param string $path
     * @return AttachmentNotValidException
     */
    public static function path(string $path): AttachmentNotValidException
    {
        return new self(
            sprintf('Attachment file path "%s" is not valid ( file not exists or is not readable )', $path)
        );
    }
}
