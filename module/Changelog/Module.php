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
                if (!isset($config['changelog'])) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; missing configuration'
                    );
                }

                $zf1data = include $config['changelog']['zf1_file'];
                if (!$zf1data || !is_array($zf1data)) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; invalid or missing ZF1 changelog data'
                    );
                }

                $zf2data = include $config['changelog']['zf2_file'];
                if (!$zf2data || !is_array($zf2data)) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; invalid or missing ZF2 changelog data'
                    );
                }

                $data = array_merge($zf1data, $zf2data);

                $controller = new Controller\ChangelogController();
                $controller->setChangelogData($data);
                return $controller;
            },
        ));
    }
}
