<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */ 


/** Zend_Controller_Exception */
require_once 'Zend/Controller/Exception.php';

/** Zend_Controller_Front */
require_once 'Zend/Controller/Front.php';

/** Zend_Controller_Request_Abstract */
require_once 'Zend/Controller/Request/Abstract.php';

/** Zend_Controller_Response_Abstract */
require_once 'Zend/Controller/Response/Abstract.php';


/**
 * @category   Zend
 * @package    Zend_Controller
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Controller_Action
{
    /**
     * Array of arguments provided to the constructor, minus the 
     * {@link $_request Request object}.
     * @var array 
     */
    protected $_invokeArgs = array();

    /**
     * Front controller instance
     * @var Zend_Controller_Front
     */
    protected $_frontController;

    /**
     * HTTP status code for redirects
     * @var int
     */
    protected $_redirectCode = 302;

    /**
     * Whether or not calls to _redirect() should exit script execution
     * @var bool
     */
    protected $_redirectExit = true;

    /**
     * Whether or not _redirect() should attempt to prepend the base URL to the 
     * passed URL (if it's a relative URL)
     * @var bool
     */
    protected $_redirectPrependBase = true;

    /**
     * Zend_Controller_Request_Abstract object wrapping the request environment
     * @var Zend_Controller_Request_Abstract
     */
    protected $_request = null;

    /**
     * Zend_Controller_Response_Abstract object wrapping the response 
     * @var Zend_Controller_Response_Abstract
     */
    protected $_response = null;

    /**
     * View script suffix; defaults to 'phtml'
     * @see {render()}
     * @var string
     */
    public $viewSuffix = 'phtml';

    /**
     * View object
     * @var Zend_View_Interface
     */
    public $view;

    /**
     * Class constructor
     *
     * The request and response objects should be registered with the 
     * controller, as should be any additional optional arguments; these will be 
     * available via {@link getRequest()}, {@link getResponse()}, and 
     * {@link getInvokeArgs()}, respectively.
     *
     * When overriding the constructor, please consider this usage as a best 
     * practice and ensure that each is registered appropriately.
     *
     * Additionally, {@link init()} is called as the final action of 
     * instantiation, and may be safely overridden to perform initialization 
     * tasks; as a general rule, override {@link init()} instead of the 
     * constructor to customize an action controller's instantiation.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs Any additional invocation arguments
     * @return void
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->setRequest($request)
             ->setResponse($response)
             ->_setInvokeArgs($invokeArgs)
             ->init();
    }

    /**
     * Initialize object
     *
     * Called from {@link __construct()} as final step of object instantiation. 
     * 
     * @return void
     */
    public function init()
    {
    }

    /**
     * Initialize View object 
     *
     * Initializes {@link $view} if not otherwise a Zend_View_Interface.
     *
     * If {@link $view} is not otherwise set, instantiates a new Zend_View 
     * object, using the 'views' subdirectory at the same level as the 
     * controller directory for the current module as the base directory. 
     * It uses this to set the following:
     * - script path = views/scripts/
     * - helper path = views/helpers/
     * - filter path = views/filters/
     * 
     * @return Zend_View_Interface
     * @throws Zend_Controller_Exception if base view directory does not exist
     */
    public function initView()
    {
        require_once 'Zend/View/Interface.php';
        if (isset($this->view) && ($this->view instanceof Zend_View_Interface)) {
            return $this->view;
        }

        $request = $this->getRequest();
        $module  = $request->getModuleName();
        $dirs    = $this->getFrontController()->getControllerDirectory();
        if (empty($module) || !isset($dirs[$module])) {
            $module = 'default';
        }
        $baseDir = dirname($dirs[$module]) . DIRECTORY_SEPARATOR . 'views';
        if (!file_exists($baseDir) || !is_dir($baseDir)) {
            throw new Zend_Controller_Exception('Missing base view directory ("' . $baseDir . '")');
        }

        require_once 'Zend/View.php';
        $this->view = new Zend_View(array(
            'scriptPath' => $baseDir . DIRECTORY_SEPARATOR . 'scripts',
            'helperPath' => $baseDir . DIRECTORY_SEPARATOR . 'helpers',
            'filterPath' => $baseDir . DIRECTORY_SEPARATOR . 'filters'
        ));

        return $this->view;
    }

    /**
     * Render a view
     *
     * Renders a view. By default, views are found in the view script path as
     * <controller>/<action>.phtml. You may change the script suffix by 
     * resetting {@link $viewSuffix}. You may omit the controller directory
     * prefix by specifying boolean true for $noController.
     *
     * By default, the rendered contents are appended to the response. You may 
     * specify the named body content segment to set by specifying a $name.
     *
     * @see Zend_Controller_Response_Abstract::appendBody()
     * @param string|null $action 
     * @param string|null $name 
     * @param boolean $noController 
     * @return void
     * @throws Zend_Controller_Exception with bad $action
     */
    public function render($action = null, $name = null, $noController = false)
    {
        $view = $this->initView();
        $this->getResponse()->appendBody(
            $view->render($this->getViewScript($action, $noController)),
            $name
        );
    }
    
    /**
     * Detect the view script
     * 
     * Detects the view script. By default, views are found in the view script path as
     * <controller>/<action>.phtml. You may change the script suffix by 
     * resetting {@link $viewSuffix}. You may omit the controller directory
     * prefix by specifying boolean true for $noController.
     * 
     * @param string|null $action 
     * @param boolean $noController
     * @return string The view script
     * @throws Zend_Controller_Exception with bad $action
     */
    public function getViewScript($action = null, $noController = false) 
    {
        if (null === $action) {
            $action = $this->getRequest()->getActionName();
        } elseif (!is_string($action)) {
            throw new Zend_Controller_Exception('Invalid action specifier for view render');
        }

        $script = $action . '.' . $this->viewSuffix;

        if (!$noController) {
            $script = $this->getRequest()->getControllerName() . DIRECTORY_SEPARATOR . $script;
        }
        
        return $script;
    }

    /**
     * Return the Request object
     * 
     * @return Zend_Controller_Request_Abstract
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Set the Request object
     * 
     * @param Zend_Controller_Request_Abstract $request 
     * @return Zend_Controller_Action
     */
    public function setRequest(Zend_Controller_Request_Abstract $request)
    {
        $this->_request = $request;
        return $this;
    }

    /**
     * Return the Response object
     * 
     * @return Zend_Controller_Response_Abstract
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * Set the Response object
     * 
     * @param Zend_Controller_Response_Abstract $response 
     * @return Zend_Controller_Action
     */
    public function setResponse(Zend_Controller_Response_Abstract $response)
    {
        $this->_response = $response;
        return $this;
    }

    /**
     * Set invocation arguments
     * 
     * @param array $args 
     * @return Zend_Controller_Action
     */
    protected function _setInvokeArgs(array $args = array())
    {
        $this->_invokeArgs = $args;
        return $this;
    }

    /**
     * Return the array of constructor arguments (minus the Request object)
     * 
     * @return array
     */
    public function getInvokeArgs()
    {
        return $this->_invokeArgs;
    }

    /**
     * Return a single invocation argument
     * 
     * @param string $key 
     * @return mixed
     */
    public function getInvokeArg($key)
    {
        if (isset($this->_invokeArgs[$key])) {
            return $this->_invokeArgs[$key];
        }

        return null;
    }

    /**
     * Set the front controller instance
     * 
     * @param Zend_Controller_Front $front 
     * @return Zend_Controller_Action
     */
    public function setFrontController(Zend_Controller_Front $front)
    {
        $this->_frontController = $front;
        return $this;
    }

    /**
     * Retrieve Front Controller
     *
     * @return Zend_Controller_Front
     */
    public function getFrontController()
    {
        // Used cache version if found
        if (null !== $this->_frontController) {
            return $this->_frontController;
        }

        // Grab singleton instance, if class has been loaded
        if (class_exists('Zend_Controller_Front')) {
            $this->_frontController = Zend_Controller_Front::getInstance();
            return $this->_frontController;
        }

        // Throw exception in all other cases
        require_once 'Zend/Controller/Exception.php';
        throw new Zend_Controller_Exception('Front controller class has not been loaded');
    }


    /**
     * Retrieve HTTP status code to emit on {@link _redirect()} call
     * 
     * @return int
     */
    public function getRedirectCode()
    {
        return $this->_redirectCode;
    }

    /**
     * Validate HTTP status redirect code
     * 
     * @param int $code 
     * @return true
     */
    protected function _checkRedirectCode($code)
    {
        if (!is_int($code) || (300 > $code) || (307 < $code)) {
            require_once 'Zend/Controller/Exception.php';
            throw new Zend_Controller_Exception('Invalid redirect HTTP status code (' . $code  . ')');
        }

        return true;
    }

    /**
     * Retrieve HTTP status code for {@link _redirect()} behaviour
     * 
     * @param int $code 
     * @return Zend_Controller_Action
     */
    public function setRedirectCode($code)
    {
        $this->_checkRedirectCode($code);
        $this->_redirectCode = $code;
        return $this;
    }

    /**
     * Retrieve flag for whether or not {@link _redirect()} will exit when finished.
     * 
     * @return bool
     */
    public function getRedirectExit()
    {
        return $this->_redirectExit;
    }

    /**
     * Retrieve exit flag for {@link _redirect()} behaviour
     * 
     * @param bool $flag 
     * @return Zend_Controller_Action
     */
    public function setRedirectExit($flag)
    {
        $this->_redirectExit = ($flag) ? true : false;
        return $this;
    }

    /**
     * Retrieve flag for whether or not {@link _redirect()} will prepend the 
     * base URL on relative URLs
     * 
     * @return bool
     */
    public function getRedirectPrependBase()
    {
        return $this->_redirectPrependBase;
    }

    /**
     * Retrieve 'prepend base' flag for {@link _redirect()} behaviour
     * 
     * @param bool $flag 
     * @return Zend_Controller_Action
     */
    public function setRedirectPrependBase($flag)
    {
        $this->_redirectPrependBase = ($flag) ? true : false;
        return $this;
    }

    /**
     * Pre-dispatch routines
     *
     * Called before action method. If using class with 
     * {@link Zend_Controller_Front}, it may modify the 
     * {@link $_request Request object} and reset its dispatched flag in order 
     * to skip processing the current action.
     * 
     * @return void
     */
    public function preDispatch()
    {
    }

    /**
     * Post-dispatch routines
     *
     * Called after action method execution. If using class with 
     * {@link Zend_Controller_Front}, it may modify the 
     * {@link $_request Request object} and reset its dispatched flag in order 
     * to process an additional action.
     *
     * Common usages for postDispatch() include rendering content in a sitewide 
     * template, link url correction, setting headers, etc.
     * 
     * @return void
     */
    public function postDispatch()
    {
    }

    /**
     * Proxy for undefined methods.  Default behavior is to throw an
     * exception on undefined methods, however this function can be
     * overridden to implement magic (dynamic) actions, or provide run-time 
     * dispatching.
     *
     * @param string $methodName
     * @param array $args
     */
    public function __call($methodName, $args)
    {
        if (empty($methodName)) {
            $msg = 'No action specified and no default action has been defined in __call() for '
                 . get_class($this);
        } else {
            $msg = get_class($this) . '::' . $methodName
                 .'() does not exist and was not trapped in __call()';
        }

        throw new Zend_Controller_Exception($msg);
    }

    /**
     * Dispatch the requested action
     * 
     * @param string $action Method name of action
     * @return void
     */
    public function dispatch($action)
    {
        $this->preDispatch();
        if ($this->getRequest()->isDispatched()) {
            // preDispatch() didn't change the action, so we can continue
            $this->$action();
            $this->postDispatch();
        }
    }

    /**
     * Call the action specified in the request object, and return a response
     *
     * Not used in the Action Controller implementation, but left for usage in 
     * Page Controller implementations. Dispatches a method based on the 
     * request.
     *
     * Returns a Zend_Controller_Response_Abstract object, instantiating one 
     * prior to execution if none exists in the controller.
     *
     * {@link preDispatch()} is called prior to the action, 
     * {@link postDispatch()} is called following it.
     *
     * @param null|Zend_Controller_Request_Abstract $request Optional request 
     * object to use
     * @param null|Zend_Controller_Response_Abstract $response Optional response 
     * object to use
     * @return Zend_Controller_Response_Abstract
     */
    public function run(Zend_Controller_Request_Abstract $request = null, Zend_Controller_Response_Abstract $response = null)
    {
        if (null !== $request) {
            $this->setRequest($request);
        } else {
            $request = $this->getRequest();
        }

        if (null !== $response) {
            $this->setResponse($response);
        }

        $action = $request->getActionName();
        if (empty($action)) {
            $action = 'index';
        }
        $action = $action . 'Action';

        $request->setDispatched(true);
        $this->dispatch($action);

        return $this->getResponse();
    }

    /**
     * Gets a parameter from the {@link $_request Request object}.  If the
     * parameter does not exist, NULL will be returned.
     *
     * If the parameter does not exist and $default is set, then
     * $default will be returned instead of NULL.
     *
     * @param string $paramName
     * @param mixed $default
     * @return mixed
     */
    final protected function _getParam($paramName, $default = null)
    {
        $value = $this->getRequest()->getParam($paramName);
        if ((null == $value) && (null !== $default)) {
            $value = $default;
        }

        return $value;
    }

    /**
     * Set a parameter in the {@link $_request Request object}.
     * 
     * @param string $paramName 
     * @param mixed $value 
     * @return Zend_Controller_Action
     */
    final protected function _setParam($paramName, $value)
    {
        $this->getRequest()->setParam($paramName, $value);

        return $this;
    }

    /**
     * Determine whether a given parameter exists in the 
     * {@link $_request Request object}.
     * 
     * @param string $paramName 
     * @return boolean
     */
    final protected function _hasParam($paramName)
    {
        return null !== $this->getRequest()->getParam($paramName);
    }

    /**
     * Return all parameters in the {@link $_request Request object}
     * as an associative array.
     *
     * @return array
     */
    final protected function _getAllParams()
    {
        return $this->getRequest()->getParams();
    }


    /**
     * Forward to another controller/action.
     *
     * It is important to supply the unformatted names, i.e. "article"
     * rather than "ArticleController".  The dispatcher will do the
     * appropriate formatting when the request is received.
     *
     * If only an action name is provided, forwards to that action in this 
     * controller.
     *
     * If an action and controller are specified, forwards to that action and 
     * controller in this module.
     *
     * Specifying an action, controller, and module is the most specific way to 
     * forward.
     *
     * A fourth argument, $params, will be used to set the request parameters. 
     * If either the controller or module are unnecessary for forwarding, 
     * simply pass null values for them before specifying the parameters.
     *
     * @param string $action
     * @param string $controller
     * @param string $module
     * @param array $params
     * @return void
     */
    final protected function _forward($action, $controller = null, $module = null, array $params = null)
    {
        $request = $this->getRequest();

        if (null !== $params) {
            $request->setParams($params);
        }

        if (null !== $controller) {
            $request->setControllerName($controller);

            // Module should only be reset if controller has been specified
            if (null !== $module) {
                $request->setModuleName($module);
            }
        }

        $request->setActionName($action)
                ->setDispatched(false);
    }


    /**
     * Redirect to another URL
     *
     * By default, emits a 302 HTTP status header, prepends base URL as defined 
     * in request object if url is relative, and halts script execution by 
     * calling exit().
     *
     * $options is an optional associative array that can be used to control 
     * redirect behaviour. The available option keys are:
     * - exit: boolean flag indicating whether or not to halt script execution when done
     * - prependBase: boolean flag indicating whether or not to prepend the base URL when a relative URL is provided
     * - code: integer HTTP status code to use with redirect. Should be between 300 and 307.
     *
     * _redirect() sets the Location header in the response object. If you set 
     * the exit flag to false, you can override this header later in code 
     * execution.
     *
     * If the exit flag is true (true by default), _redirect() will write and 
     * close the current session, if any.
     *
     * @param string $url
     * @param array $options Options to be used when redirecting
     * @return void
     */
    protected function _redirect($url, array $options = null)
    {
        // prevent header injections
        $url = str_replace(array("\n", "\r"), '', $url);

        $exit        = $this->getRedirectExit();
        $prependBase = $this->getRedirectPrependBase();
        $code        = $this->getRedirectCode();
        if (null !== $options) {
            if (isset($options['exit'])) {
                $exit = ($options['exit']) ? true : false;
            }
            if (isset($options['prependBase'])) {
                $prependBase = ($options['prependBase']) ? true : false;
            }
            if (isset($options['code'])) {
                $this->_checkRedirectCode($options['code']);
                $code = $options['code'];
            }
        }

        // If relative URL, decide if we should prepend base URL
        if ($prependBase && !preg_match('|^[a-z]+://|', $url)) {
            $request = $this->getRequest();
            if ($request instanceof Zend_Controller_Request_Http) {
                $base = $request->getBaseUrl();
                if (('/' != substr($base, -1)) && ('/' != substr($url, 0, 1))) {
                    $url = $base . '/' . $url;
                } else {
                    $url = $base . $url;
                }
            }
        }

        // Set response redirect
        $response = $this->getResponse();
        $response->setRedirect($url, $code);

        if ($exit) {
            // Close session, if started
            if (class_exists('Zend_Session', false) && Zend_Session::isStarted()) {
                Zend_Session::writeClose();
            } elseif (isset($_SESSION)) {
                session_write_close();
            }

            $response->sendHeaders();
            exit();
        }
    }
}
