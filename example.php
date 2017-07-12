<?php

require_once 'vendor/autoload.php';

$sender = Weblab\AmazonSdkSesWrapper\ValueObject\Email::create('****@***.**');
$recipients = [
	Weblab\AmazonSdkSesWrapper\ValueObject\Email::create('****@***.**'),
	Weblab\AmazonSdkSesWrapper\ValueObject\Email::create('****@***.**')
];
$attachments = [
	Weblab\AmazonSdkSesWrapper\ValueObject\Attachment::file(__DIR__ . '/../path/to/attachment'),
	Weblab\AmazonSdkSesWrapper\ValueObject\Attachment::file(__DIR__ . '/../path/to/attachment', 'attachment-title.png'),
];


$data = Weblab\AmazonSdkSesWrapper\DataTransfer\SesEmailDataTransfer::create($sender)->setSubject('Ses Client Hello!')->setHtmlText('<b>Ses Client email example</b> <i>html text</i><br /><h3>Weblab.lv</h3>');
foreach($recipients as $recipient) {
	$data->addRecipient($recipient);
}
foreach($attachments as $attachment) {
	$data->addAttachment($attachment);
}


$sender = new Weblab\AmazonSdkSesWrapper\Service\SesClientEmailSender('***', '***', '***', 'us-east-1');
print_r($sender->send($data));
