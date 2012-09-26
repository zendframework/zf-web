<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as HttpResponse;

class Module implements ConsoleUsageProviderInterface
{
    public function onBootstrap($e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

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
        $sharedEvents->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, array($this, 'rotateXPoweredByHeader'));
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
