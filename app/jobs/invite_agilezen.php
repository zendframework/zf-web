<?php
/**
 * Send the agilezen invitation stored in a file
 * 
 * Usage: invite_agilezen.php [projectId] [file]
 * 
 * @author Enrico Zimuel (enrico@zend.com)
 */

if (count($argv)!==3) {
   echo "Usage: invite_agilezen.php [projectId] [file]\n";
   exit;
}

$projectId = $argv[1];
$file      = $argv[2];

require_once '/var/www/staging.framework.zend.com/vendor/Zf2/Zend/Loader/StandardAutoloader.php';

/** DURING INSTANTIATION **/
$loader = new \Zend\Loader\StandardAutoloader(array(
    // absolute directory
    'Zend' => '/var/www/staging.framework.zend.com/vendor/Zf2/Zend',
));
$loader->register(); 

use Zend\Service\AgileZen\AgileZen;

$apiKey='882636b35b4742edbe2b9c6261e2770d';

$agileZen = new AgileZen($apiKey);

$emails = unserialize(file_get_contents($file));

if (empty($emails)) {
    die();
}

$sent = array();
foreach ($emails as $email) {
    $data = array (
        'email' => $email,
        'role'  => 76138 // Invitation role Id
    );
    $result = $agileZen->addInvite($projectId, $data);
    if ($agileZen->isSuccessful()) {
        $sent[] = $email;
    } else {
	echo "Error (" . $agileZen->getErrorCode() . ") " .$agileZen->getErrorMsg(). "\n";
    }
}

// Delete the file with the email
unlink($file);

// Store the email sent in a log file

$logFile = '/var/www/staging.framework.zend.com/app/var/log/agilezen_invitation.log';

if (file_exists($logFile)) {
    $log = unserialize(file_get_contents($logFile));
    $diff = array_diff($sent, $log);
    if (!empty($diff)) {
        file_put_contents($logFile, serialize(array_merge($log, $diff)));
    }
} else {
    file_put_contents($logFile, serialize($sent));
}


