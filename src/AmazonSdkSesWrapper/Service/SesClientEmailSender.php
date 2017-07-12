<?php

namespace Weblab\AmazonSdkSesWrapper\Service;

use Aws\Ses\SesClient;
use Mail_mime;

use Weblab\AmazonSdkSesWrapper\DataTransfer\SesEmailDataTransfer;
use Weblab\AmazonSdkSesWrapper\ValueObject\Email;

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
	 * @param array $config
	 */
	public function __construct(string $profile, string $accessKeyId, string $accessSecretKey, string $region, array $config = [])
	{
		$config = array_merge($config, [
			'profile' => $profile,
			'credentials' => [
				'key' => $accessKeyId,
				'secret' => $accessSecretKey
			],
			'region' => $region
		]);
		
		$this->amazonSesClient = SesClient::factory($config);
	}
	
	/**
	 * @param SesEmailDataTransfer $data
	 * @return \Guzzle\Service\Resource\Model
	 */
	public function send(SesEmailDataTransfer $data)
	{
		$mail = new Mail_mime([ 'eol' => "\n" ]);

		// set email text part
		// todo:: if empty should be used html text?
		if (false === empty($data->getText())) {
			$mail->setTXTBody($data->getText());
		}
		// set email html text part
		if (false === empty($data->getHtmlText())) {
			$mail->setHTMLBody($data->getHtmlText());
		}
		// add attachments
		foreach($data->getAttachments() as $attachment) {
			$mail->addAttachment($attachment->getFilepath(), $attachment->getCtype(), $attachment->getTitle());
		}
		
		// prepare message and send email using ses client
		$message = $mail->txtHeaders([
			'from' => (string)$data->getSender(),
			'to' => implode(', ', array_map(function(Email $recipient) { return (string)$recipient; }, $data->getRecipients())),
			'subject' => $data->getSubject()
		]) . "\r\n" . $mail->get();

		// send email
		return $this->amazonSesClient->sendRawEmail([
			'RawMessage' => [
				'Data' => base64_encode($message)
			]
		]);
	}
}
