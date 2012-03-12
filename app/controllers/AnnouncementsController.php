<?php

class AnnouncementsController extends Zend_Controller_Action
{
    public function indexAction() 
    {
    }

    public function listAction()
    {
    }

    public function viewAction()
    {
        if (!$page = $this->_getParam('page', false)) {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }

        $this->render($page);
    }

    public function __call($method, $args)
    {
        if ('Action' == substr($method, -6)) {
            $this->_forward('view', 'Announcements', null, array(
                'page' => $this->getRequest()->getActionName(),
            ));
        }
    }
}
