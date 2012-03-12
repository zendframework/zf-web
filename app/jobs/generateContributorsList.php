<?php
$include_path = array(
    '.',
    realpath(dirname(__FILE__) . '/../../framework/library'),
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $include_path));
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

require_once __DIR__ . '/lib/Contributors.php';

$contributors = new ZF\Jobs\Contributors();
$contributors->dispatch();
