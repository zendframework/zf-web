<?php

namespace ZfSiteBlog;

use Zend\Config\Config;
use Zend\Mvc\ModuleRouteListener;
use Zend\Stdlib\ArrayUtils;
use Zend\View\Model;

class Module
{
    protected static $layout;
    protected $config;

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

    public function getViewHelperConfiguration()
    {
        return array('factories' => array(
            'disqus' => function ($services) {
                $sm     = $services->getServiceLocator();
                $config = $sm->get('config');
                if ($config instanceof Config) {
                    $config = $config->toArray();
                }
                $config = $config['disqus'];
                return new View\Helper\Disqus($config);
            },
        ));
    }

    public function onBootstrap($e)
    {
        $app          = $e->getApplication();
        $services     = $app->getServiceManager();
        $events       = $app->getEventManager();
        $this->config = $services->get('config');
        $this->initAcls($e);
        $this->initView($e);

        $moduleRouteListener = new ModuleRouteListener();
        $events->attach($moduleRouteListener);
    }

    public static function prepareCompilerView($view, $config, $services)
    {
        $renderer = $services->get('ViewRenderer');
        $view->addRenderingStrategy(function($e) use ($renderer) {
            return $renderer;
        }, 100);

        self::$layout = $layout   = new Model\ViewModel();
        $layout->setTemplate('layout');
        $view->addResponseStrategy(function($e) use ($layout, $renderer) {
            $result = $e->getResult();
            $layout->setVariable('content', $result);
            $page   = $renderer->render($layout);
            $e->setResult($page);

            // Cleanup
            $headTitle = $renderer->plugin('headtitle');
            $headTitle->getContainer()->exchangeArray(array());
            $headTitle->append('Zend Framework');

            $headLink = $renderer->plugin('headLink');
            $headLink->getContainer()->exchangeArray(array());
            $headLink(array(
                'rel' => 'shortcut icon',
                'type' => 'image/vnd.microsoft.icon',
                'href' => '/images/Application/favicon.ico',
            ));

            $headScript = $renderer->plugin('headScript');
            $headScript->getContainer()->exchangeArray(array());
        }, 100);
    }

    public static function handleTagCloud($cloud, $view, $config, $locator)
    {
        if (!self::$layout) {
            return;
        }

        self::$layout->setVariable('footer', sprintf(
            "<h4>Tag Cloud</h4>\n<div class=\"cloud\">\n%s</div>\n",
            $cloud->render()
        ));
    }
}
