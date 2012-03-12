<?php
class ServicesController extends Zend_Controller_Action 
{

    public function postDispatch()
    {
        if ($this->_request->isDispatched()) {
            // $this->view->headLink()->appendStylesheet('/css/services.css', 'all');
            $this->view->headTitle()->append('Services');
    		$this->view->tab = 'services';
        }
	}

    public function indexAction() 
    {
		$this->_forward ('overview', 'services') ;
	} 

    public function overviewAction() 
    {
	}
	
    public function supportAction() 
    {
    }

    public function trainingAction() 
    {
	} 

    public function consultingAction() 
    {
	}

    public function certificationAction() 
    {
    }
} 
