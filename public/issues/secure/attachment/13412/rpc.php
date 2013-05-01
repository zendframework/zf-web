<?php
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', true);

require_once 'Zend/XmlRpc/Client.php';

$xmlRpc = new Zend_XmlRpc_Client('http://localhost/test/service.php');
echo $xmlRpc->call('getPhpInfo');