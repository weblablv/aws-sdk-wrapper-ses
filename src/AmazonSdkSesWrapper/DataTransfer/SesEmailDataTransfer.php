<?php

namespace Weblab\AmazonSdkSesWrapper\DataTransfer;

use Weblab\AmazonSdkSesWrapper\ValueObject\Attachment;
use Weblab\AmazonSdkSesWrapper\ValueObject\Email;

final class SesEmailDataTransfer
{
	/**
	 * @var Email $sender
	 */
	private $sender;
	/**
	 * @var array|Email[] $recipient
	 */
	private $recipients = [];
	/**
	 * @var string|null $subject
	 */
	private $subject;
	/**
	 * @var string|null $text
	 */
	private $text;
	/**
	 * @var string|null $htmlText
	 */
	private $htmlText;
	/**
	 * @var array|Attachment[]
	 */
	private $attachments = [];
	
	/**
	 * @param Email $sender
	 */
	public function __construct(Email $sender)
	{
		$this->sender = $sender;
	}
	
	/**
	 * @param Email $sender
	 * @return SesEmailDataTransfer
	 */
	public static function create(Email $sender): SesEmailDataTransfer
	{
		return new self($sender);
	}
	
	/**
	 * @return Email
	 */
	public function getSender(): Email
	{
		return $this->sender;
	}
	
	/**
	 * @return array|Email[]
	 */
	public function getRecipients(): array
	{
		return $this->recipients;
	}
	
	/**
	 * @param Email $recipient
	 * @return SesEmailDataTransfer
	 */
	public function addRecipient(Email $recipient): SesEmailDataTransfer
	{
		array_push($this->recipients, $recipient);
		return $this;
	}
	
	/**
	 * @return null|string
	 */
	public function getSubject()
	{
		return $this->subject;
	}
	
	/**
	 * @param string $subject
	 * @return SesEmailDataTransfer
	 */
	public function setSubject(string $subject): SesEmailDataTransfer
	{
		$this->subject = $subject;
		return $this;
	}
	
	/**
	 * @return null|string
	 */
	public function getText()
	{
		return $this->text;
	}
	
	/**
	 * @param string $text
	 * @return SesEmailDataTransfer
	 */
	public function setText(string $text): SesEmailDataTransfer
	{
		$this->text = strip_tags($text);
		return $this;
	}
	
	/**
	 * @return null|string
	 */
	public function getHtmlText()
	{
		return $this->htmlText;
	}
	
	/**
	 * @param string $htmlText
	 * @return SesEmailDataTransfer
	 */
	public function setHtmlText(string $htmlText): SesEmailDataTransfer
	{
		$this->htmlText = $htmlText;
		return $this;
	}
	
	/**
	 * @return array|Attachment[]
	 */
	public function getAttachments(): array
	{
		return $this->attachments;
	}
	
	/**
	 * @param Attachment $attachment
	 * @return SesEmailDataTransfer
	 */
	public function addAttachment(Attachment $attachment): SesEmailDataTransfer
	{
		array_push($this->attachments, $attachment);
		return $this;
	}
}
