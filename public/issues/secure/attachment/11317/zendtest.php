<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../library');
require_once '../library/Zend/Service/Nirvanix.php'; 

$auth = array('username' => null,'password' => null,'appKey' => null);

/*
* This should throw the following exception:

Fatal error: Uncaught exception 'Zend_Service_Nirvanix_Exception' with message 'Missing Required Parameter: 
appKey, username, password' in D:\PHP5\website\library\Zend\Service\Nirvanix\Response.php:119 
*/
$nirvanixSvc = new Zend_Service_Nirvanix($auth);

?>