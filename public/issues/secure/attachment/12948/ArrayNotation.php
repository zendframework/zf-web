<?php

final class Zend_Form_ArrayNotation
{
    private static $_instance;

    private $_items = array('#0' => array());
    private $_array = array('#0', '#0');

    private $_allowed = array('name','path','concat');
    /**
     * getInstance 
     * 
     * @static
     * @access public
     * @return void
     */
	public static function getInstance()
	{
		if (self::$_instance === null) {
            self::$_instance = new self();
        }
		return self::$_instance;
	}

    /**
     * __construct
     * 
     * @access private
     * @return void
     */
    private function __construct()
    {
        return $this;
    }

    /**
     * debugItemsArrays 
     * 
     * @access public
     * @return void
     */
    public function debugItemsArrays()
    {
        $array = array('$this->_array' => $this->_array,
                       '$this->_items' => $this->_items);
        return $array;
    }

    /**
     * addItem
     * 
     * @param array $properties 
     * @param string $into 
     * @access public
     * @return string ItemId
     */
    public function addItem($properties = array(), $into = null)
    {
        end($this->_items);
        $ident = '#' . ((int) ltrim(key($this->_items), '#') + 1);

        $this->_items[$ident] = array_fill_keys($this->_allowed,
                                                null);

        $this->setProperties($ident, $properties)
             ->appendItem($ident, $into);
        return $ident;
    }

    /**
     * setProperties 
     * 
     * @param mixed $ident 
     * @param mixed $properties 
     * @access public
     * @return void
     */
    public function setProperties($ident, $properties)
    {
        foreach ($properties as $key => $value) {
            $this->setProperty($ident, $key, $value);
        }
        return $this;
    }

    /**
     * setProperty 
     * 
     * @param mixed $ident 
     * @param mixed $key 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function setProperty($ident, $key, $value)
    {
        if (!in_array($key, $this->_allowed, true)) {
            return $this;
        }
        $method = 'set' . ucfirst($key);
        $this->$method($ident, $value);
        return $this;
    }

    /**
     * setConcat 
     * 
     * @param mixed $ident 
     * @param mixed $method 
     * @access public
     * @return void
     */
    public function setConcat($ident, $method)
    {
        if (!isset($this->$ident)) {
            throw new Zend_Exception("Item '$ident' does not exist.");
        } else if ($ident === '#0') {
            throw new Zend_Exception("Item '$ident' cannot be changed.");
        }
        if (is_string($method)) {
            $method = '_concat' . ucfirst($method);
            if (!method_exists($this, $method) || '_concatItems' === $method) {
                $method = null;
            }
        }
        if (isset($method)) {
            $this->_items[$ident]['concat'] = $method;
        }
        return $this;
    }

    public function getConcat($ident)
    {
        if (!isset($this->$ident)) {
           return null; 
        }
        return str_replace('_concat', '', $this->_items[$ident]['concat']);
    }

    /**
     * setName 
     * 
     * @param mixed $ident 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function setName($ident, $name)
    {
        if (!isset($this->$ident)) {
            throw new Zend_Exception("Item '$ident' does not exist.");
        } else if ($ident === '#0') {
            throw new Zend_Exception("Item '$ident' cannot be changed.");
        }
        $this->_items[$ident]['name'] = $name;
        return $this;
    }

    /**
     * setNotation 
     * 
     * @param mixed $ident 
     * @param mixed $segments 
     * @access public
     * @return void
     */
    public function setPath($ident, $segments = null)
    {
        if (!isset($this->$ident)) {
            throw new Zend_Exception("Item '$ident' does not exist.");
        } else if ($ident === '#0') {
            throw new Zend_Exception("Item '$ident' cannot be changed.");
        }
        $this->_items[$ident]['path'] = $this->toPath($segments);
        return $this;
    }

    /**
     * removeItem 
     * 
     * @param mixed $ident 
     * @access public
     * @return void
     */
    public function removeItem($ident)
    {
        if (!isset($this->$ident) || $ident === '#0') {
            return $this;
        }
        list($l, $r) = array_keys($this->_array, $ident, true);
        array_splice($this->_array, $l, ++$r-$l);

        unset($this->_items[$ident]);
        return $this;
    }

    /**
     * appendItem 
     * 
     * @param mixed $ident 
     * @param mixed $into 
     * @access public
     * @return void
     */
    public function appendItem($ident, $into = null)
    {
        if (null === $into) {
            $into = '#0';
        } else if (!isset($this->$into)) {
            throw new Zend_Exception("Attempt to append to nonexistant item");
        }
        if (!isset($this->$ident)) {
            throw new Zend_Exception("Item '$ident' does not exist.");
        } else if ($ident === '#0') {
            throw new Zend_Exception("Item '$ident' cannot be changed.");
        }

        if (false !== array_search($ident, $this->_array, true)) {
            $this->_moveInto($ident, $into);
        } else {
            $this->_insertInto(array($ident, $ident), $into);
        }
        return $this;
    }

    /**
     * _moveInto 
     * 
     * @param mixed $ident 
     * @param mixed $into 
     * @access private
     * @return void
     */
    private function _moveInto($ident, $into)
    {
        list($l, $r) = array_keys($this->_array, $ident, true);
        $this->_insertInto(array_splice($this->_array, $l, ++$r-$l), $into);
    }

    /**
     * _insertInto 
     * 
     * @param mixed $ident 
     * @param mixed $into 
     * @access private
     * @return void
     */
    private function _insertInto($ident, $into)
    {
        list($l, $r) = array_keys($this->_array, $into, true);
        array_splice($this->_array, $r, 0, $ident);
    }

    /**
     * normalizePath 
     * 
     * @param mixed $path 
     * @access public
     * @return void
     */
    public function normalizePath($path)
    {
        return trim(strtr($path, array('[' => '/', ']' => '')), '/');
    }

    /**
     * getArrayNotation 
     * 
     * @param mixed $ident 
     * @access public
     * @return void
     */
    public function getNotation($ident, $from = null, $concat = null)
    {
        if (!isset($this->$ident)) {
            throw new Zend_Exception("Item '$ident' does not exist.");
        }
        
        $items = array();
        if (is_numeric($from)) {
            if ($from == 0) {
                $items = array($ident);
            } else {
                $items = $this->_seekUp($ident, abs($from));
            }
        } else if (null === $from) {
            $items = $this->_seekFrom('#0', $ident);
        } else if (isset($this->$from)) {
            $items = $this->_seekFrom($from, $ident);
        }

        if (isset($concat)) {
            $old = $this->getConcat($ident);
            $this->setConcat($ident, $concat);
            
            $path = $this->_concatItems($items);

            $this->setConcat($ident, $old);
        } else {
            $path = $this->_concatItems($items);
        }

        if ('' === $path) {
            return null;
        }

        return $this->_toArrayNotation($path);
    }

    /**
     * getValuesForItem 
     * 
     * @param mixed $ident 
     * @param mixed $values 
     * @access public
     * @return void
     */
    public function getValuesForItem($ident, $values)
    {
        if (!isset($this->$ident)) {
            return null;
        }
        $segments = $this->_concatItems(array($ident));

        $segment = strtok($segments, '/');

        while (isset($values[$segment])) {
            $values  = $values[$segment];
            if (false === ($segment = strtok('/'))) {
                return $values;
            }
        }
        return null;
    }

    /**
     * getValuesForItemIfExist 
     * 
     * @param mixed $ident 
     * @param mixed $values 
     * @access public
     * @return void
     */
    public function getValuesForItemIfExist($ident, $values)
    {
        if (null !== ($test = $this->getValuesForItem($ident, $values))) {
            $values = $test;
        }
        return $values;
    }

    /**
     * appendValue 
     * 
     * @param mixed $ident 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function appendValue($ident, $value)
    {
        if (!isset($this->$ident)) {
            throw new Zend_Exception("Item '$ident' does not exist.");
        }
        return $this->_toArray($this->_concatItems(array($ident)),
                               $value);
    }

    /**
     * replaceInto 
     * 
     * @param mixed $ident 
     * @param mixed $array 
     * @param mixed $value 
     * @access public
     * @return void
     */
    public function replaceInto($ident, $array, $value)
    {
        if (!isset($this->$ident)) {
            throw new Zend_Exception("Item '$ident' does not exist.");
        }
        $value = $this->_toArray($this->_concatItems(array($ident)),
                                 $value);
        return $this->_array_replace_recursive($array, $value);
    }

    /**
     * _toArray 
     * 
     * @param mixed $path 
     * @param mixed $value 
     * @access private
     * @return void
     */
    private function _toArray($path, $value)
    {
        $segments = explode('/', $path);

        $array = array();
        $final =& $array;

        $numeric = 0;
        foreach ($segments as $segment) {
            if ('' === $segment) {
                $segment = $numeric++;
            }
            $final = $final[$segment] = array();
            $final =& $final[$segment];
        }
        $final = $value;
        return $array;
    }

    /**
     * _toArrayNotation 
     * 
     * @param mixed $path 
     * @access private
     * @return void
     */
    private function _toArrayNotation($path)
    {
        $segments = explode('/', $path);

        $notation = array_shift($segments);

        if (count($segments)) {
            $notation .= '[' . join('][', $segments) . ']';
        }

        return $notation;
    }

    /**
     * toPath 
     * 
     * @param mixed $segments 
     * @access public
     * @return string
     */
    public function toPath($segments)
    {
        $path = '';
        if (is_string($segments)) {
            $path = $this->_toPathFromString($segments);
        } else if (is_array($segments)) {
            $path = $this->_toPathFromArray($segments);
        }
        $path =  $this->normalizePath($path);

        if ('' === $path) {
            return null;
        }
        return $path;
    }

    /**
     * _toPathFromString 
     * 
     * @param mixed $string 
     * @access private
     * @return void
     */
    private function _toPathFromString($string)
    {
        if (isset($this->$string)) {
            return $this->_concatItems(array($string));
        }
        return $string;
    }

    /**
     * _toPathFromArray 
     * 
     * @param mixed $array 
     * @access private
     * @return void
     */
    private function _toPathFromArray($array)
    {
        $path = '';
        while (is_array($array)) {
            $path .= key($array) . '/';
            $array = current($array);
        }
        return $path;
    }

    /**
     * toId 
     * 
     * @param mixed $segments 
     * @access public
     * @return void
     */
    public function toId($segments)
    {
        $id = '';
        if (is_string($segments)) {
            $id = $this->_toIdFromString($segments);
        } else if (is_array($segments)) {
            $id = $this->_toPathFromArray($segments);
        }
        $id = $this->normalizePath($id);

        if ('' === $id) {
            return null;
        }
        return strtr($id, '/', '-');
    }

    /**
     * _toIdFromString 
     * 
     * @param mixed $string 
     * @access private
     * @return void
     */
    private function _toIdFromString($string)
    {
        if (isset($this->$string)) {
            $items = $this->_seekFrom('#0', $string);
            return $this->_concatItems($items);
        }
        return $string;
    }

    /**
     * This is a helper function until php 5.3 is widespreaded 
     * 
     * @param array $into
     * @access protected
     * @return void
     */
    protected function _array_replace_recursive(array $into)
    {
        $fromArrays = array_slice(func_get_args(),1);

        foreach ($fromArrays as $from) {
            foreach ($from as $key => $value) {
                if (is_array($value)) {
                    if (!isset($into[$key])) {
                        $into[$key] = array();
                    }
                    $into[$key] = $this->_array_replace_recursive($into[$key], $from[$key]);
                } else {
                    $into[$key] = $value;
                }
            }
        }
        return $into;
    }

    /**
     * _concatItems 
     * 
     * @param mixed $array 
     * @access private
     * @return void
     */
    private function _concatItems($array)
    {
        $notation = '';
        foreach ($array as $item) {
            if (isset($this->_items[$item]['concat'])) {
                $method    = $this->_items[$item]['concat'];
                $notation .= ltrim($this->$method($this->_items[$item]), '/');
            }
        }
        return trim($notation, '/');
    }

    /**
     * _concatPathOnly 
     * 
     * @param mixed $item 
     * @access private
     * @return void
     */
    private function _concatPathOnly($item)
    {
        return $item['path'] . '/';
    }

    /**
     * _concatPathAppendName 
     * 
     * @param mixed $item 
     * @access private
     * @return void
     */
    private function _concatPathAppendName($item)
    {
        return $item['path'] . '/' . $item['name'] . '/';
    }

    /**
     * _concatEmptyPathAppendName 
     * 
     * @param mixed $item 
     * @access private
     * @return void
     */
    private function _concatEmptyPathAppendName($item)
    {
        if (null === $item['path']) {
            return $item['name'] . '/';
        }
        return $item['path'] . '/';
    }

    /**
     * _seekUp 
     * 
     * @param mixed $ident 
     * @param mixed $count 
     * @access private
     * @return void
     */
    private function _seekUp($ident, $count)
    {
        ++$count;
        $seek  = array();
        $keys  = array_keys($this->_array, $ident, true);
        $items = array_slice($this->_array, end($keys));

        while ($count && !empty($items)) {
            $keys = array_keys($items, current($items), true);
            if (isset($keys[1])) {
                list($l, $r) = $keys;
                array_splice($items, $l, $r-$l+1);
            } else {
                array_unshift($seek, array_shift($items));
                --$count;
            }
        }
        return $seek;
    }

    /**
     * _seekFrom 
     * 
     * @param mixed $from 
     * @param mixed $ident 
     * @access private
     * @return void
     */
    private function _seekFrom($from, $ident)
    {
        list($ol, $or) = array_keys($this->_array, $from, true);
        list($il, $ir) = array_keys($this->_array, $ident, true);

        return array_intersect(array_slice($this->_array, $ol, $il-$ol+1),
                               array_slice($this->_array, $ir, $or-$ir+1));
    }

    /**
     * __isset 
     * 
     * @param string $ident 
     * @access public
     * @return bool
     */
    public function __isset($ident)
    {
        if (!is_string($ident) ||
            !array_key_exists($ident, $this->_items)) {
            return false;
        }
        return true;
    }

    /**
     * __clone 
     * 
     * @access private
     * @return void
     */
    private function __clone()
    {
    }
}
