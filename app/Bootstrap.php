<?php

// User model might exist in the session, so require it
require_once dirname(__FILE__) . '/models/User.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /*
    public function run()
    {
    }
     */

    protected function _initAutoloader()
    {
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
    }

    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }

    protected function _initViewHelpers()
    {
        $this->bootstrap('layout');
        $view = $this->getResource('layout')->getView();
        Zend_Dojo::enableView($view);
        $view->addScriptPath(APPLICATION_PATH . '/views/scripts');
        $view->addScriptPath(APPLICATION_PATH . '/views/layouts');
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers');
        $view->doctype('XHTML1_STRICT');
        $view->headTitle()->setSeparator(': ')->append('Zend Framework');
        $view->placeholder('sub-nav')
             ->setPrefix('<div class="sub-nav"><ul>')
             ->setPostfix('</ul></div>');

        $options = $this->getOption('dojo');
        $view->dojo()->enable()
                     ->addJavascript($view->render('javascript.js'));
        if (isset($options['cdnVersion'])) {
            $view->dojo()->setCdnBase(Zend_Dojo::CDN_BASE_GOOGLE)
                         ->setCdnDojoPath(Zend_Dojo::CDN_DOJO_PATH_GOOGLE)
                         ->setCdnVersion($options['cdnVersion'])
                         ->setDjConfigOption('useXDomain', true)
                         ->setDjConfigOption('modulePaths', $options['modules']);
        } else {
            $view->dojo()->setLocalPath($options['localPath']);
            foreach ($options['modules'] as $key => $value) {
                $view->dojo()->registerModulePath($key, $value);
            }
        }
        foreach ($options['layers'] as $layer) {
            $view->dojo()->addLayer($layer);
        }
        foreach ($options['requires'] as $value) {
            if (empty($value)) {
                continue;
            }
            $view->dojo()->requireModule($value);
        }

        // get CSS options
        $options = $this->getOptions();
        if ($this->hasOption('css')) {
            $view->assign('css', $this->getOption('css'));
        }

        Zend_Registry::set('view', $view);
    }

    protected function _initSifr()
    {
        $this->bootstrap('Config');
        $this->bootstrap('FrontController');
        $this->bootstrap('Layout');

        $config = $this->getResource('config');
        if ($config->dynamicheader) {
            ZfWeb_DynamicHeader::startMvc($config->dynamicheader->toArray());
        }
    }

    protected function _initRouter()
    {
        $this->bootstrap('FrontController');
        $front  = $this->getResource('FrontController');
        $router = $front->getRouter();

        $router->addRoute(
            'home',
            new Zend_Controller_Router_Route_Static('home', array(
                'controller' => 'index',
                'action'     => 'index'
            ))
        );
        $router->addRoute(
            'license',
            new Zend_Controller_Router_Route('license/:version', array(
                'controller' => 'license',
                'action'     => 'view',
                'version'    => 'new-bsd'
            ))
        );
        $router->addRoute(
            'faq',
            new Zend_Controller_Router_Route('faq/:section', array(
                'controller' => 'faq',
                'action'     => 'index',
                'section'    => 'contributing'
            ))
        );
        $router->addRoute(
            'changelog',
            new Zend_Controller_Router_Route(
                'changelog/:version', 
                array(
                    'controller' => 'changelog',
                    'action'     => 'view',
                ),
                array(
                    'version' => '\d+\.\d+\.\d+',
                )
            )
        );
        $router->addRoute(
            'roadmap',
            new Zend_Controller_Router_Route(
                'roadmap/:version', 
                array(
                    'controller' => 'roadmap',
                    'action'     => 'index',
                ),
                array(
                    'version' => '\d+\.\d+(\.\d+)?',
                )
            )
        );
        $router->addRoute(
            'manual',
            new Zend_Controller_Router_Route('manual/:language/:page', array(
                'controller' => 'manual',
                'action'     => 'manual',
                'page'       => 'manual.html'
            ))
        );
        $router->addRoute(
            'manual-versioned',
            new Zend_Controller_Router_Route(
                'manual/:version/:language/:page', 
                array(
                    'controller' => 'manual',
                    'action'     => 'manual',
                    'page'       => 'manual.html'
                ),
                array(
                    'version' => '(\d+\.\d+|current)',
                )
            )
        );
        $router->addRoute(
            'manual-index',
            new Zend_Controller_Router_Route_Static('manual/index', array(
                'controller' => 'manual',
                'action'     => 'index'
            ))
        );
        $router->addRoute(
            'manual-components',
            new Zend_Controller_Router_Route_Static('manual/components', array(
                'controller' => 'manual',
                'action'     => 'components'
            ))
        );
        $router->addRoute(
            'manual-apidoc',
            new Zend_Controller_Router_Route_Static('manual/api', array(
                'controller' => 'manual',
                'action'     => 'api'
            ))
        );
        $router->addRoute(
            'manual-videos',
            new Zend_Controller_Router_Route_Static('manual/videos', array(
                'controller' => 'manual',
                'action'     => 'videos'
            ))
        );
        $router->addRoute(
            'manual-status',
            new Zend_Controller_Router_Route_Static('manual/status', array(
                'controller' => 'manual',
                'action'     => 'status'
            ))
        );
        $router->addRoute(
            'manual-search',
            new Zend_Controller_Router_Route_Static('manual/search', array(
                'controller' => 'manual',
                'action'     => 'search'
            ))
        );
        $router->addRoute(
            'manual-comment',
            new Zend_Controller_Router_Route('manual/comment/:section', array(
                'controller' => 'manual',
                'action'     => 'comment',
            ))
        );
        $router->addRoute(
            'manual-administer-comment',
            new Zend_Controller_Router_Route('manual/administer-comment/:subaction/:filter/:page', array(
                'controller' => 'manual',
                'action'     => 'administer-comment',
                'subaction'  => 'list',
                'filter'     => 'all',
                'page'       => 1
            ))
        );
        $router->addRoute(
            'behind-the-site',
            new Zend_Controller_Router_Route_Static(
                'behind-the-site',
                array(
                    'controller' => 'sitemap',
                    'action'     => 'behind-the-site',
                )
            )
        );
        $router->addRoute(
            'xml-namespaces',
            new Zend_Controller_Router_Route_Regex(
                'xml/(.*)',
                array(
                    'module'     => 'default',
                    'controller' => 'xml',
                    'action'     => 'namespace',
                ),
                array(
                    1 => 'namespace',
                )
            )
        );
        $router->addRoute(
            'security',
            new Zend_Controller_Router_Route(
                'security/advisory/:report', 
                array(
                    'controller' => 'security',
                    'action'     => 'advisory',
                ),
                array(
                    'report' => 'ZF\d{4}-\d{2}',
                )
            )
        );
        $router->addRoute(
            'blog-entry',
            new Zend_Controller_Router_Route(
                'zf2/blog/entry/:entry',
                array(
                    'module' => 'zf2',
                    'controller' => 'blog',
                    'action' => 'entry',
                ),
                array(
                    'entry' => '[a-zA-Z0-9_-]+',
                )
            )
        );

        return $router;
    }
}
