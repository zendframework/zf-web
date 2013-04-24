<?php
header('Content-Type: text/plain');

set_include_path(
  realpath('../src/ZendFramework/library')
);
                           
require_once('Zend/Http/Client.php');
require_once('Zend/XmlRpc/Client.php');
require_once('Zend/XmlRpc/Request.php');

require_once('Zend/XmlRpc/Generator/XmlWriter.php');
$generator = new Zend_XmlRpc_Generator_XmlWriter();
 
//require_once('Zend/XmlRpc/Generator/DomDocument.php');
//$generator = new Zend_XmlRpc_Generator_DomDocument();

Zend_XmlRpc_Value::setGenerator($generator);

$httpClient = new Zend_Http_Client();
$httpClient->setAdapter('Zend_Http_Client_Adapter_Test');
$httpClient->getAdapter()->addResponse(
  new Zend_Http_Response(200, array(), new Zend_XmlRpc_Response('OK'))
);

$client = new Zend_XmlRpc_Client('http://localhost/', $httpClient);

for($i = 8; $i > 0; $i--)
{
  $client->call("test-$i", array('one', 'two'));
  echo "memory_usage " . memory_get_usage() . " bytes \n";
}