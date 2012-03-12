<?php
class DemoController extends Zend_Controller_Action
{
    public function indexAction()
    {
    }

    public function __call($method, $args)
    {
        if ('Action' != substr($method, -6)) {
            require_once 'Zend/Controller/Action/Exception.php';
            throw new Zend_Controller_Action_Exception(sprintf('Method "%s" does not exist and was not trapped in __call()', $methodName), 500);
        }
        return $this->_helper->redirector('index');
    }
}
