<?php

require_once 'vendor/autoload.php';

$sender = WebLabLv\AmazonSdkSesWrapper\ValueObject\Email::create('****@***.**');
$recipients = [
	WebLabLv\AmazonSdkSesWrapper\ValueObject\Email::create('****@***.**'),
	WebLabLv\AmazonSdkSesWrapper\ValueObject\Email::create('****@***.**')
];
$attachments = [
	WebLabLv\AmazonSdkSesWrapper\ValueObject\Attachment::file(__DIR__ . '/../path/to/attachment'),
	WebLabLv\AmazonSdkSesWrapper\ValueObject\Attachment::file(__DIR__ . '/../path/to/attachment', 'attachment-title.png'),
];


$data = WebLabLv\AmazonSdkSesWrapper\DataTransfer\SesEmailDataTransfer::create($sender)->setSubject('Ses Client Hello!')->setHtmlText('<b>Ses Client email example</b> <i>html text</i><br /><h3>Weblab.lv</h3>');
foreach($recipients as $recipient) {
	$data->addRecipient($recipient);
}
foreach($attachments as $attachment) {
	$data->addAttachment($attachment);
}


$sender = new WebLabLv\AmazonSdkSesWrapper\Service\SesClientEmailSender('***', '***', '***', 'us-east-1');
print_r($sender->send($data));
