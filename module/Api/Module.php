<?php

namespace Api;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

class Module implements ConfigProviderInterface, BootstrapListenerInterface,
                        ServiceProviderInterface, ControllerProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getTarget();
        $events = $app->getEventManager();
        $services = $app->getServiceManager();
        $events->attach('route', function ($e) use ($services) {
            $matches = $e->getRouteMatch();
            $controller = $matches->getParam('controller');
            if ($controller != 'Api\UserController') {
                return;
            }
            $view = $services->get('View');
            $jsonStrategy = $services->get('ViewJsonStrategy');
            $view->getEventManager()->attach($jsonStrategy, 100);
        }, -10);
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'Api\UserStorage' => function ($services) {
                $config = $services->get('Config');
                if (!isset($config['api'])
                    || !isset($config['api']['cla_users_path'])
                ) {
                    throw new ServiceNotCreatedException('Missing configuration for CLA user data path');
                }
                $path = $config['api']['cla_users_path'];
                try {
                    $storage = new UserStorage($path);
                } catch (\InvalidArgumentException $e) {
                    throw new ServiceNotCreatedException('Invalid path configuration for CLA user data path', $e->getCode(), $e);
                }
                return $storage;
            },
            'Api\UserService' => function ($services) {
                $storage = $services->get('Api\UserStorage');
                return new UserService($storage);
            }
        ));
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Api\UserController' => function ($controllers) {
                $services   = $controllers->getServiceLocator();
                $controller = new UserController();
                $controller->setUserService($services->get('Api\UserService'));
                return $controller;
            }
        ));
    }
}
