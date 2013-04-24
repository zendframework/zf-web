<?php
require_once 'Zend/Soap/Wsdl.php';
require_once 'Zend/Soap/AutoDiscover.php';
//fixed: require_once './AutoDiscover.php';
require_once 'Zend/Soap/Server.php';

// Disable wsdl cache, so changes take effect
ini_set("soap.wsdl_cache_enabled", "0");

class TestService {
    /**
     * update something
     * does not return something; the operation either completes successfully, or a soapfault is generated
     * @param string $data
     * @return void
     */
    public function update($data)
    {
	// do something with $data
	//$operation_failed = true;
	if($operation_failed) {
	    throw new SoapFault('Server', 'that did not work!');
	}
    }
}

if (isset($_GET['wsdl'])) {
	$autodiscover = new Zend_Soap_AutoDiscover();
	$autodiscover->setClass('TestService');
	$autodiscover->handle();
} else {
	$server = new Zend_Soap_Server('http://localhost/~tim/ibe/test/services.php?wsdl');
	$server->setClass('TestService');
	$server->handle();
}
