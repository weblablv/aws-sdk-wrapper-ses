<?php

namespace Weblab\AmazonSdkSesWrapper\ValueObject;

use Weblab\AmazonSdkSesWrapper\Support\FileSystemSupport;
use Weblab\AmazonSdkSesWrapper\ValueObject\Exception\AttachmentFilepathNotValidException;

use Guzzle\Http\Mimetypes;

final class Attachment
{
	/**
	 * @var string $filepath
	 */
	private $filepath;
	/**
	 * @var string $title
	 */
	private $title;
	/**
	 * @var string $ctype
	 */
	private $ctype;
	
	/**
	 * @param string $filepath
	 * @param string|null $title
	 * @param string|null $ctype
	 */
	private function __construct(string $filepath, string $title = null, string $ctype = null)
	{
		if (false === FileSystemSupport::readable($filepath)) {
			throw new AttachmentFilepathNotValidException($filepath);
		}
		
		$this->filepath = $filepath;
		$this->title = null !== $title ? $title : basename($filepath);
		$this->ctype = null !== $ctype ? $ctype : Mimetypes::getInstance()->fromFilename($filepath);
	}
	
	public static function file(string $filepath, string $title = null, string $ctype = null): Attachment
	{
		return new self($filepath, $title, $ctype);
	}
	
	/**
	 * @return string
	 */
	public function getFilepath(): string
	{
		return $this->filepath;
	}
	
	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}
	
	/**
	 * @return string
	 */
	public function getCtype(): string
	{
		return $this->ctype;
	}
}
