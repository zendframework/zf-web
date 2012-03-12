<?php

class AboutController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->view->headLink()->appendStylesheet('/css/whyzf.css', 'all');
        $this->view->headTitle()->append('About');
        $this->view->tab = 'about';
    }

    public function indexAction()
    {
        $this->_forward('overview');
    }

    public function overviewAction()
    {
    }

    public function featuresbenefitsAction()
    {
    }

    public function componentsAction()
    {
    }

    public function numbersAction()
    {
    }

    public function casestudiesAction()
    {
    }
    
    public function faqAction()
    {
    }
    
    public function zf2FaqAction()
    {
    }
}
