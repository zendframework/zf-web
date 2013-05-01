<?php

require_once "Zend/Loader.php";
Zend_Loader::registerAutoload();

$db = Zend_Db::factory('pdo_mysql', array('username' => 'root', 'password' => 'tribal', 'host' => 'localhost', 'dbname' => 'zfunittests'));
$db->getProfiler()->setEnabled(true);

Zend_Db_Table_Abstract::setDefaultAdapter($db);

class Bugs extends Zend_Db_Table_Abstract
{
    protected $_schema = "zfunittests";
    protected $_name = 'bugs';
}

class Products extends Zend_Db_Table_Abstract
{
    protected $_schema = "zfunittests";
    protected $_name   = "products";
}

class BugsProducts extends Zend_Db_Table_Abstract
{
    protected $_schema = "zfunittests";
    protected $_name = "bugs_products";

    protected $_referenceMap    = array(
        'Bug' => array(
            'columns'           => 'bug_id', // Deliberate non-array value
            'refTableClass'     => 'Bugs',
            'refColumns'        => array('bug_id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE,
        ),
        'Product' => array(
            'columns'           => array('product_id'),
            'refTableClass'     => 'Products',
            'refColumns'        => array('product_id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE,
        )
    );
}

$bugs = new Bugs();
$bug = $bugs->find(1)->current();

$products = $bug->findManyToManyRowset("Products", "BugsProducts");

var_dump($db->getProfiler()->getQueryProfiles());