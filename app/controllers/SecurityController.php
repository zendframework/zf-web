<?php

class SecurityController extends Zend_Controller_Action
{
    public function indexAction()
    {
    }

    public function advisoriesAction()
    {
    }

    public function advisoryAction()
    {
        ZfWeb_Plugins_Caching::$doNotCache = true;
        if (!$report = $this->_getParam('report', false)) {
            $this->error = 'No advisory identifier provided';
            return;
        }
        $this->view->report = $report;
    }

    public function feedAction()
    {
        $this->view->response = $this->getResponse();
    }
}
