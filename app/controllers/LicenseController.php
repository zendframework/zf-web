<?php

class LicenseController extends Zend_Controller_Action
{
    /**
     * Display a version of the license.
     */
    public function viewAction() {;
        $version = $this->getRequest()->getParam('version');

        switch ($version) {
            case 'new-bsd':
                $licenseTitle = 'New BSD License';
                break;
            case 'zend-framework-1.0':
                $licenseTitle = 'Zend Framework License 1.0';
                break;
            default:
                $this->_redirect('/license');
        }

        $filename = dirname(dirname(__FILE__)) . "/models/Licenses/$version.txt";
        $license = @file_get_contents($filename);

        // render the view
        $this->view->licenseTitle = $licenseTitle;
        $this->view->license      = $license;
    }
}
