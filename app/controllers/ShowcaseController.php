<?php

class ShowcaseController extends Zend_Controller_Action {

    public function preDispatch()
    {
    	$this->view->title = 'Showcase';
        $this->view->tab   = 'showcase';
    }

    public function indexAction()
    { }
}
