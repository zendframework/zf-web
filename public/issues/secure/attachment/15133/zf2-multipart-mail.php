<?php
require_once 'Zend/Loader/StandardAutoloader.php';
$loader = new Zend\Loader\StandardAutoloader();
$loader->register();

$text = new Zend\Mime\Part('Plain Text');
$text->encoding = Zend\Mime\Mime::ENCODING_QUOTEDPRINTABLE;
$text->type = Zend\Mime\Mime::TYPE_TEXT;
$text->charset = 'UTF-8';

$html = new Zend\Mime\Part('<b>HTML</b>');
$html->encoding = Zend\Mime\Mime::ENCODING_QUOTEDPRINTABLE;
$html->type = Zend\Mime\Mime::TYPE_HTML;
$html->charset = 'UTF-8';

$message = new Zend\Mime\Message();
$message->addPart($text);
$message->addPart($html);

$mail = new Zend\Mail\Message('UTF-8');
$mail->setFrom('example@example.org');
$mail->setSubject('ZF2 Multipart Mail');
$mail->setBody($message);
$mail->addTo('example@example.org');

$transport = new Zend\Mail\Transport\Smtp();
$transport->send($mail);
?>
