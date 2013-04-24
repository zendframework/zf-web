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
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @version    $Id: Hostname.php 20096 2010-01-06 02:05:09Z bkarwin $
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Controller_Router_Route_Interface */
require_once 'Zend/Controller/Router/Route/Abstract.php';

/**
 * RequestParam route.
 * 
 * This router is very much identical to the default Zend_Controller_Router_Route, but
 * accepts additional RequestVars to set the controller, action etc.
 * This makes it possible to create Zend_Rest_Client compatible urls for example, such as:
 * new Zend_Controller_Router_Router_RequestVars(":controller", array("action"=>"method"));
 * Which leads to server.com/product?method=find style urls.
 * 
 * Inspired by http://akrabat.com/zend-framework/zend-framework-urls-without-mod_rewrite/
 *
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2010 Ivo Jansch (ivo@ibuildings.com)
 * @copyright  Copyright (c) 2008 Rob Allen (rob@akrabat.com)
 */
class Zend_Controller_Router_Route_RequestVars extends Zend_Controller_Router_Route
{
    protected $_variableMap = array();
    protected $_paramValues = array();
    
     /**
     * Prepares the route for mapping by splitting (exploding) it
     * to a corresponding atomic parts. These parts are assigned
     * a position which is later used for matching and preparing values.
     *
     * @param string $route Map used to match with later submitted URL path
     * @param array $variableMap A mapping that maps variables to request parameters
     * @param array $defaults Defaults for map variables with keys as variable names
     * @param array $reqs Regular expression requirements for variables (keys as variable names)
     * @param Zend_Translate $translator Translator to use for this instance
     */
    public function __construct($route, $variableMap = array(), $defaults = array(), $reqs = array(), Zend_Translate $translator = null, $locale = null)    
    {
        parent::__construct($route, $defaults, $reqs, $translator, $locale);
        
        $this->_variableMap = $variableMap;
    }
    
    public function getVersion() {
        return 2;
    }

    /**
     * Matches a user submitted path with a previously defined route.
     * Assigns and returns an array of defaults on a successful match.
     *
     * @param string  $request used to match against this routing map
     * @return array|false An array of assigned values or a false on a mismatch
     */
    public function match($request)
    {        
        // This is in here so that the router doesn't break when Route_Abstract gets 
        // upped to version 2 and accepts $request objects. (after that happens, this
        // code may be removed
        if (parent::getVersion() == 1) {
          $match = $request->getPathInfo();
        } else {
          $match = $request;
        }
        
        $result = parent::match($match);
        
        $params = $request->getParams();
        
        $map = $this->_variableMap;
        
        // If certain map elements weren't specified, we look for their regular ?controller= names
        if (!isset($map["controller"])) $map["controller"] = "controller";
        if (!isset($map["module"])) $map["module"] = "module";
        if (!isset($map["action"])) $map["action"] = "action";
          
        foreach ($map as $mvcParam => $urlParam) {
         
            if (isset($params[$urlParam])) { 
                $result[$mvcParam] = $params[$urlParam];
                
                // save for later assembly
                $this->_paramValues[$urlParam] = $params[$urlParam];
            } else {
                // maybe we got it from the regular $route (first param to constructor)
                if (!isset($result[$mvcParam])) {
                    // This route isn't satisfied because not all vars were passed.
                    return false;
                }
            }
        }
        
        return $result;
    }

    /**
     * Assembles user submitted parameters forming a URL path defined by this route
     *
     * @param  array $ An array of variable and value pairs used as parameters
     * @param  boolean $reset Whether or not to set route defaults with those provided in $data
     * @return string Route path with user submitted parameters
     */
    public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
    {
        // TODO: We should take the paramValues/mvcParams into account shouldn't we? For now
        // This router doesn't support full reverse assembly I guess.
        return parent::assemble($data, $reset, $encode, $partial); 
    } 
}

