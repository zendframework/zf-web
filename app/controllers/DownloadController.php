<?php

class DownloadController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->view->headTitle()->append('Downloads');
        $this->view->tab = 'download';
    }

    public function indexAction()
    {
        $this->_forward('overview');
    }

    public function overviewAction()
    {
    }
    
    public function currentAction()
    {
        $this->_forward('latest');
    }
    
    public function latestAction()
    {
        require_once dirname(__FILE__) . '/../models/ReleaseModel.php';
        $model = new ReleaseModel('ZendFramework');

        $this->view->latest = $model;

        $stable = false;
        $versionList = $model->getVersionList();
        do {
            $currentVersion = current($versionList);
            if ($model->isVersionStable($currentVersion)) {
                $stable = true;
            } else {
                next($versionList);
            }
        } while (!$stable);
        $this->view->current = new ReleaseModel('ZendFramework', $currentVersion);

        $versionList = $model->getVersionList(null);
        while ($model->getReleaseVersion() . $model->getReleaseSuffix() != reset($versionList)) {
            // Skip unstable releases
            array_shift($versionList);
        }
        array_shift($versionList);
        $pastReleases = array();
        foreach ($versionList as $version) {
            $pastReleases[] = new ReleaseModel('ZendFramework', $version);
        }
        $this->view->pastReleases = $pastReleases;

        require_once dirname(dirname(__FILE__)) . '/models/ManualModel.php';
        $manualModel          = new ManualModel();
        $locales              = $manualModel->getLocales();

        $this->view->locales = $locales;
    }

    public function webservicesAction()
    {
        require_once dirname(dirname(__FILE__)) . '/models/ReleaseModel.php';
        $model = new ReleaseModel('ZendGdata');

        $this->view->current = $model;

        $versionList = $model->getVersionList();
        $currentVersion = $model->getReleaseVersion() . $model->getReleaseSuffix();
        while ($currentVersion != reset($versionList)) {
            // Skip unstable releases
            array_shift($versionList);
        }
        array_shift($versionList);
        $pastReleases = array();
        foreach ($versionList as $version) {
            $pastReleases[] = new ReleaseModel('ZendGdata', $version);
        }
        $this->view->pastReleases = $pastReleases;
    }
    
    public function infocardAction()
    {
        require_once dirname(dirname(__FILE__)) . '/models/ReleaseModel.php';
        $model = new ReleaseModel('ZendInfoCard');

        $this->view->current = $model;

        $versionList = $model->getVersionList();
        $currentVersion = $model->getReleaseVersion() . $model->getReleaseSuffix();
        while ($currentVersion != reset($versionList)) {
            // Skip unstable releases
            array_shift($versionList);
        }
        array_shift($versionList);
        $pastReleases = array();
        foreach ($versionList as $version) {
            $pastReleases[] = new ReleaseModel('ZendInfoCard', $version);
        }
        $this->view->pastReleases = $pastReleases;
    }
    
    public function amfAction()
    {
        require_once dirname(dirname(__FILE__)) . '/models/ReleaseModel.php';
        $model = new ReleaseModel('ZendAMF');

        $this->view->current = $model;

        $versionList = $model->getVersionList();
        $currentVersion = $model->getReleaseVersion() . $model->getReleaseSuffix();
        while ($currentVersion != reset($versionList)) {
            // Skip unstable releases
            array_shift($versionList);
        }
        array_shift($versionList);
        $pastReleases = array();
        foreach ($versionList as $version) {
            $pastReleases[] = new ReleaseModel('ZendAMF', $version);
        }
        $this->view->pastReleases = $pastReleases;
    }

    public function simplecloudAction()
    {
    }

    public function stableAction()
    {
        // Redirect to main download page
        $this->_helper->redirector('index');
    }

    public function gdataAction()
    {
        $this->_forward('webservices');
    }
    
    public function archivesAction()
    {
        require_once dirname(__FILE__) . '/../models/ReleaseModel.php';
        $model = new ReleaseModel('ZendFramework');

        $this->view->current = $model;

        $latestVersion = current($model->getVersionList());
        $this->view->latest = new ReleaseModel('ZendFramework', $latestVersion);

        $versionList = $model->getVersionList(null);
        while ($model->getReleaseVersion() . $model->getReleaseSuffix() != reset($versionList)) {
            // Skip unstable releases
            array_shift($versionList);
        }
        array_shift($versionList);
        $pastReleases = array();
        foreach ($versionList as $version) {
            $pastReleases[] = new ReleaseModel('ZendFramework', $version);
        }
        $this->view->pastReleases = $pastReleases;

        require_once dirname(dirname(__FILE__)) . '/models/ManualModel.php';
        $manualModel          = new ManualModel();
        $locales              = $manualModel->getLocales();

        $this->view->locales = $locales;
    }
}
