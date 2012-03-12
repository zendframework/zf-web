<?php

class DeveloperController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_redirect('/community/contribute');
    }
}
