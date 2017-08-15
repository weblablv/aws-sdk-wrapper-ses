<?php

namespace WebLabLv\AmazonSdkSesWrapper\Service;

use Aws\Ses\SesClient;
use Mail_mime;

use WebLabLv\AmazonSdkSesWrapper\DataTransfer\SesEmailDataTransfer;
use WebLabLv\AmazonSdkSesWrapper\ValueObject\Email;

final class SesClientEmailSender
{
	/**
	 * @var SesClient $amazonSesClient
	 */
	private $amazonSesClient;

	/**
	 * @param string $profile
	 * @param string $accessKeyId
	 * @param string $accessSecretKey
	 * @param string $region
	 * @param array  $config
	 */
	public function __construct(string $profile, string $accessKeyId, string $accessSecretKey, string $region, array $config = [])
	{
		$config = array_merge([
			'profile'     => $profile,
            'region'      => $region,
			'credentials' => [
				'key'    => $accessKeyId,
				'secret' => $accessSecretKey
			]
		], $config);

		$this->amazonSesClient = SesClient::factory($config);
	}

	/**
	 * @param SesEmailDataTransfer $data
	 * @return \Guzzle\Service\Resource\Model
	 */
	public function send(SesEmailDataTransfer $data)
	{
        $mailMime = new Mail_mime([
            'text_encoding' => '7bit',
            'text_charset'  => 'utf-8',
            'html_charset'  => 'utf-8',
            'head_charset'  => 'utf-8',
            'eol'           => "\n"
        ]);

        false === empty($data->getText())     && $mailMime->setTXTBody($data->getText());
        false === empty($data->getHtmlText()) && $mailMime->setHTMLBody($data->getHtmlText());

        foreach($data->getAttachments() as $attachment) {
            $mailMime->addAttachment(
                $attachment->getFilepath(), $attachment->getCtype(), $attachment->getTitle()
            );
        }

        $headers = [
            'from'    => (string) $data->getSender(),
            'subject' => (string) $data->getSubject(),
            'to'      => (string) implode(',', array_map(function(Email $recipient) {
                return (string)$recipient;
            }, $data->getRecipients()))
        ];

        // prepare headers
        foreach($headers as $name => $value) {
            $headers[$name] = $mailMime->encodeHeader($name, $value, 'utf-8', 'quoted-printable');
        }

        return $this->amazonSesClient->sendRawEmail([
            'RawMessage' => [
                'Data' => base64_encode($mailMime->txtHeaders($headers) . "\r\n" . $mailMime->get())
            ]
        ]);
	}
}
