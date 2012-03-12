<?php

/** ChangelogModel */
require_once APPLICATION_PATH . '/models/ChangelogModel.php';

class ChangelogController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_forward('view', 'changelog');
    }

    public function viewAction() 
    {
        $model = new ChangelogModel();
        $version = $this->_getParam('version', false);
        if (!$version || !$model->hasVersion($version)) {
            $version = $model->getLatestVersion();
            if (empty($version)) {
                throw new Exception('No changelog versions found!');
            }
        }

        $this->view->model   = $model;
        $this->view->version = $version;
        $this->view->issues  = $model->getIssuesByVersion($version);
    }
}
