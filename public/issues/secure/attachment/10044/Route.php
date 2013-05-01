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
    const DEFAULT_REGEX = '[a-z0-9\-\_\.\~\%]+';

    protected $_parts = array();
    protected $_defaults;
    protected $_requirements;

    public function __construct($map, array $defaults = array(), array $requirements = array())
    {
        $this->_defaults     = $defaults;
        $this->_requirements = $requirements;
        
        $pattern = '#\{*?(\:[a-z0-9_\x7f-\xff]+)\}*#i'; // map vars follow similar naming rules to PHP $vars
        $mapParts = preg_split($pattern, trim($map, '/'), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        foreach ($mapParts as $mapPart) {
            if ($mapPart[0] === ':') { // mapPart is a variable
                $mapPart = ltrim($mapPart, ':');
                $this->_parts[] = array(
                    'type'  => 'var',
                    'name'  => $mapPart,
                    'regex' => (isset($requirements[$mapPart]) ? $requirements[$mapPart] : self::DEFAULT_REGEX));
            } else { // mapPart is part of the path
                $this->_parts[] = array(
                    'type'  => 'path',
                    'name'  => $mapPart,
                    'regex' => preg_quote($mapPart, self::REGEX_DELIMITER));
            }
        }
    }
    
    public function match($path)
    {
        $path = trim($path, '/'); $offset = 0;
        $out = $this->_defaults;
        
        for ($i=0, $totalParts=count($this->_parts); $i<$totalParts; $i++) {
            
            $part = $this->_parts[$i];
            
            if ($part['type'] === 'var') { // if the current part is a var try a regex match
                
                // if we're not at the last part, look ahead and see if the next part should be a required or optional match
                if ($i < $totalParts) {
                    $nextPart = $this->_parts[$i+1];
                    if ($nextPart['type'] === 'var') {
                        // since the next part is a var we can check if it has a default value or not
                        if (array_key_exists($nextPart['name'], $this->_defaults)) $optionalRegex = '|$';
                        else $optionalRegex = '';
                    } elseif ($nextPart['name'] === '/' && $i+1 < $totalParts &&
                              array_key_exists($this->_parts[$i+2]['name'], $this->_defaults)) {
                        // otherwise if the next part is a '/' path and the var after it
                        // has a default value it is also considered optional to match
                        $optionalRegex = '|$';
                    } else $optionalRegex = ''; // all other paths are a required match
                } else {
                    // since this is the last part there is nothing optional or required to match
                    $nextPart['regex'] = '';
                    $optionalRegex = '';
                }
                
                // the regex has to match the current part and the part after it
                // otherwise patterns like .* would match the rest of $path
                $regex = self::REGEX_DELIMITER
                       . '^(' . $part['regex'] . ')'
                       . '(' . $nextPart['regex'] . $optionalRegex . ')'
                       . self::REGEX_DELIMITER . 'i';
                
                // try to match the current and next parts of the map
                if (preg_match($regex, $path, $matches, PREG_OFFSET_CAPTURE)) {
                    $out[$part['name']] = $matches[1][0];
                    $offset = strlen($matches[1][0]);
                    // if the next part isn't blank we can process it now and skip over the next loop
                    if ($matches[2][0] !== '') {
                        // if the next part is a var, add it to the output array
                        if ($nextPart['type'] === 'var') {
                            $out[$nextPart['name']] = $matches[2][0];
                            $offset += strlen($matches[2][0]);
                        } else $offset += strlen($nextPart['name']); // otherwise it is a path and we can skip past it
                        $i++;
                    }
                } elseif (array_key_exists($part['name'], $this->_defaults) && !isset($this->_requirements[$part['name']])) {
                    // if the preg_match wasn't successful try to use
                    // the default value for this part and keep looping
                    continue;
                } else return false; // this route doesn't match
                
            } else { // if the current part is a path try a string comparison
                $nameLength = strlen($part['name']);
                if ($part['name'] === substr($path, $offset, $nameLength)) {
                    $offset = $nameLength;
                } elseif ($part['name'] === '/' && $i < $totalParts &&
                          array_key_exists($this->_parts[$i+1]['name'], $this->_defaults)) {
                    // if the current part is an unmatched '/' path and the var after it
                    // has a default value, break out of the loop and return the output array
                    break;
                } else return false; // this route doesn't match
            }
            
            // remove the matched text from $path and rewind the offset
            $path = substr($path, $offset); $offset = 0;
        }
        
        return ($path !== false) ? false : $out;
    }
    
    public function assemble($data = array())
    {
        $out = '';
        foreach ($this->_parts as $part) {
            if ($part['type'] === 'var') {
                if (isset($data[$part['name']])) {
                    $regex = (isset($this->_requirements[$part['name']])) ? $this->_requirements[$part['name']] : self::DEFAULT_REGEX;
                    $regex = self::REGEX_DELIMITER . $regex . self::REGEX_DELIMITER . 'i';
                    if (preg_match($regex, $data[$part['name']])) $out .= $data[$part['name']];
                    else throw new Zend_Controller_Router_Exception($part['name'] . ' does not match the required pattern: ' . $regex);
                } elseif (array_key_exists($part['name'], $this->_defaults)) {
                    $out .= $this->_defaults[$part['name']];
                } else throw new Zend_Controller_Router_Exception($part['name'] . ' is not specified');
            } else $out .= $part['name'];
        }
        return rtrim($out, '/');
    }
    
}