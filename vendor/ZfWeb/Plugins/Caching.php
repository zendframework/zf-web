<?php
/**
 * Full page content caching for framework site
 * 
 * @uses    Zend_Controller_Plugin_Abstract
 * @version $Id$
 */
class ZfWeb_Plugins_Caching extends Zend_Controller_Plugin_Abstract
{
    /**
     * Do Not Cache flag - set to true to disable caching
     * @var bool
     */
    public static $doNotCache = false;

    /**
     * Routes that should not be cached.
     *
     * Keys are controller names, values are arrays of actions that should not 
     * be cached; a null value for the actions means all actions for that 
     * controller should not be cached.
     * 
     * @var array
     */
    public $nonCacheableRoutes = array(
        'changelog' => null,
        'error'     => null,
        'manual'    => array('search', 'administer-comment'),
        'roadmap'   => null,
        'search'    => null,
        'xmlrpc'    => null,
    );

    /**
     * Special cases: cases where keys need to be generated specially.
     * @var array
     */
    public $specialCases = array(
        'manual' => array('actions' => array('manual'), 'method' => 'createManualKey'),
        'blog'   => array('actions' => array('entry', 'index'), 'method' => 'createBlogKey'),
    );

    /**
     * Cache identifier
     * @var string
     */
    public $key;

    /**
     * @var Zend_Cache
     */
    protected $_cache;

    /**
     * Determine whether we have a cached response, and, if so, immediately 
     * display it
     *
     * POST requests always result in "no cache" action.
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if ($request->isPost()) {
            self::$doNotCache = true;
            return;
        }

        $module     = $request->getModuleName();
        $controller = $request->getControllerName();
        $action     = $request->getActionName();
        if (empty($action)) {
            $action = 'index';
        }

        // Temporary hack until we can refactor caching
        // This was basically colliding with IndexController::indexAction in the
        // main app
        if ($module == 'zf2' && $controller == 'index' && $action == 'index') {
            return;
        }

        if (array_key_exists($controller, $this->nonCacheableRoutes)) {
            if ((null === $this->nonCacheableRoutes[$controller])
                || in_array($action, $this->nonCacheableRoutes[$controller])
            ) {
                self::$doNotCache = true;
                return;
            }
        } 
        
        $method = 'createKey';
        if (array_key_exists($controller, $this->specialCases)
                  && (
                      (null === $this->specialCases[$controller]['actions'])
                      || in_array($action, $this->specialCases[$controller]['actions'])
                     )
        ) {
            $method = $this->specialCases[$controller]['method'];
        }

        $this->$method($request);
        $this->loadCache();
    }

    /**
     * Load from cache or start caching
     * 
     * @return void
     */
    public function loadCache()
    {
        if (empty($this->key)) {
            return;
        }

        if (false !== ($content = $this->getCache()->load($this->key))) {
            $response = $this->getResponse();
            $response->setBody($content);
            $response->sendResponse();
            exit;
        }
    }

    /**
     * Create cache key
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function createKey(Zend_Controller_Request_Abstract $request)
    {
        $controller = $request->getControllerName();
        $action     = $request->getActionName();
        if (empty($action)) {
            $action = 'index';
        }
        $this->key = md5($controller . $action);
    }

    /**
     * Create cache key for manual
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function createManualKey(Zend_Controller_Request_Abstract $request)
    {
        $page = $request->getParam('page', 'index.html');
        $lang = $request->getParam('language', 'en');
        $this->key = md5('manual-' . $lang . '-' . $page);
    }

    /**
     * Create cache key for blog
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function createBlogKey(Zend_Controller_Request_Abstract $request)
    {
        $action = $request->getParam('action', 'index');

        switch ($action) {
            case 'index':
                $page = $request->getParam('page', 1);
                $key  = 'blog-list-' . $page;
                break;
            case 'entry':
                $entry = $request->getParam('entry', '');
                $key   = 'blog-entry-' . $entry;
                break;
            default:
                return;
        }
        $this->key = md5($key);
    }

    /**
     * Cache response
     * 
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        if (self::$doNotCache 
            || $this->getResponse()->isRedirect() 
            || (null === $this->key)
        ) {
            return;
        }

        $this->getCache()->save($this->getResponse()->getBody(), $this->key);
    }

    /**
     * Get cache object
     * 
     * @return Zend_Cache
     */
    public function getCache()
    {
        if (null === $this->_cache) {
            $front        = Zend_Controller_Front::getInstance();
            $bootstrap    = $front->getParam('bootstrap');
            $cacheManager = $bootstrap->getResource('cachemanager');
            $this->_cache = $cacheManager->getCache('content');
        }
        return $this->_cache;
    }
}
