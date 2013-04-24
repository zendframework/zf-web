<?php 
require_once('Zend/Amf/Server.php');
require_once('TestService.php');
$server = new Zend_Amf_Server();
$server->setProduction(false);

$server->setClass("TestService");
$server->setClassMap("ComplexBARequest", "ComplexBARequest");

echo($server->handle());
?>