<?php

namespace Manual;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;

class Module implements ConfigProviderInterface, ControllerProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Manual\Controller\Page' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                $resolver = $services->get('ViewResolver');
                $config   = $services->get('Config');

                $controller = new Controller\PageController(
                    $config['zf_latest_version'],
                    $config['zf1_latest_version']
                );
                $controller->setResolver($resolver);
                $controller->setApiDocVersions($config['zf_apidoc_versions']);
                $controller->setParams($config['zf_document_path']);
                return $controller;
            },
        ));
    }
}
