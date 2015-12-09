<?php

namespace Application;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\EventManager\EventInterface;
use Zend\Http\Response as HttpResponse;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
    implements ConsoleUsageProviderInterface, BootstrapListenerInterface,
               ControllerProviderInterface, ConfigProviderInterface
{
    public function onBootstrap(EventInterface $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'rotateXPoweredByHeader'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Application\Controller\Console' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                $config   = $services->get('Config');
                $config   = $config['console'];

                $controller = new Controller\ConsoleController();
                $controller->setConsole($services->get('Console'));
                $controller->setConfig($config);
                return $controller;
            }
        ));
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
            'github fetch contributors' => 'Fetch and cache list of contributors',
        );
    }

    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
    }

    /**
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function rotateXPoweredByHeader(MvcEvent $e)
    {
        $response = $e->getResponse();
        if (!$response instanceof HttpResponse) {
            return;
        }

        static $xPoweredByHeaders = array(
            'ASP.NET',
            'Django',
            'MVC.NET',
            'Play Framework',
            'Rails',
            'Spring',
            'Supreme Allied Commander',
            'Symfony2',
            'Zend Framework 2',
        );

        $value = $xPoweredByHeaders[rand(0, count($xPoweredByHeaders) -1)];

        $response->getHeaders()
                 ->addHeaderLine('X-Powered-By', $value);
    }
}
