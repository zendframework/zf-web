<?php

class AdminController extends Zend_Controller_Action
{
    
    public function init()
    {
        require_once MODELS_PATH.'Categories.php';
        require_once MODELS_PATH.'Users.php';
        require_once MODELS_PATH.'UsersRights.php';
        require_once MODELS_PATH.'Sentences.php';
        require_once MODELS_PATH.'SentencesAlt.php';
        require_once MODELS_PATH.'Images.php';
        require_once MODELS_PATH.'Sounds.php';
        require_once MODELS_PATH.'Videos.php';
        require_once MODELS_PATH.'Contents.php';
        require_once MODELS_PATH.'Tasks.php';
        require_once MODELS_PATH.'Villes.php';
        require_once MODELS_PATH.'Seo.php';
        require_once MODELS_PATH.'Sites.php';
        require_once MODELS_PATH.'Sites_Seo.php';
        require_once 'Custom/Referencement.php';
        Zend_Session::start();
        $this->session = new Zend_Session_Namespace('Authentification');
        $this->checkAuthentification();
        $this->view->controller = $this->getRequest()->getControllerName();
        $this->view->action = $this->getRequest()->getActionName();
        if (isset($this->session->id)) {
            $this->view->rights = $this->session->rights;
        }
        $this->UPLOAD_PATH = realpath(dirname(__FILE__) . '/../../public/images/database/');
        $this->UPLOAD_PATH_2 = realpath(dirname(__FILE__) . '/../../public/sounds/database/');
        $this->BACKUP_PATH = realpath(dirname(__FILE__) . '/../../public/backups/');   
    }
    
    public function checkAuthentification()
    {
        $access = ($this->session->access == true) ? true : false;
        if (!$access) {
            return $this->_redirect('index');
        }   
    }
    
    public function getUser()
    {
        return $this->session->id;
    }
    
    public function rememberCategory()
    {
        $this->view->message .= '<div class = "notice">Votre catégorie actuelle est <strong>' . ((empty($this->session->categoryName)) ? '(aucune)' : $this->session->categoryName) . '</strong></div>';
    }
    
    public function rememberAdmin()
    {
        $this->view->message .= '<div class = "notice">Vous êtes actuellement identifié en tant qu\'<strong>administrateur</strong></div>';
    }

    public function rememberNoAccess()
    {
        $this->view->message .= '<div class = "error">Vous n\'avez pas les droits d\'accès suffisants.</div>';
        $this->view->controller = 'index';
        return $this->render('message');
    }
        
    public function rememberWork()
    {
        $table = new Tasks();
        $select = $table->select()->where('Server = ?', $this->session->id)->order('Created DESC')->limit(2);
        $rows = $table->fetchAll($select);
        $count = count($rows);
        if ($count > 0) {
            $this->view->message .= '<div class = "notice">';
            $i = 0;
            foreach ($rows as $row) {
                $date = new Zend_Date($row->Created); 
                $this->view->message .= '<strong><u>Liste des tâches du ' . $date . '</u></strong><br />' . nl2br($row->Message);
                if (++$i < $count) {
                    $this->view->message .= '<br /><br />';    
                }
            }
            $this->view->message .= '</div>';
        } else {
            //$this->view->message .= '<div class = "notice">Aucune tâche pour le moment</div>';
        }
    }
    
    public function setcategoryAction()
    {
        $form = new Zend_Form();
        $form->setAction(PUBLIC_PATH . $this->getRequest()->getControllerName()  . '/setcategory/');
        $form->setMethod('post');
        $form->setAttrib('id', 'CategoryForm');                    
        $type = new Zend_Form_Element_Select('Categorie');
        //$type->setLabel('Choisissez la catégorie de vos saisies');
        $type->setRequired(true);
        $table = new Categories();
        $select = $table->select();
        $objects = $table->fetchAll($select);
        foreach ($objects as $object) {  
            $type->addMultiOption($object->Id, $object->Name);
        }
        if (!empty($this->session->category)) {
            $type->setValue($this->session->category);
        } else {
            $type->setValue($objects[0]->Id);    
        }
        $type->setDecorators(array(
            'ViewHelper',
            'Description',
            'Errors',
            array('HtmlTag', array('tag' => 'dd', 'class' => 'PositionCenter'))
        ));
        $submit = new Zend_Form_Element_Submit('Enregistrer');       
        $form->addElement($type);
        $form->addElement($submit);
        if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
            $values = $form->getValues(); 
            $row = $table->find($values['Categorie'])->current();
            unset($_COOKIE['PreviousCategory']);
            setcookie('PreviousCategory', $row->Id, time() + 365 * 24 * 60 * 60, '/');
            $this->session->category = $row->Id;
            $this->session->categoryName = $row->Name;
            $this->session->message = '<div class = "success">Vos saisies seront désormais pris en compte et rentrés dans la catégorie <strong>' . $row->Name . '</strong></div>';      
            return $this->_forward('index');
        } else {
            $this->rememberCategory(); 
            $this->view->message .= '<div class = "notice">Choisissez le métier sur lequel vous souhaitez travailler</div>';
            $this->view->form = $form;      
            return $this->render('form');
        }
    }

const INTERVAL = 7;
protected $session;
}