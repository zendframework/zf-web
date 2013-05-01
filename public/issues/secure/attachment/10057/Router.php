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
 * @subpackage Router
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */ 


/** Zend_Controller_Router_Interface */
require_once 'Zend/Controller/Router/Interface.php';

/** Zend_Controller_Dispatcher_Interface */
require_once 'Zend/Controller/Dispatcher/Interface.php';

/** Zend_Controller_Router_Exception */
require_once 'Zend/Controller/Router/Exception.php';

/** Zend_Controller_Dispatcher_Token */
require_once 'Zend/Controller/Dispatcher/Token.php';


/**
 * Simple first implementation of a router, to be replaced
 * with rules-based URI processor.
 *
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Controller_Router implements Zend_Controller_Router_Interface
{

    public function route(Zend_Controller_Dispatcher_Interface $dispatcher)
    {
        /**
         * @todo Replace with Zend_Request object
         */
        $path = $_SERVER['REQUEST_URI'];
        if (strstr($path, '?')) {
            $path = substr($path, 0, strpos($path, '?'));
        }
        $path = explode('/', trim($path, '/'));

        /**
         * If the entrance is sub-directory, 
         * then Controller in the URI's position should be changed.
         *  
         * http://zend.com/sub-directory/controller-name/action-name/
         */
        $subDir      = str_replace($_SERVER["DOCUMENT_ROOT"], "", $_SERVER["SCRIPT_FILENAME"]);
        $subDir      = explode('/', trim($subDir, '/'));
		$subDirLevel = count($subDir)-1;

        /**
         * The controller is always the first piece of the URI, and
         * the action is always the second:
         *
         * http://zend.com/controller-name/action-name/
         */
        $controller = $path[$subDirLevel];
        $action     = isset($path[$subDirLevel+1]) ? $path[$subDirLevel+1] : null;

        /**
         * If no controller has been set, IndexController::index()
         * will be used.
         */
        if (!strlen($controller)) {
           $controller = 'index';
           $action = 'index';
        }

        /**
         * Any optional parameters after the action are stored in
         * an array of key/value pairs:
         *
         * http://www.zend.com/controller-name/action-name/param-1/3/param-2/7
         *
         * $params = array(2) {
         *              ["param-1"]=> string(1) "3"
         *              ["param-2"]=> string(1) "7"
         * }
         */
        $params = array();
        for ($i=$subDirLevel+2; $i<sizeof($path); $i=$i+2) {
            $params[$path[$i]] = isset($path[$i+1]) ? $path[$i+1] : null;
        }

        $actionObj = new Zend_Controller_Dispatcher_Token($controller, $action, $params);

        if (!$dispatcher->isDispatchable($actionObj)) {
            /**
             * @todo error handling for 404's
             */
            throw new Zend_Controller_Router_Exception('Request could not be mapped to a route.');
        } else {
            return $actionObj;
        }
    }
}