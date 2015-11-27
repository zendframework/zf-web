<?php

namespace Downloads;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

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
            'Downloads\Model\Release' => function ($services) {
                $config = $services->get('Config');
                if (!isset($config['downloads'])) {
                    throw new ServiceNotCreatedException(sprintf(
                        'Unable to create instance of %s; missing top-level "%s" configuration key',
                        __NAMESPACE__ . '\Model\ReleaseModel',
                        'downloads'
                    ));
                }
                $config = $config['downloads'];

                if (!isset($config['release_base_path'])) {
                    throw new ServiceNotCreatedException(sprintf(
                        'Unable to create instance of %s; missing "%s" configuration key',
                        __NAMESPACE__ . '\Model\ReleaseModel',
                        'release_base_path'
                    ));
                }
                if (!isset($config['versions'])) {
                    throw new ServiceNotCreatedException(sprintf(
                        'Unable to create instance of %s; missing "%s" configuration key',
                        __NAMESPACE__ . '\Model\ReleaseModel',
                        'versions'
                    ));
                }
                if (!isset($config['manual_languages'])) {
                    throw new ServiceNotCreatedException(sprintf(
                        'Unable to create instance of %s; missing "%s" configuration key',
                        __NAMESPACE__ . '\Model\ReleaseModel',
                        'manual_languages'
                    ));
                }
                if (!isset($config['products'])) {
                    throw new ServiceNotCreatedException(sprintf(
                        'Unable to create instance of %s; missing "%s" configuration key',
                        __NAMESPACE__ . '\Model\ReleaseModel',
                        'products'
                    ));
                }

                $model = new Model\ReleaseModel(
                    $config['release_base_path'],
                    $config['versions'],
                    $config['manual_languages'],
                    $config['products']
                );

                return $model;
            },
        ));
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Downloads\Controller\Downloads' => function ($controllers) {
                $services   = $controllers->getServiceLocator();
                $model      = $services->get('Downloads\Model\Release');
                $controller = new Controller\DownloadsController();
                $controller->setReleasesModel($model);
                return $controller;
            },
        ));
    }
}
