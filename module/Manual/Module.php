<?php

namespace Manual;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Manual\Controller\Page' => function ($sm) {
                $services = $sm->getServiceLocator();
                $resolver = $services->get('ViewResolver');
                $config   = $services->get('Config');
                $controller = new Controller\PageController();
                $controller->setResolver($resolver);
                $controller->setParams($config['zf_document_path']);
                return $controller;
            },
        ));
    }
    
}
