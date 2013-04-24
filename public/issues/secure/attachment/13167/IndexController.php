<?php

require_once 'Custom/AdminController.php';

class IndexController extends AdminController
{
    
    public function indexAction()
    {
        $session = new Zend_Session_Namespace('Authentification');
        if ($session->access == true) {
            $this->_forward('home');
        } else {
            $this->view->form = $this->getForm();
        }
    }
    
    public function checkAuthentification()
    {
        return true;  
    }

    public function authentificateAction()
    {    
        if (!$this->getRequest()->isPost()) {
            return $this->_forward('index');
        }
        $form = $this->getForm();
        if (!$form->isValid($_POST)) {
            $this->view->form = $form;
            return $this->render('index');
        } else {
            $values = $form->getValues();
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get('database'));
            $authAdapter->setTableName('users');
            $authAdapter->setIdentityColumn('Login');
            $authAdapter->setCredentialColumn('Password');
            $authAdapter->setIdentity($values['Login']);
            $authAdapter->setCredential($values['Password']);
            $select = $authAdapter->getDbSelect();
            $select->where('Active = 1');
            if ($authAdapter->authenticate()->getCode() == Zend_Auth_Result::SUCCESS) {
                $databaseTable = $authAdapter->getResultRowObject();
                Zend_Registry::get('database')->query('UPDATE users SET LastConnection = NOW() WHERE Id = "' . $databaseTable->Id . '"');
                $session = $this->session;
                if (isset($_COOKIE['PreviousCategory'])) {
                    $table = new Categories();
                    $row = $table->find($_COOKIE['PreviousCategory'])->current();
                    if (isset($row->Id)) {
                        $session->category = $row->Id;
                        $session->categoryName = $row->Name;
                    }    
                }
                $session->access = true;
                $session->id = $databaseTable->Id;
                $session->lastName = $databaseTable->LastName;
                $session->firstName = $databaseTable->FirstName;
                $session->rights = new UsersRights($databaseTable->Status);
                $this->view->access = true;
                $this->_forward('home');
            } else {
                $this->view->message = '<div class="error">Identification incorrect : mauvais nom d\'utilisateur et/ou mot de passe.</div>';
                $this->view->form = $form;
                return $this->render('index'); 
            }
        }            
    }
    
    public function stayconnectedAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $session = new Zend_Session_Namespace('Authentification');
        $access = ($session->access == true) ? true : false;
        if ($access) {
            echo 'OK';    
        } else {
            echo 'Vous êtes déconnecté, sauvegardez votre travail !';
        }
    }
    
    public function homeAction()
    {
        $session = new Zend_Session_Namespace('Authentification');
        $this->view->message = '<div class="success">Vous êtes identifié en tant que ' . $session->lastName . ' ' . $session->firstName . '.</div>';
        if ($this->view->rights->canAccessAdmin()) {
            $this->rememberAdmin();
        }
        $this->rememberWork();
        return $this->render('index');
    }
    
    public function logoutAction()
    {
        $session = new Zend_Session_Namespace('Authentification');
        if (isset($session->id)) {
            unset($session->access);
            unset($session->id);
            unset($session->category); 
            unset($session->categoryName);
            header('Location: ' . PUBLIC_PATH . 'index/logout/');
        } 
        $this->view->message = '<div class="success">Vous avez bien été déconnecté</div>';
        $this->view->form = $this->getForm();
        return $this->render('index');
    }
    
    public function getForm()
    {
        $form = new Zend_Form();
        $form->setAction(PUBLIC_PATH. 'index/authentificate/');
        $form->setMethod('post');
        $form->setAttrib('id', 'Sentence');
        $login = new Zend_Form_Element_Text('Login');
        $login->setLabel('Nom d\'utilisateur');
        $login->addValidator(new Zend_Validate_Alnum());
        $login->setRequired(true);
        $password = new Zend_Form_Element_Password('Password');
        $password->setLabel('Mot de passe');
        $password->addValidator(new Zend_Validate_Alnum());
        $password->setRequired(true);
        $submit = new Zend_Form_Element_Submit('OK');       
        $form->addElement($login);
        $form->addElement($password);
        $form->addElement($submit);
        return $form;
    }

}

