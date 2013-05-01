<?php
require_once('Zend/Mail.php');

$mail = new Zend_Mail();

$mail->setSubject('Bekræftigelse af reservation med Goopoint Online Appointments den 16-04-2009 kl. 09:00.');
$mail->setBodyText('Test');

$mail->addTo('info@spintop.nl');
$mail->send();
?>