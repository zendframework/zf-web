<?php

namespace Security;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface,
                        ControllerProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'Security\Model\Advisory' => function ($services) {
                $config = $services->get('Config');
                if (!isset($config['security'])) {
                    throw new ServiceNotFoundException(sprintf(
                        'Unable to create %s; missing configuration key "security", with subkey "advisories"',
                        __NAMESPACE__ . '\Model\AdvisoryModel'
                    ));
                }
                $config = $config['security'];
                if (!isset($config['advisories']) || !is_array($config['advisories'])) {
                    throw new ServiceNotFoundException(sprintf(
                        'Unable to create %s; missing subkey "advisories"',
                        __NAMESPACE__ . '\Model\AdvisoryModel'
                    ));
                }

                $pageSize = isset($config['page_size']) ? $config['page_size'] : 10;

                $model = new Model\AdvisoryModel($config['advisories'], $pageSize);
                return $model;
            },
        ));
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Security\Controller\Advisory' => function ($controllers) {
                $services   = $controllers->getServiceLocator();
                $model      = $services->get('Security\Model\Advisory');
                $controller = new Controller\AdvisoryController();
                $controller->setAdvisoryModel($model);
                return $controller;
            },
        ));
    }
}
