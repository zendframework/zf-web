<?php

class ErrorController extends Zend_Controller_Action
{
    protected $_404Errors = array(
        Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER,
        Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION,
    );

    protected $_services = array(
        'code',
        'crowd',
        'issues',
        'wiki',
    );

    public function errorAction()
    {
        $this->_helper->viewRenderer->setViewSuffix('phtml');

        // Do not cache errors
        ZfWeb_Plugins_Caching::$doNotCache = true;

        $errors  = $this->_getParam('error_handler');
        $request = $errors->request;
        switch ($errors->type) { 
            case (in_array($request->getControllerName(), $this->_services)):
                $this->getResponse()->setHttpResponseCode(503);
                $this->render('services');
                break;
            case (in_array($errors->type, $this->_404Errors)):
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                $this->view->text    = 'The page you requested does not exist or could not be found.';
                break;  
            default:    
                // application error     
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                $this->view->text    = 'An error occurred during your request. Please try again later.';
                break;
        }
        
        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }
}
