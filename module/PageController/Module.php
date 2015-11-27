<?php

namespace PageController;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'PageController\Controller\Page' => function ($sm) {
                $services = $sm->getServiceLocator();
                $resolver = $services->get('ViewResolver');

                $controller = new Controller\PageController();
                $controller->setResolver($resolver);
                return $controller;
            },
        ));
    }
}
