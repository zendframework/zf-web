<?php

/**
 * Main controller for all documentation (some documentation must
 * still be migrated from the manual controller)
 */
class DocsController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_forward('overview');
    }
    
    public function overviewAction()
    {
    }
    
    public function multimediaAction()
    {
        $this->view->active  = 'multimedia'; // title for title bar
    }

    public function translationsAction()
    {
        ZfWeb_Plugins_Caching::$doNotCache = true;
        $version             = $this->_getParam('version', null);

        require_once dirname(dirname(__FILE__)) . '/models/ReleaseModel.php';
        $model = new ReleaseModel('ZendFramework', $version);

        $this->view->release = $model;

        require_once dirname(dirname(__FILE__)) . '/models/ManualModel.php';
        $manualModel          = new ManualModel();
        $locales              = $manualModel->getLocales();

        $this->view->locales = $locales;
    }

    public function apiAction()
    {
        $versions = Zend_Registry::get('config')->apidoc->versions->toArray();
        foreach ($versions as $minor => $specific) {
            $label = str_replace('r_', '', $minor);
            if ($label == 'core') {
                unset($versions[$minor]);
                continue;
            }
            $label = str_replace('_', '.', $label);
            $versions[$label] = $specific;
            unset($versions[$minor]);
        }
        $this->view->versions = $versions;
    }
}
