<?php
class IPMCore_Controller_Action_Helpers_NavigationLoader
extends Zend_Controller_Action_Helper_Abstract {

    public function preDispatch() {

        $bootstrap = $this->getActionController()
            ->getInvokeArg('bootstrap');
        $view = $bootstrap->getResource('View');

        $module = $this->getRequest()->getModuleName();
        $file = $module;
        $container = new Zend_Navigation(
            new Zend_Config_Xml(APPLICATION_PATH . '/config/' . $file . 'menu.xml',
            'nav')
        );
        $view->navigation($container);
        $view->navigation()->breadcrumbs()->setLinkLast(false)
            ->setMinDepth(0)->setSeparator(" &raquo; ")->setRenderInvisible(true);

    }

}