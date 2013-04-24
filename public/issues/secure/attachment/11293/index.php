<?php
require_once 'Zend/XmlRpc/Client.php';

$client = new Zend_XmlRpc_Client('http://localhost/zend_service/service.php');
$test = $client->call('sayHello', array('jerome'));
var_dump($test);
