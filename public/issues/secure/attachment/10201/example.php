<?php
require_once 'Zend/Http/Client.php';

$uri = 'http://www.example.com/pan/aReport3Key.action?columns=orderNR&columns=orderValue';
echo "original uri: $uri\n";
$client = new Zend_Http_Client($uri);
$client->request();
echo "request made: " . $client->getLastRequest() . "\n";
?>