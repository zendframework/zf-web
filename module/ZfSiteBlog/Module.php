<?php

namespace ZfSiteBlog;

use Zend\Config\Config;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\View\Http\ViewManager;
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

    public function getServiceConfig()
    {
        // If we're in the console environment, we need to force usage
        // of the HTTP environment to ensure our routing and view 
        // usage is consistent with the site while generating the
        // static blog files.

        if (!defined('ZFSITE_CONSOLE') || !constant('ZFSITE_CONSOLE')) {
            return array();
        }

        return array('factories' => array(
            'request' => function ($services) {
                return new Request();
            },
            'response' => function ($services) {
                return new Response();
            },
            'router' => function ($services) {
                $config       = $services->get('Configuration');
                $routerConfig = isset($config['router']) ? $config['router'] : array();
                $router       = TreeRouteStack::factory($routerConfig);
                return $router;
            },
            'viewmanager' => function ($services) {
                return new ViewManager();
            },
        ));
    }

    public function getViewHelperConfig()
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
            $layout->setVariable('single', false);

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

            $headMeta = $renderer->plugin('headMeta');
            $headMeta->getContainer()->exchangeArray(array());

            foreach (array('top-nav', 'sidebar', 'scripts') as $name) {
                $placeholder = $renderer->placeholder($name);
                $placeholder->exchangeArray(array());
            }
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
