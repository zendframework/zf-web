<?php
session_start();
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');

function main()
{
    $user = 'CHANGEME@gmail.com';
    $pass = 'CHANGEME';
    $service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
    
    // Delete service if ?reset=true query parameter is present
    if (isset($_GET['reset']) && $_GET['reset'] == 'true') {
        echo "<p><strong>Deleting existing service object!</strong></p>";
        
        unset($_SESSION['gdata_service']);
    }
    
    // Prepare client
    if (!isset($_SESSION['gdata_service'])) {
        echo "<p><strong>Creating new service object.</strong></p>";
        $client = Zend_Gdata_ClientLogin::getHttpClient($user,$pass,$service);
        $s = new Zend_Gdata_Calendar($client);
        $_SESSION['gdata_service'] = $s;
    } else {
        echo "<p><strong>Reusing existing service object.</strong></p>";
    }
    $s = $_SESSION['gdata_service'];
    
    // Retrieve all calendars
    $eventFeed = $s->getCalendarEventFeed();
    echo "<ul>\n";
    foreach ($eventFeed as $event) {
      echo "\t<li>" . $event->title->text .  " (" . $event->id->text . ")\n";
      echo "\t\t<ul>\n";
      foreach ($event->when as $when) {
        echo "\t\t\t<li>Starts: " . $when->startTime . "</li>\n";
      }
      echo "\t\t</ul>\n";
      echo "\t</li>\n";
    }
    echo "</ul>\n";
}

main();