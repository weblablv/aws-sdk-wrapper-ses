<?php

namespace WebLabLv\AmazonSdkSesWrapper\Service;

use Aws\Ses\SesClient;
use Aws\Credentials\CredentialProvider;
use Aws\Result;

use WebLabLv\AmazonSdkSesWrapper\Data\SesClientData;

use Mail_mime;

/**
 * Credentials: http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/credentials.html#using-the-aws-credentials-file-and-credential-profiles
 */

class SesClientSender
{
    /**
     * @var SesClient $sesClient
     */
    private $sesClient;
    /**
     * @var string $profile
     */
    private $profile = 'default';
    /**
     * @var array $credentials
     */
    private $credentials = [];
    /**
     * @var string $version
     */
    private $version = '2010-12-01'; // latest at 2017-10-11
    /**
     * @var string $region
     */
    private $region  = 'us-east-1';
    /**
     * @var Result|null $result
     */
    private $result;

    /**
     * @param string $key
     * @param string $secret
     */
    public function __construct(string $key, string $secret)
    {
        $this->credentials = [
            'key'    => $key,
            'secret' => $secret
        ];
    }

    /**
     * @param string $profile
     * @return SesClientSender
     */
    public function setProfile(string $profile): SesClientSender
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @param string $version
     * @return SesClientSender
     */
    public function setVersion(string $version): SesClientSender
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @param string $region
     * @return SesClientSender
     */
    public function setRegion(string $region): SesClientSender
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return void
     */
    public function initSesClient()
    {
        $this->sesClient = new SesClient($this->getConfiguration());
    }

    /**
     * @param SesClientData $sesClientData
     * @return Result
     */
    public function send(SesClientData $sesClientData): Result
    {
        $this->lastSesClientResultRemove()->doSend($sesClientData);
        return $this->getSesClientResult();
    }

    /**
     * @return Result|null
     */
    public function getSesClientResult()
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    private function initSesClientRequired(): bool
    {
        return false === $this->sesClient instanceof SesClient;
    }

    /**
     * @return array
     */
    private function getConfiguration(): array
    {
        return [
            'version'     => $this->version,
            'region'      => $this->region,
            'credentials' => $this->credentials
        ];
    }

    /**
     * @return SesClientSender
     */
    private function lastSesClientResultRemove(): SesClientSender
    {
        $this->result = null;
        return $this;
    }

    /**
     * @param SesClientData $sesClientData
     */
    private function doSend(SesClientData $sesClientData)
    {
        $mailMime = new Mail_mime([
            'text_encoding' => '7bit',
            'text_charset'  => 'utf-8',
            'html_charset'  => 'utf-8',
            'head_charset'  => 'utf-8',
            'eol'           => "\r\n"
        ]);

        false === empty($sesClientData->getText())     && $mailMime->setTXTBody($sesClientData->getText());
        false === empty($sesClientData->getHtmlText()) && $mailMime->setHTMLBody($sesClientData->getHtmlText());

        foreach($sesClientData->getAttachments() as $attachment) {
            $mailMime->addAttachment(
                $attachment->getFilename(), $attachment->getCtype(), $attachment->getTitle()
            );
        }

        $mailHeaders       = [];
        $mailHeaders['To'] = $sesClientData->getRecipientString(); // todo:: move to sendRawEmail method

        false === empty($sesClientData->getSubject())       && $mailHeaders['Subject'] = $sesClientData->getSubject();
        false === empty($sesClientData->getCustomHeaders()) && $mailHeaders = array_merge($mailHeaders, $sesClientData->getCustomHeaders());

        foreach($mailHeaders as $name => $value) {
            $mailHeaders[$name] = $mailMime->encodeHeader($name, $value, 'utf-8', 'quoted-printable');
        }

        true === $this->initSesClientRequired() && $this->initSesClient();
        $this->result = $this->sesClient->sendRawEmail([
            'Source'     => $sesClientData->getSender(),
            'RawMessage' => [
                'Data' => $mailMime->txtHeaders($mailHeaders) . "\r\n" . $mailMime->get()
            ]
        ]);
    }
}
