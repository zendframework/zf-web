<?php
require_once 'Zend/Soap/Client.php';

// Disable wsdl cache, so changes take effect
ini_set("soap.wsdl_cache_enabled", "0");

$client = new Zend_Soap_Client('http://localhost/~tim/ibe/test/services.php?wsdl');

try{
	$client->update('foodata');
	echo "OK\n";
} catch (SoapFault $e) {
	echo "Exception: ".$e."\n";
}
