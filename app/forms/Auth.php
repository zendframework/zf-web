<?php

class Application_Form_Auth extends Zend_Form
{
    public function init()
    {
        $this->addElement('text', 'username', array(
            'required'   => true,
            'label'      => 'Username:',
            'decorators' => array(
                'ViewHelper',
                'Label',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
            ),
        ));
        
        $this->addElement('password', 'password', array(
            'required'   => true,
            'label'      => 'Password:',
            'decorators' => array(
                'ViewHelper',
                'Label',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
            ),
        ));
        
        $this->addElement('submit', 'search', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Login',
            'class'    => 'btn_submit',
            'decorators' => array(
                'ViewHelper',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'id' => 'manual-search-li')),
            ),
        ));
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
