<?php

namespace Changelog;

use Zend\ServiceManager\Exception\ServiceNotCreatedException;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array('namespaces' => array(
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
            ))
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Changelog\Controller\Changelog' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                $config   = $services->get('Config');
                if (!isset($config['changelog']) || !isset($config['changelog']['file'])) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; missing configuration'
                    );
                }
                $data     = include $config['changelog']['file'];
                if (!$data || !is_array($data)) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; invalid or missing changelog data'
                    );
                }

                $model    = $services->get('Downloads\Model\Release');
                $controller = new Controller\ChangelogController();
                $controller->setChangelogData($data);
                $controller->setReleasesModel($model);
                return $controller;
            },
        ));
    }
}
