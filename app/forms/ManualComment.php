<?php

/**
 * Form for manual comments
 **/
class Application_Form_ManualComment extends Zend_Form
{
    protected $_fontConfig      = false;
    protected $_recaptchaConfig = false;
    protected $_user            = false;

    public function init()
    {
        if (!$user = $this->getUser()) {
            $this->addElement('text', 'name', array(
                'required'   => true,
                'label'      => 'Name:',
                'decorators' => array(
                    'ViewHelper',
                    'Label',
                    'Errors',
                    array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
                ),
            ));
            
            $this->addElement('text', 'email', array(
                'required'   => true,
                'label'      => 'Email:',
                'decorators' => array(
                    'ViewHelper',
                    'Label',
                    'Errors',
                    array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
                ),
            ));
        }
            
        $this->addElement('textarea', 'comment', array(
            'filters'    => array(),
            'required'   => true,
            'label'      => 'Comment:',
            'description' => '<a target="_blank" href="http://en.wikipedia.org/wiki/BBCode">BBCode</a> is allowed in the comment markup',
            //'size'     => 30,
            'decorators' => array(
                'ViewHelper',
                'Label',
                'Errors',
                array('Description', array('escape' => false)),
                array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
            ),
        ));
            
        if (!$user) {
            $numberOfLetters = 5;
            if (!$config = $this->getRecaptchaConfig()) {
                $captchaOptions = array('captcha' => 'Figlet', 'wordLen' => 5, 'timeout' => 300);
            } else {
                $captchaOptions = array('captcha' => 'ReCaptcha', 'privKey' => $config->privkey, 'pubKey' => $config->pubkey);
            }
            
            $this->addElement('captcha', 'captcha', array(
                'label'   => 'Please fill in the ' . $numberOfLetters . ' letters that you see below.',
                'captcha' => $captchaOptions,
            ));
        }
        
        $this->addElement('submit', 'search', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Add Comment!',
            'class'    => 'btn_submit',
            'decorators' => array(
                'ViewHelper',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'id' => 'manual-search-li')),
            ),
        ));
    }

    public function setUser($user)
    {
        $this->_user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setFontConfig(Zend_Config $config)
    {
        $this->_fontConfig = $config;
        return $this;
    }

    public function getFontConfig()
    {
        return $this->_fontConfig;
    }

    public function setRecaptchaConfig(Zend_Config $config)
    {
        $this->_recaptchaConfig = $config;
        return $this;
    }

    public function getRecaptchaConfig()
    {
        return $this->_recaptchaConfig;
    }

    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'searchForm'))
                 ->addDecorator('Form');
        }
    }
}
