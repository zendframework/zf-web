<?php

class CommunityController extends Zend_Controller_Action
{
    
    public function postDispatch()
    {
        if ($this->_request->isDispatched()) {
            $this->view->headLink()->appendStylesheet('/css/community.css', 'all');
            $this->view->headTitle()->append('Community');
            $this->view->tab = 'community';
        }
    }

    public function indexAction()
    {
        $this->_forward('overview');
    }
    
    public function overviewAction()
    {
    }

    public function contributeAction()
    {
    }
    
    public function resourcesAction() 
    {
    } 

// The applications page is currently out of date and should not be
// resurrected until it has been updated.
    
//    public function applicationsAction()
//    {
//        require_once dirname(dirname(__FILE__)) . '/models/ApplicationsModel.php';
//        $model = new ApplicationsModel();
//        $this->view->applications = $model->getApplications();
//    }

    public function contributorsAction()
    {
        require_once dirname(dirname(__FILE__)) . '/models/ContributorsModel.php';
        $model = new ContributorsModel();
        $this->view->contributors  = $model->getContributors();
        $this->view->specialThanks = $model->getSpecialThanks();
    }

    public function logoAction()
    {
    }

    public function groupsAction()
    {
    }
}
