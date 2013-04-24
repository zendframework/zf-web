<?php
class LoginController extends Zend_Controller_Action{
	public function init(){
	}
	public function indexAction(){
		$this->_forward('login');
	}
	public function loginAction(){
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->setNamespace('actionErrors');
		$this->view->actionErrors = $flashMessenger->getMessages();	
	}
	protected function _getAuthAdapter($formData)
	{
		$db = new Zend_Db_Adapter_Pdo_Mysql(array(
				'host'     => '127.0.0.1',
				'username' => 'root',
				'password' => '',
				'dbname'   => 'places'
			));

		$authAdapter = new Zend_Auth_Adapter_DbTable($db);
		$authAdapter->setTableName('users')
					->setIdentityColumn('username')
					->setCredentialColumn('password');
		// get "salt" for better security 
		//$config = Zend_Registry::get('config');
		//$salt = $config->auth->salt;
		$password = $formData['password'];
		$authAdapter->setIdentity($formData['username']);
		$authAdapter->setCredential($password);
		return $authAdapter;
	}
	public function identifyAction(){
		$success = false;
		$message = '';
		$message2='';
		if ($this->_request->isPost()) {
			// collect the data from the user
			$formData=$this->getRequest()->getPost();
			if (empty($formData['username'])|| empty($formData['password'])) {
				$message = 'Please provide a username and password.';
			} else {
				// do the authentication
				
				$authAdapter = $this->_getAuthAdapter($formData);
				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);
				if ($result->isValid()) {
					// success: store database row to auth's storage
					// (Not the password though!)
					$data = $authAdapter->getResultRowObject(null,'password');
					$auth->getStorage()->write($data);
					$success = true;
					$redirectUrl = $this->_redirectUrl;
				} else {
					$message = 'Login failed';
					$message2= $formData['username'];
				}
			}
		}
		if(!$success) {
			$flashMessenger = $this->_helper->FlashMessenger; 
			$flashMessenger->setNamespace('actionErrors');
			$flashMessenger->addMessage($message);
			$flashMessenger->addMessage($message2);
			$redirectUrl = 'login/login';
		}
			$this->_redirect($redirectUrl);
	}
	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$this->_redirect('/'); 
	}
}