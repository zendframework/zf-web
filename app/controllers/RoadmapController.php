<?php

class RoadmapController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->version = $this->_getParam('version', false);
    }
}
