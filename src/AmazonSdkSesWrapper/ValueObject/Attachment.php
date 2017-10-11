<?php

namespace WebLabLv\AmazonSdkSesWrapper\ValueObject;

use function GuzzleHttp\Psr7\mimetype_from_filename;
use WebLabLv\AmazonSdkSesWrapper\Support\FileSystemSupport;
use WebLabLv\AmazonSdkSesWrapper\ValueObject\Exception\AttachmentNotValidException;

use function GuzzleHttp\Psr7\mimetype_from_extension;

final class Attachment
{
    /**
     * @var string $filename
     */
    private $filename;
    /**
     * @var string $title
     */
    private $title;
    /**
     * Ctype can be nullable if filename is something like .gitignore
     *
     * @var string|null $ctype
     */
    private $ctype;

    /**
     * @param string      $filename
     * @param string|null $title
     * @param string|null $ctype
     *
     * @throws AttachmentNotValidException if filename path is not readable or not exist
     */
    private function __construct(string $filename, string $title = null, string $ctype = null)
    {
        if (false === FileSystemSupport::isReadable($filename)) {
            throw AttachmentNotValidException::path($filename);
        }

        $this->filename = $filename;
        $this->title    = false === empty($title) ? $title : basename($filename);
        $this->ctype    = false === empty($ctype) ? $ctype : mimetype_from_filename($filename);
    }

    /**
     * @param string      $filename
     * @param string|null $title
     * @param string|null $ctype
     *
     * @return Attachment
     */
    public static function file(string $filename, string $title = null, string $ctype = null): Attachment
    {
        return new self($filename, $title, $ctype);
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Attachment
     */
    public function setTitle(string $title): Attachment
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCtype()
    {
        return $this->ctype;
    }

    /**
     * @param string $ctype
     * @return Attachment
     */
    public function setCtype(string $ctype): Attachment
    {
        $this->ctype = $ctype;
        return $this;
    }
}
