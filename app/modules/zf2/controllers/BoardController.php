<?php

class Zf2_BoardController extends Zend_Controller_Action
{
    protected $_form;
    
    public function init()
    {
        ZfWeb_Plugins_Caching::$doNotCache = true;
    }

    public function indexAction()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $config    = $bootstrap->getResource('config');
        
        require_once dirname(__FILE__) . '/../../../models/AgileZenModel.php';
        $agileZen = new AgileZenModel($config->agilezen->path . '/agilezen.zf2');
                
        $this->view->assign(array(
            'data' => $agileZen->getData(),
            'tags' => $agileZen->getTags(),
            'form' => $this->getForm()
        ));
    }
    public function statusAction()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $config    = $bootstrap->getResource('config');
        
        require_once dirname(__FILE__) . '/../../../models/AgileZenModel.php';
        $agileZen = new AgileZenModel($config->agilezen->path . '/agilezen.zf2');
        
        $data = $agileZen->filterByTag($this->_request->getPost('tag'));
        
        $this->_helper->layout()->disableLayout();
        
        $this->view->assign(array(
            'data' => $data
        ));
    }
    public function inviteAction()
    {
        $this->view->assign(array(
            'form' => $this->getForm()
        ));
    }
    public function sendAction()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $config    = $bootstrap->getResource('config');
        
        $request = $this->getRequest();
        $post = $request->getPost('inviteme');
        
        if (!$request->isPost() || empty($post['email'])) {
            return $this->_helper->redirector('invite');
        }
        
        $form = $this->getForm();
        if (!$form->isValid($request->getPost())) {
            $this->view->assign(array(
                'form' => $form
            ));
            return $this->render('invite');
        }

              
        if (!file_exists($config->agilezen->path . '/invites_agilezen')) {
            $data = array();
        } else {        
            $data = unserialize(file_get_contents($config->agilezen->path . '/invites_agilezen'));
        }    
        
        if (in_array($post['email'], $data)) {
            $this->view->msg = "Invitation already sent, please check your email box.";
            $this->view->type = 'gray';
        } else {
            $data[]= $post['email'];
            if (file_put_contents($config->agilezen->path . '/invites_agilezen', serialize($data))!==false) {
                $this->view->msg =  "Invitation sent. You will receive an email soon, thanks.";
                $this->view->type = 'green';
            }
        }
        
    }
    /**
     * Form Invite Me
     * 
     * @return type 
     */
    public function getForm()
    {
        $config = Zend_Registry::get('config');
        if (null === $this->_form) {
            
            $form = new Zend_Form(array(
                'name'    => 'inviteme',
                'isArray' => true,
                'method'  => 'post',
                'action'  => '/zf2/board/send',
            ));

            $email = $form->createElement('text', 'email');
            $email->setLabel('Enter your email:')
                  ->setRequired(true);

            if (isset($config->recaptcha)) {
                $captchaOptions = $config->recaptcha->toArray();
                $captchaOptions['captcha'] = 'ReCaptcha';
            } else {
                $captchaOptions = array('captcha' => 'Figlet', 'wordLen' => 5, 'timeout' => 300);
            }
                    
            $captcha = $form->createElement('captcha', 'captcha', array(
                'label'   => 'Please fill the CAPTCHA',
                'captcha' => $captchaOptions
                ));
                    
             $send = $form->createElement('submit', 'send');
             $send->setRequired(false)
                 ->setIgnore(true)
                 ->setLabel('Invite me')
                 ->setAttrib('class', 'btn_submit');

            $form->addElements(array(
                $email,
                $captcha,
                $send
            ));

            $this->_form = $form;
        }
        return $this->_form;
    }
}
