<?php
if (isset($_GET['wsdl']))
	{
	$SOAPHandler = new Zend_Soap_AutoDiscover
		(
		'Zend_Soap_Wsdl_Strategy_ArrayOfTypeComplex',
		"http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}",
		'W3C'
		);
	}
?>