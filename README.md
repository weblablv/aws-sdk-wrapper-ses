# Usage

### Ses client data
Ses client data is a email message. With ability to set sender, html text, attachments, etc...
Sender and recipient(s) are required, all another arguments can be omitted
```php
// equals to $sesClientData = new SesClientData($sender)
$sesClientData = SesClientData($sender); // $sender is required and must be verified in amazon sdk email/domain

false === empty($htmlText) && $sesClientData->setHtmlText($htmlText); // is optional and can be omitted if email html text is not required
false === empty($text)     && $sesClientData->setText($text); // is optional and can be omitted if email text is not required, setText method will remove all html tags with strip_tags method
false === empty($subject)  && $sesClientData->setSubject($subject) // is optional and can be omitted if email subject is not required

/**
 * at least on recipient is required to send email
 * 
 * fist argument ( email ) is required and must be valid email
 * second argument ( fullname ) is optional and ca be omitted, it's used to display recipient email fullname in recipients list
 */ 
$sesClientData->addRecipient('email@domain', 'fullname');
$sesClientData->addRecipient('email@domain.com');
$sesClientData->addRecipient('email@domain.com); // will be omitted, because "email@domain.com" was already added previously

/**
 * attachments is optional and can be omitted
 *
 * first argument ( file path ) must be full file path to existing and readable file 
 * second argument ( filename ) can be omitted, if it's omitted, then file basename will be used by default
 */
$sesClientData->addAttachment(
    Attachment::create('full/path/to/attachment.file', 'fileTitle'), // attachment filename will be 'fileTitle'
    Attachment::create('full/path/to/attachment/file.txt')           // attachment filename will be 'file'
);
```

### Ses client sender
Ses client sender is wrapper for amazon sdk ses client and used to send email ( SesClientData ) using amazon SesClient
```php
/**
 * SesClientSender have some predefined configuration values and can be used out-of-box.
 * http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/credentials.html#using-the-aws-credentials-file-and-credential-profiles
 *
 * equals to $sesClientSender = new SesClientSender()
 */
$sesClientSender = SesClientSender::create();

false === empty($profile)         && $sesClientSender->setProfile($profile);                 // is optional if you need to change default ses profile in credentials file ( default )
false === empty($credentialsPath) && $sesClientSender->setCredentialsPath($credentialsPath); // is optional if you need to change default path to credentials path ( ~/.aws/credentials )
false === empty($version)         && $sesClientSender->setVersion($version);                 // is optional if you need to change default version ( 2010-12-01 )
false === empty($region)          && $sesClientSender->setRegion($region);                   // is optional if you need to change default region ( us-east-1 )

/**
 * method will directly send email ( $sesClientData ) with amazon ses client api
 * ses client result object ( \Aws\Result ) will be returned
 */
$result = $sesClientSender->send($sesClientData);
// equals to
$sesClientSender->send($sesClientData);
$result = $sesClientSender->getResult();
```
