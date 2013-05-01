<?php
error_reporting(E_ALL|E_STRICT);
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();
class A {
    function __construct () {
        $this->b = new B($this);
    }
}

class B {
    function __construct ($parent = NULL) {
        $this->parent = $parent;
    }
}



class MyProcess extends ZendX_Console_Process_Unix
{
    

    public function _run()
    {
        $a = new A();
        throw new Exception('Aborting Process');
    }  
}

$exit = false;
$loopcount =  0;
while (!$exit) {

    $process = new MyProcess();
    try {
        $process->start();
    } Catch (Exception $e) {
  
    }
    $loopCount++;
    if($loopCount > 10000000) {
        $exit = true;
    }
}
