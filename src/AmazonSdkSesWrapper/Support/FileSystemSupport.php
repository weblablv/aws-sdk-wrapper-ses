<?php

namespace WebLabLv\AmazonSdkSesWrapper\Support;

final class FileSystemSupport
{
    /**
     * @param string $filename
     * @return bool
     */
    public static function isReadable(string $filename): bool
    {
        return true === file_exists($filename) && true === is_readable($filename);
    }
}
