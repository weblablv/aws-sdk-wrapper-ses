<?php

namespace WebLabLv\AmazonSdkSesWrapper\Data;

use WebLabLv\AmazonSdkSesWrapper\ValueObject\Attachment;

final class SesClientData
{
    /**
     * @var string $sender
     */
    private $sender;
    /**
     * @var array $recipients
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
     * @var array $customHeaders
     */
    private $customHeaders = [];

    /**
     * @param string $sender
     */
    public function __construct(string $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param string $sender
     * @return SesClientData
     */
    public static function create(string $sender): SesClientData
    {
        return new self($sender);
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * @param string $email
     * @return SesClientData
     */
    public function setSender(string $email): SesClientData
    {
        $this->sender = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @return string
     */
    public function getRecipientString(): string
    {
        return implode(', ', array_map(function(array $recipient) {
            return true === empty($recipient['title']) ? $recipient['email'] : sprintf('"%s" <%s>', $recipient['title'], $recipient['email']);
        }, $this->recipients));
    }

    /**
     * @param string      $email
     * @param string|null $title
     *
     *
     * @return SesClientData
     */
    public function addRecipient(string $email, string $title = null): SesClientData
    {
        if (false === array_search($email, array_column($this->recipients, 'email'))) {
            array_push($this->recipients, [
                'email' => $email,
                'title' => $title
            ]);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return SesClientData
     */
    public function setSubject(string $subject): SesClientData
    {
        $this->subject = $subject;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return SesClientData
     */
    public function setText(string $text): SesClientData
    {
        $this->text = strip_tags($text);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHtmlText()
    {
        return $this->htmlText;
    }

    /**
     * @param string $htmlText
     * @return SesClientData
     */
    public function setHtmlText(string $htmlText): SesClientData
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
     * @return SesClientData
     */
    public function addAttachment(Attachment $attachment): SesClientData
    {
        array_push($this->attachments, $attachment);
        return $this;
    }

    /**
     * @return array
     */
    public function getCustomHeaders(): array
    {
        return $this->customHeaders;
    }

    /**
     * @param $name
     * @param string $value
     *
     * @return SesClientData
     */
    public function addCustomHeader(string $name, string $value): SesClientData
    {
        $this->customHeaders[$name] = $value;
        return $this;
    }

    /**
     * @param array $headers
     * @return SesClientData
     */
    public function setCustomHeaders(array $headers): SesClientData
    {
        $this->customHeaders = $headers; // todo:: array_merge instead of replace?
        return $this;
    }
}
