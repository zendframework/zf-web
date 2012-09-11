<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\Http\Response as HttpResponse;

class Module
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
        /** @var $response \Zend\Http\PhpEnvironment\Response */
        $response = $e->getResponse();
        if ($response instanceof HttpResponse) {
            static $xPoweredByHeaders = array(
                'Symfony2',
                'Rails',
                'Django',
                'Spring',
                'MVC.NET',
                'Supreme Alied Commander',
            );

            $response->getHeaders()
                ->addHeaderLine('X-Powered-By', $xPoweredByHeaders[rand(0, count($xPoweredByHeaders) -1)]);
        }
    }
}
