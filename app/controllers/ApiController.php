<?php
class ApiController extends Zend_Controller_Action
{
    public function zfVersionAction()
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
        $this->view->content = $currentVersion;
        $this->render('content');
    }
}
