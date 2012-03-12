<?php

class Zf2_StatusController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $bootstrap    = $this->getInvokeArg('bootstrap');
        $config       = $bootstrap->getResource('config');
        $cacheManager = $bootstrap->getResource('cachemanager');
        $cache        = $cacheManager->getCache('zfstatus');

        $git          = new Zfstatus_Service_Git($config->git->data_dir);
        $repos        = $git->getRepositories();
        $repoNames    = array_keys($repos);
        $repo         = array_shift($repoNames);

        $github       = new Zfstatus_Service_Github($cache);
        $service      = new Zfstatus_Service_Zf($github);
        $this->view->assign(array(
            'github'       => $github,
            'repo'         => $repos[$repo],
            'repoName'     => $repo,
            'zfService'    => $service,
        ));
    }
}
