<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {




    protected function _initSession() {
        $session = new Zend_Session_Namespace( 'ipmcore' );
        Zend_Registry::set( 'session', $session );

        if (!isset($session->initialized)) {
            Zend_Session::regenerateId();
            $session->initialized = true;
        }

    }


    protected function _initView() {
        $options = $this->getOption('resources');
        if (isset($options['view'])) {
            $view = new Zend_View($options['view']);
        }
        else {
            $view = new Zend_View;
        }

        if (isset($options['view']['doctype'])) {
            $view->doctype($options['view']['doctype']);
        }
        if (isset($options['view']['contentType'])) {
            $view->headMeta()->appendHttpEquiv('Content-Type',
                    $options['view']['contentType']);
        }

        /**
         * Default Title
         */
        $view->headTitle('IPMCore')->setSeparator(' - ');

        $rev = $options['view']['version'];
        /**
         * JavaScript. Also see Layout.phtml in app/layouts
         */
        $view->headScript()
                ->appendFile('/js/jslibs.js', 'text/javascript')
                ->appendFile('/js/ipmc/ipmcore.scripts_' . $rev . '.js', 'text/javascript');

        /**
         * CSS. Also see Layout.phtml in app/layouts
         */
        $view->headLink()
                ->appendStylesheet('/css/print_' . $rev . '.css', 'print')
                ->appendStylesheet('/css/screen_' . $rev . '.css', 'screen, projection')
                ->appendStylesheet('/css/ie_' . $rev . '.css', 'screen, projection', 'IE')
                ->appendStylesheet('/css/ipmcore_' . $rev . '.css');


        if(APPLICATION_ENV != 'production')
            Zend_Registry::set('version', $options['view']['version']);

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'ViewRenderer'
        );
        $viewRenderer->setView($view);
        return $view;
    }

    protected function _initModifiedFrontController() {
        $options = $this->getOption('resources');

        if ( ! isset($options['modifiedFrontController']['contentType'])) {
            return;
        }
        $this->bootstrap('FrontController');
        $front = $this->getResource('FrontController');
        //$front->addModuleDirectory(APPLICATION_PATH . '/modules');

        $response = new Zend_Controller_Response_Http;
        $response->setHeader('Content-Type',
                $options['modifiedFrontController']['contentType'], true);
        $front->setResponse($response);
    }

    protected function _initRoutes() {

        $frontController = Zend_Controller_Front::getInstance();

        //get possible languages from config ini
        $availableLangs = $this->getOption('languages');

        //ensure that English language is in the list (required)
        $availableLangs['en_GB'] = 'en';

        //buld regex for route with languages list
        $regex =  '(' . implode('|', $availableLangs) .')' ;

        $router = $frontController->getRouter();
        $router->removeDefaultRoutes();

        $staticRoute = new Zend_Controller_Router_Route_Static(
                '/',
                array(
                        'module' => 'default',
                )
        );

        $adminRoute = new Zend_Controller_Router_Route_Static(
                '/admin',
                array(
                        'module' => 'admin',
                )
        );

        $langRoute = new Zend_Controller_Router_Route(
                '/:lang',
                array(
                        'lang' => '',
                ),
                array ('lang' => $regex)
        );

        $moduleControllerActionRoute = new Zend_Controller_Router_Route(
                ':controller/:action/*',
                array(
                        'controller' => 'index',
                        'action'     => 'index',
                )
        );

        $langModuleControllerActionRoute = new Zend_Controller_Router_Route(
                ':lang/:module/:controller/:action/*',
                array(
                        'module' => 'default',
                        'controller' => 'index',
                        'action'     => 'index',
                        'lang' => ''
                ),
                array ('lang' => $regex, 'module' => '[a-z]+' )
        );

        $router->addRoute('mca', $moduleControllerActionRoute);
        $router->addRoute('lmca', $langModuleControllerActionRoute);
        $router->addRoute('lang', $langRoute);
        $router->addRoute('admin', $adminRoute);
        $router->addRoute('stat', $staticRoute);


        /**
         * register action helpers
         */
        Zend_Controller_Action_HelperBroker::addPrefix(
                'IPMCore_Controller_Action_Helpers_');
        Zend_Controller_Action_HelperBroker::addHelper(
                new IPMCore_Controller_Action_Helpers_NavigationLoader());
        //this must be here as we pass current language and array of all langs to LangSelector helper
        Zend_Controller_Action_HelperBroker::addHelper(
                new IPMCore_Controller_Action_Helpers_LanguageLoader($availableLangs));

    }

    protected function _initLog() {
        /**
         * FirePHP
         */
        if ($this->getEnvironment() == 'development') {
            $writer = new Zend_Log_Writer_Firebug();
            $writer->setPriorityStyle(8, 'TABLE');
            $writer->setPriorityStyle(9, 'TRACE');
            $logger = new Zend_Log($writer);
            Zend_Registry::set('logger',$logger);
        }
    }


    protected function _initDoctrine() {
        //$this->getApplication()->getAutoloader()->registerNamespace('Doctrine')
        //->pushAutoloader(array('Doctrine', 'autoload'));

        $options = $this->getOption('doctrine');

        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(Doctrine_Core::ATTR_MODEL_CLASS_PREFIX, 'IPMCore_Model_');
        $manager->setAttribute(
                Doctrine_Core::ATTR_MODEL_LOADING,
                Doctrine_Core::MODEL_LOADING_PEAR
        );

        //$manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);

        $prefix = null;
        if(isset($options['database_prefix'])) $prefix = $options['database_prefix'];

        $dsn = $options['dns'] . $prefix . $options['database_main'];
        $conn = Doctrine_Manager::connection($dsn);
        $conn->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);
        return $conn;
    }

}