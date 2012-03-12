<?php

class Application_Form_ManualSearch extends Zend_Form
{
    protected $_language  = 'en';
    protected $_locales   = array();
    protected $_version   = '';
    protected $_versions  = array();

    public function init()
    {
        $this->addElement('text', 'query', array(
            'filters'  => array('StringTrim'),
            'required' => true,
            'label'    => 'Search term:',
            'size'     => 30,
            'decorators' => array(
                'ViewHelper',
                'Label',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
            )
        ));
        $languages = $this->getLanguageOptions();
        $this->addElement('select', 'language', array(
            'required'     => false,
            'label'        => 'Language:',
            'multiOptions' => $languages,
            'value'        => $this->getLanguage(),
            'escape'       => false,
            'validators'   => array(
                array('InArray', false, array(array_keys($languages))),
            ),
            'decorators'   => array(
                'ViewHelper',
                'Label',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
            )
        ));
        $this->addElement('select', 'version', array(
            'required'     => false,
            'label'        => 'Version:',
            'value'        => $this->getVersion(),
            'multiOptions' => $this->getVersionOptions(),
            'decorators' => array(
                'ViewHelper',
                'Label',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'class' => 'element')),
            )
        ));
        $this->addElement('submit', 'search', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Search Manual!',
            'class'    => 'btn_submit',
            'decorators' => array(
                'ViewHelper',
                'Errors',
                array('HtmlTag', array('tag' => 'li', 'id' => 'manual-search-li')),
            )
        ));
    }

    public function setLocales(array $locales)
    {
        $this->_locales = $locales;
        return $this;
    }

    public function getLocales()
    {
        return $this->_locales;
    }

    public function getLanguageOptions()
    {
        $languages = array('all' => 'All');
        foreach ($this->getLocales() as $key => $locale) {
            $languages[$key] = html_entity_decode(
                $locale['locale_name'],
                ENT_COMPAT,
                'UTF-8'
            );
        }
        return $languages;
    }

    public function setLanguage($language)
    {
        $this->_language = $language;
        return $this;
    }

    public function getLanguage()
    {
        return $this->_language;
    }

    public function setVersions(array $versions)
    {
        $this->_versions = $versions;
        return $this;
    }

    public function getVersions()
    {
        return $this->_versions;
    }

    public function getVersionOptions()
    {
        $options = array();
        foreach ($this->getVersions() as $v) {
            $options[$v] = $v;
        }
        return $options;
    }

    public function setVersion($version)
    {
        $this->_version = $version;
        return $this;
    }

    public function getVersion()
    {
        return $this->_version;
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
