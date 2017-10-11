<?php

use WebLabLv\AmazonSdkSesWrapper\Data\SesClientData;
use WebLabLv\AmazonSdkSesWrapper\Service\SesClientSender;
use WebLabLv\AmazonSdkSesWrapper\ValueObject\Attachment;

require_once 'vendor/autoload.php';

$sender        = null;
$subject       = 'Ses Client sender example email #Русский текст';
$recipients    = [
    'username@domain'     => 'Someone username',
    'username@domain.com' => null
];
$htmlText      = '<p>Hello <b>amazon ses client</b> example email!</p>';
$text          = 'hello amazon ses client example email!';
$attachments   = [
    Attachment::file(__DIR__ . '/.gitignore', 'filenameGitignore'),
    Attachment::file(__DIR__ . '/example.php'),
];

/**
 * Ses Client data
 */
$sesClientData = SesClientData::create($sender);

false === empty($htmlText) && $sesClientData->setHtmlText($htmlText);
false === empty($text)     && $sesClientData->setText($text);
false === empty($subject)  && $sesClientData->setSubject($subject);

foreach($recipients as $recipient => $title) {
    $sesClientData->addRecipient($recipient, $title);
}
foreach($attachments as $attachment) {
    $sesClientData->addAttachment($attachment);
}

/**
 * Ses Client sender
 */
$profile         = null;
$credentialsPath = null;
$version         = null;
$region          = null;

$sesClientSender = SesClientSender::create();

false === empty($credentialsPath) && $sesClientSender->setCredentialsPath($credentialsPath);
false === empty($profile)         && $sesClientSender->setProfile($profile);
false === empty($version)         && $sesClientSender->setVersion($version);
false === empty($region)          && $sesClientSender->setRegion($region);

$sesClientSender->send($sesClientData);
print_r($sesClientSender->getSesClientResult());
