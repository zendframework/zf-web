<?php
require_once("Zend/Acl.php");
require_once("Zend/Debug.php");
require_once 'Zend/Acl/Role.php';
require_once("Zend/Log.php");
require_once("Zend/Log/Writer/Stream.php");
$writer = new Zend_Log_Writer_Stream('php://output');
$logger = new Zend_Log($writer);
$acl = new Zend_Acl();
$acl->setLogger($logger);
$acl->addRole('guest')
    ->addRole('member')
    ->addRole('admin');

$parents = array('member', 'admin', 'guest');
$acl->addRole('someUser', $parents);
require_once 'Zend/Acl/Resource.php';
$acl->add(new Zend_Acl_Resource('someResource'));
$acl->deny('guest', 'someResource');
// $acl->allow('guest', 'someResource','pork');
$acl->allow('member', 'someResource');
//echo $acl->isAllowed('guest', 'someResource') ? 'allowed' : 'denied';
Zend_Debug::dump($acl->isAllowed('someUser', 'someResource'));
Zend_Debug::dump($acl->isAllowed('someUser', 'someResource', 'pork'));

