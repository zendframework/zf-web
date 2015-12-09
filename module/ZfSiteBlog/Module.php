<?php

namespace ZfSiteBlog;

use Zend\Config\Config;
use Zend\Console\Console;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\View\Helper as ViewHelper;
use Zend\View\HelperPluginManager;
use Zend\View\Model;

class Module implements ConfigProviderInterface, ServiceProviderInterface,
                        ViewHelperProviderInterface
{
    protected static $layout;
    protected $config;

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array('initializers' => array(
            function ($instance, $services) {
                if (!Console::isConsole()) {
                    return;
                }
                if (!$instance instanceof HelperPluginManager) {
                    return;
                }
                $instance->setFactory('basepath', function ($sm) use ($services) {
                    $config = $services->get('Config');
                    $config = $config['view_manager'];
                    $basePathHelper = new ViewHelper\BasePath;
                    $basePath = '/';
                    if (isset($config['base_path'])) {
                        $basePath = $config['base_path'];
                    }
                    $basePathHelper->setBasePath($basePath);
                    return $basePathHelper;
                });
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
        $renderer = $services->get('BlogRenderer');
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

            foreach (array('sidebar', 'scripts') as $name) {
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
