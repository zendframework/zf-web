<?php

class AdminController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            throw new Exception('Must be logged in to administrate comments');
        }

        $this->_user = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $this->_user;

        if (!$this->_user->isAdministrator()) {
            throw new Exception('Must be an administrator');
        }

        require_once dirname(__FILE__) . '/../models/ManualCommentModel.php';
        $this->model = new ManualCommentModel();
    }

    public function commentsAction()
    {
        $request = $this->getRequest();
        $isXhr   = $request->isXmlHttpRequest();

        if ($isXhr) {
            $this->_helper->viewRenderer->setViewSuffix('json.phtml');
            $this->_helper->layout->disableLayout();
            $this->view->comments = $this->model->getAllComments();
        }
    }

    public function updateCommentAction()
    {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            throw new Exception('Action is available via XHR only');
        }

        $this->_helper->viewRenderer->setViewSuffix('json.phtml');
        $this->_helper->layout->disableLayout();

        $id     = $request->getParam('id', false);
        $status = $request->getParam('status', false);

        if (!$id || !$status) {
            $this->view->error = 'Missing identifier or status';
            return;
        }

        $this->model->changeStatus($id, $status);
        $this->view->comment = $this->model->getComment($id);
    }
}
