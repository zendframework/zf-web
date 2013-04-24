<?php
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

//get your database connection ready
$db = Zend_Db::factory('Pdo_Mssql', array(
    'host'        =>'localhost',
    'username'    => 'user',
    'password'    => 'password',
    'dbname'    => 'Database'
));

Zend_Db_Table_Abstract::setDefaultAdapter($db);

$config = array(
    'name'           => 'session',
    'primary'        => 'id',
    'modifiedColumn' => 'modified',
    'dataColumn'     => 'data',
    'lifetimeColumn' => 'lifetime'
);

Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
Zend_Session::start();

$defaultNamespace = new Zend_Session_Namespace('Default');

// Works
$defaultNamespace->requests++;

// Doesnt Work
class Foo {
    protected $_bar;
}
$defaultNamespace->serializedData = serialize(array("foo" => new Foo()));

var_dump($_SESSION);

echo "Session should save after this";

?>