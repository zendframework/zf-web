<?php 
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', true);

require_once 'Zend/XmlRpc/Server.php';

$server = new Zend_XmlRpc_Server();
$server->addFunction('getPhpInfo');
echo $server->handle();

/**
 * @return string
 */
function getPhpInfo() {
	return file_get_contents('./phpinfo.html');
}

