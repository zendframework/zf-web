<?php
require_once 'Zend/XmlRpc/Server.php';
class MyService {
	public function sayHello($arg1) {
		return "Hi ".$arg1;
	}
}

$server = new Zend_XmlRpc_Server();
$server->setClass('MyService');
echo $server->handle();
