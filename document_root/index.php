<?php
define('APPLICATION_PATH', realpath(dirname(dirname(__FILE__)) . '/app'));
define(
    'APPLICATION_ENV', 
    (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production')
);

error_reporting(E_ALL|E_STRICT);

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(dirname(__FILE__)) . '/framework/library'),
    get_include_path(),
)));

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: Zend Framework 1.10');

require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/etc/config.ini'
);
$application->bootstrap()
            ->run();

