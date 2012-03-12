<?php

class Zf2_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        ZfWeb_Plugins_Caching::$doNotCache = true;
    }

    public function indexAction()
    {
        $this->_helper->viewRenderer->renderScript('zf2/index.phtml');
    }
}
