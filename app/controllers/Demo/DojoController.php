<?php
class Demo_DojoController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        ZfWeb_Plugins_Caching::$doNotCache = true;
        // $this->_helper->layout->disableLayout();

        $contextSwitch = $this->_helper->contextSwitch;
        $contextSwitch->addContext('ajax', array('suffix' => 'ajax', 'headers' => array('Content-Type' => 'application/json')))
                      ->addContext('html', array('suffix' => 'html'))
                      ->addActionContext('grid-data', 'ajax')
                      ->addActionContext('grid', 'html')
                      ->initContext();

        $this->initDojo();

        $dojoType = $this->_getParam('dojoType', 'programmatic');
        if ($dojoType == 'declarative') {
            Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
        }
        $this->view->dojoType = $dojoType;
    }

    public function initDojo()
    {
        $this->view->headLink()->appendStylesheet('/css/dojodemo.css');
        $this->view->dojo()->setDjConfigOption('usePlainJson', true)
                           ->setDjConfigOption('isDebug', false)
                           ->addStylesheetModule('dijit.themes.tundra')
                           ->addStylesheet('/js/dojox/grid/_grid/tundraGrid.css')
                           ->setLocalPath('/js/dojo/dojo.js')
                           ->addLayer('/js/dojodemo/dojodemo.js')
                           ->enable( );

    }

    public function indexAction()
    {
        $this->_helper->redirector('preview');
    }

    public function previewAction()
    {
        $this->view->form = new ZfWeb_Form_Test;
    }

    public function gridAction()
    {
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
    }

    public function gridDataAction()
    {
    }
}
