<?php

namespace WebLabLv\AmazonSdkSesWrapper\Support;

final class FileSystemSupport
{
	/**
	 * @param string $filepath
	 * @return bool
	 */
	public static function readable(string $filepath): bool
	{
		return true === file_exists($filepath) && true === is_readable($filepath);
	}
}
