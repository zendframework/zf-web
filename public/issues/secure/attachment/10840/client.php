<?php

// Change your server path here
$server = "http://127.0.0.1/Rest_Test/server.php";


// 1st test, manual call to service using HttpClient
require_once ('Zend/Http/Client.php');

$client = new Zend_Http_Client($server.'?method=parrot&arg1=This%20is%20my%20string');

echo "Result using Zend_Http_Client:<br>";
echo htmlspecialchars($client->request()->getBody());


echo '<hr>';

// 2nd test, call to service using Zend_Rest_Client
require_once ('Zend/Rest/Client.php');

$rest = new Zend_Rest_Client($server);

// Should parrot the input string, but it fails
echo "Result using Zend_Rest_Client:<br>";
echo $rest->parrot('This is my string')->get();

