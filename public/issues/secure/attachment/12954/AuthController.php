<?php
class AuthController extends Zend_Controller_Action{

 public function indexAction()
    {
               
    }
 
 public function loginAction ()
   {
     $userForm = new Form_LoginForm();
     $userForm->setAction('/auth/login');     
     if ($this->_request->isPost() && $userForm->isValid($_POST)) {
         $data = $userForm->getValues();
         $db = Zend_Db_Table::getDefaultAdapter();
         $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'utils',
            'username','source');
         $authAdapter->setIdentity($data['username']);
         $authAdapter->setCredential($data['password']);
         $result = $authAdapter->authenticate();
     if ($result->isValid()) {
         $auth = Zend_Auth::getInstance();
         $data1 = $authAdapter->getResultRowObject(null,'source');
        // $auth->getStorage()->write($data1);
        // $auth->getStorage()->write($data);
        $dom = $auth->getIdentity()->domainadmin;
        $host = $auth->getIdentity()->hostadmin;
        $mySession = SessionWrapper::getInstance();
        $mySession->setSessVar('username', $formData['username']);
         date_default_timezone_set('Europe/Brussels');
                    
                    

		echo 'valid';
                
           } else {
        $this->view->loginMessage = "Sorry, your username or password was incorrect";
           }}
        $this->view->form = $userForm;
     }
 public function logout()
     {
       $authAdapter = Zend_Auth::getInstance();
       $authAdapter->clearIdentity();
     }
 
}
