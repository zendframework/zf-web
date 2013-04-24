<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to version 1.0 of the Zend Framework
 * license, that is bundled with this package in the file LICENSE, and
 * is available through the world-wide-web at the following URL:
 * http://www.zend.com/license/framework/1_0.txt. If you did not receive
 * a copy of the Zend Framework license and are unable to obtain it
 * through the world-wide-web, please send a note to license@zend.com
 * so we can mail you a copy immediately.
 *
 * @package    Zend_Controller
 * @subpackage Router
 * @copyright  Copyright (c) 2005-2006 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://www.zend.com/license/framework/1_0.txt Zend Framework License version 1.0
 */

/** Zend_Controller_Router_Exception */
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
class Zend_Controller_Router_Route implements Zend_Controller_Router_Route_Interface
{

    const URL_VARIABLE = ':';
    const REGEX_DELIMITER = '#';
    
    // TODO: allow for all types of URI characters (per RFC 3986)
    // http://en.wikipedia.org/wiki/URL_encoding
    const DEFAULT_REGEX = '[a-z0-9\-\._]';

    private $_map;
    private $_mapRegex;
    private $_mapKeys;
    private $_mapKeysOrig = array();
    
    private $_defaults;

    public function __construct($map, $defaults = array(), $requirements = array())
    {
        $this->_map      = trim($map, '/');
        $this->_defaults = $defaults;
        
        $pattern = self::REGEX_DELIMITER . '\\' . self::URL_VARIABLE
                 . '[a-z0-9\*]+?'
                 . '\\' . self::URL_VARIABLE . self::REGEX_DELIMITER . 'i';
        
        if (preg_match_all($pattern, $this->_map, $mapKeys)) {
            
            $mapKeys  = $mapKeys[0];
            $optional = false;
            
            foreach ($mapKeys as $i => $mapKey) {
                
                $mapKeyLookup = trim($mapKey, self::URL_VARIABLE . '*');
                $prevChar = substr($map, strpos($map, $mapKey)-1, 1);
                
                if ($mapKey[strlen($mapKey)-2] === '*') { // key is a wildcard ***POSSIBLE OPTIMIZATION: if (strlen($mapKey) - strlen($mapKeyLookup) === 3) ***
                    
                    $mapKey = str_replace('*', '\*', $mapKey);
                    
                    if (array_key_exists($mapKeyLookup, $defaults) || $optional) { // if the mapKey has a default set, it becomes an optional pattern to look for
                        
                        $optional = true;
                    
                        $mapPattern[] = self::REGEX_DELIMITER
                                      . $prevChar . $mapKey
                                      . self::REGEX_DELIMITER;
                        
                        $regex = (array_key_exists($mapKeyLookup, $requirements) ? $requirements[$mapKeyLookup] . '|' : '.*');
                        $mapReplacement[] = $prevChar . '{0,1}(' . $regex . ')';
                        
                    } else { // otherwise we require a / slash and a value to be present
                        
                        $mapPattern[] = self::REGEX_DELIMITER
                                      . $prevChar . $mapKey
                                      . self::REGEX_DELIMITER;
                        
                        $regex = (array_key_exists($mapKeyLookup, $requirements) ? $requirements[$mapKeyLookup] : '.+');
                        $mapReplacement[] = $prevChar . '(' . $regex . ')';
                    }
                    
                } else { // key is NOT a wildcard
                    
                    if (array_key_exists($mapKeyLookup, $defaults) || $optional) { // if the mapKey has a default set, it becomes an optional pattern to look for
                        
                        $optional = true;
                        
                        $mapPattern[] = self::REGEX_DELIMITER
                                      . $prevChar . $mapKey
                                      . self::REGEX_DELIMITER;
                                      
                        $regex = (array_key_exists($mapKeyLookup, $requirements) ? $requirements[$mapKeyLookup] . '|' : self::DEFAULT_REGEX  . '*');
                        $mapReplacement[] = $prevChar . '{0,1}(' . $regex . ')';
                        
                    } else { // otherwise we require a / slash and a value to be present
                        
                        $mapPattern[] = self::REGEX_DELIMITER
                                      . $mapKey
                                      . self::REGEX_DELIMITER;
                                      
                        $regex = (array_key_exists($mapKeyLookup, $requirements) ? $requirements[$mapKeyLookup] : self::DEFAULT_REGEX  . '+');
                        $mapReplacement[] = '(' . $regex . ')';
                    }
                }
                
                $this->_mapKeys[$i+1] = $mapKeyLookup;
                $this->_mapKeysOrig[] = $mapKey;
            }
            
            $this->_mapRegex = self::REGEX_DELIMITER
                             . '^' . preg_replace($mapPattern, $mapReplacement, str_replace('.', '\.', $this->_map)) . '$'
                             . self::REGEX_DELIMITER . 'i';
                             
        } else {
            $this->_mapRegex = self::REGEX_DELIMITER . '^' . $map . '$' . self::REGEX_DELIMITER;
        }
    }
    
    
    public function match($path)
    {
        $path = trim($path, '/');
        $out = $this->_defaults;
        
//        echo 'path: ', $path, "\n";
//        echo '_map: ', $this->_map, "\n";
//        echo '_mapRegex: ', $this->_mapRegex, "\n";
//        echo "\n";
        
        if (preg_match_all($this->_mapRegex, $path, $mapValues, PREG_SET_ORDER)) {
            $mapValues = $mapValues[0];
            for ($i=1; $i<count($mapValues); $i++) {
                $mapValues[$i] = ltrim($mapValues[$i], '/');
                if ($mapValues[$i] !== '') $out[$this->_mapKeys[$i]] = $mapValues[$i];
            }
//            echo 'returning $out', "\n";
//            print_r($out);
            return $out;
        }
//        echo 'returning false', "\n";
        return false;
    }


    public function assemble($data = array())
    {

        $out = array();
        $optional = false;
        
        foreach ($this->_mapKeys as $part) {
            if (isset($data[$part])) {
                $out[$part] = $data[$part];
            } elseif (isset($this->_defaults[$part])) {
                $out[$part] = $this->_defaults[$part];
            } else
                throw new Zend_Controller_Router_Exception($part . ' is not specified');
        }

        return str_replace($this->_mapKeysOrig, $out, $this->_map);

    }

}

?>