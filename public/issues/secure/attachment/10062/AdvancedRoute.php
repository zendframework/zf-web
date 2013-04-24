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
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 */

/** Zend_Controller_Router_Interface */
require_once 'Zend/Controller/Router/Route/Interface.php';

/**
 * Route
 *
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 * @see        http://manuals.rubyonrails.com/read/chapter/65
 */
class Custom_Controller_Router_AdvancedRoute implements Zend_Controller_Router_Route_Interface
{
    
    const URL_VARIABLE = ':';
    const REGEX_DELIMITER = '#';
    const DEFAULT_REGEX = '[a-z0-9\-\_\.\~\!\*\'\(\)\;\:\@\&\=\+\$\,\?\%\#\[\]]+';

    protected $_map;
    protected $_defaults;
    protected $_requirements;
    protected $_parts = false;
    protected $_regex = false;
    protected $_captureParams = false;

    /**
     * @param string Map used to match with later submitted URL path 
     * @param array Defaults for map variables with keys as variable names
     * @param array Regular expression requirements for variables (keys as variable names)
     */
    public function __construct($map, array $defaults = array(), array $requirements = array())
    {
        $this->_map          = trim($map, '/');
        $this->_defaults     = $defaults;
        $this->_requirements = $requirements;
    }
    
    /**
     * Prepares the route for mapping by splitting it into corresponding
     * atomic parts. These parts are used to assemble a regular expression
     * which is later used for matching values.  
     */
    public function parse()
    {
        $pattern = '#\{?(\:[a-z0-9_\x7f-\xff]+)\}?#i'; // map variables follow PHP variable naming conventions
        $this->_parts = preg_split($pattern, $this->_map, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        $this->_regex = self::REGEX_DELIMITER . '^';
        
        for ($i=0, $totalParts=count($this->_parts)-1; $i<=$totalParts; $i++) {
            $part = $this->_parts[$i];
            
            if ($part[0] === ':') { // $part is a variable
                $part = substr($part, 1);
                $this->_parts[$i] = array(
                    'type'  => 'var',
                    'name'  => $part,
                    'regex' => (isset($this->_requirements[$part])) ? $this->_requirements[$part] : self::DEFAULT_REGEX
                );
                $optionalFlag     = (array_key_exists($part, $this->_defaults)) ? '?' : '';
                $prevOptionalFlag = ($optionalFlag !== '' && $i > 0 && $this->_parts[$i-1]['name'] === '/') ? '?' : '';
            } else { // $part is a path
                if ($i === $totalParts && substr($part, -2) === '/*') {
                    $part = substr($part, 0, -2);
                    $this->_captureParams = true;
                }
                $this->_parts[$i] = array(
                    'type'  => 'path',
                    'name'  => $part,
                    'regex' => preg_quote($part, self::REGEX_DELIMITER)
                );
                $optionalFlag     = '';
                $prevOptionalFlag = '';
            }
            
            $this->_regex .= $prevOptionalFlag . '(' . $this->_parts[$i]['regex'] . ')' . $optionalFlag;
        }
        
        if ($this->_captureParams === true) {
            $this->_parts[$i]['type'] = 'var';
            $this->_parts[$i]['name'] = '*';
            $this->_regex .= '/?(.*)';
        }
        
        $this->_regex .= '$' . self::REGEX_DELIMITER;
    }
    
    /**
     * Matches a user submitted path with parts defined by a map. Assigns and 
     * returns an array of variables on a succesfull match.  
     *
     * @param string Path used to match against this routing map 
     * @return array|false An array of assigned values or a false on a mismatch
     */
    public function match($path)
    {
        $path = trim($path, '/');
        $out = $this->_defaults;
        
        // Return $out if we have an exact match. No need for further processing.
        if ($path === $this->_map) return $out;
        
        if ($this->_regex === false) self::parse();
        
        if (preg_match_all($this->_regex, $path, $matches, PREG_SET_ORDER)) {
            for ($i=1, $totalMatches=count($matches[0]); $i<$totalMatches; $i++) {
                if ($this->_parts[$i-1]['type'] === 'var' && $matches[0][$i] !== '') {
                    $out[$this->_parts[$i-1]['name']] = $matches[0][$i];
                }
            }
            if (array_key_exists('*', $out)) {
                $params = explode('/', rtrim($out['*'], '/'));
                while ($key = current($params)) {
                    $out[$key] = next($params);
                    next($params);
                }
            }
            return $out;
        }
        return false;
    }
    
    /**
     * Assembles user submitted parameters forming a URL path defined by this route 
     *
     * @param array An array of variable and value pairs used as parameters 
     * @return string Route path with user submitted parameters
     */
    public function assemble($data = array())
    {
        if ($this->_parts === false) self::parse();
        
        $out = '';
        
        foreach ($this->_parts as $part) {
            if ($part['name'] === '*') continue;
            if ($part['type'] === 'var') {
                if (isset($data[$part['name']])) {
                    $out .= $data[$part['name']];
                } elseif (array_key_exists($part['name'], $this->_defaults)) {
                    $out .= $this->_defaults[$part['name']];
                } else {
                    throw new Zend_Controller_Router_Exception($part['name'] . ' is not specified');
                }
            } else {
                $out .= $part['name'];
            }
        }
        return rtrim($out, '/');
    }
}