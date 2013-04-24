<?php

class Zend_Form_Notary
{
    protected $_registryName = 'FormNotary';

    protected $_array;
    protected $_items;
    protected $_btree;
    protected $_index;

    protected $_ident;

    public function __construct()
    {
        if (!Zend_Registry::isRegistered($this->_registryName)) {
            $this->_setupArray();
        }
        $this->_loadArray()
             ->_generateIdent();
    }

    protected function _setupArray()
    {
        $this->_array = array('items' => array('#0' => array()),
                              'btree' => array('#0', '#0'),
                              'index' => array());

        return $this->_saveArray();
    }    

    protected function _generateIdent()
    {
        end($this->_items);
        $this->_ident = '#' . (ltrim(key($this->_items), '#') + 1);
        $this->_array['items'][$this->_ident] = array();
        return $this;
    }

    protected function _loadArray()
    {
        $this->_array = Zend_Registry::get($this->_registryName);
        $this->_items =& $this->_array['items'];
        $this->_btree =& $this->_array['btree'];
        $this->_index =& $this->_array['index'];
        return $this;
    }

    protected function _saveArray()
    {
        Zend_Registry::set($this->_registryName, $this->_array);
        return $this;
    }

    public function getIdent()
    {
        return $this->_ident;
    }

    public function isIdent($ident)
    {
        $this->_loadArray();
        if (array_key_exists($ident, $this->_items)) {
            return true;
        }
        return false;
    }

    protected function _ensureIdent($ident)
    {
        if (null === $ident) {
            $this->_loadArray();
            return $this->_ident;
        }
        if (!$this->isIdent($ident)) {
            throw new Zend_Form_Exception("Nonexistant Ident '$ident'");
        }
        return $ident;
    }

    public function setProperty($key, $value, $forIdent = null)
    {
        $this->_setProperty($key, $value, $this->_ensureIdent($forIdent));
        $this->_saveArray();
    }

    public function setProperties($properties, $forIdent = null)
    {
        $ident = $this->_ensureIdent($forIdent);
        foreach ($properties as $key => $value) {
            $this->_setProperty($key, $value, $ident);
        }
        $this->_saveArray();
    }

    protected function _setProperty($key, $value, $ident)
    {
        if (3 == func_num_args()) {
            $value = $this->normalize($key, $value);
        }
        $this->_items[$ident][$key] = $value;
        if (!isset($this->_index[$key])) {
            $this->_index[$key] = array();
        }
        $this->_index[$key][$ident] =& $this->_items[$ident][$key];
        return $this;
    }

    public function getProperty($key, $fromIdent = null)
    {
        $key   = (string)$key;
        $ident = $this->_ensureIdent($fromIdent);
        return $this->prepare($key, $this->_getProperties($key, $ident), null);
    }

    public function getProperties($keys = null, $fromIdent = null)
    {
        $array = array();
        $ident = $this->_ensureIdent($fromIdent);
        foreach ($this->_getProperties($keys, $ident, true) as $key => $val) {
            $array[$key] = $this->prepare($key, $val, null);
        }
        return $array;
    }

    protected function _getProperties($key, $ident, $returnArray = false)
    {
        $keys = $this->_filterPropertyKeys($key);
        if (!empty($keys)) {
            if (true === $returnArray) {
                return array_intersect_key($this->_items[$ident], $keys);
            } else {
                return array_shift(array_intersect_key($this->_items[$ident],
                                                       $keys)));
            }
        } 
        return (true === $returnArray ? array() : null);
    }

    protected function _filterPropertyKeys($keys)
    {
        $ret = array();
        if (null === $key) {
            $ret = array_keys($this->_index);
        } else if (is_scalar($keys)) {
            $ret = array_intersect_key($this->_index, array($keys));
        } else if (is_array($keys)) {
            $ret = array_intersect_key($this->_index, $keys);
        }
        return $ret;
    }

    public function prepare($key, $value)
    {
        $prepare = 'prepare' . ucfirst($key);
        if (method_exists($this, $prepare)) {
            switch (func_num_args()) {
                case 3 :
                    return $this->$prepare($value, null);
                    break;
                default :
                    return $this->$prepare($value);
                    break;
            }
        }
        return $value;
    }

    public function prepareNotation($notation)
    {
        if (1 == func_num_args()) {
            $notation = $this->normalizeNotation($notation);
        }
        if (empty($segments = explode('/', $notation))) {
            return strtr($notation, '#', '');
        }
        $notation = array_shift($segments);
        if (count($segments)) {
            $notation .= '[' . join('][', $segments) . ']';
        }
        return strtr($notation, '#', '');
    }

    public function normalize($key, $value)
    {
        $normalize = 'normalize' . ucfirst($key);
        if (method_exists($this, $normalize)) {
            return $this->$normalize($value);
        }
        return $value;
    }

    public function normalizeNotation($notation)
    {
        $notation = str_replace('[]','/#/', $notation);
        return trim(strtr($notation, array('[' => '/', ']' => '')), '/');
    }

    public function getChilds($parent = null)
    {
        $parent = $this->_ensureIdent($parent);
        list($l, $r) = array_keys($this->_btree, $parent);
        $offsprings  = array_slice($this->_btree, $l+1, $r-$l);

        $childs = array();
        $child  = null;

        while (false !== ($current = current($offsprings))) {
            if (null === $child) {
                $childs[] = $child = $current;
            } else if ($child === $current) {
                $child = null;
            }
            next($offsprings);
        }
        if (empty($childs)) {
            return null;
        }
        return $childs;
    }

    public function getChildsByProperty($value, $filterKey = null, $fromIdent = null)
    {
        return $this->_filterIdents((array)$this->getChilds($fromIdent),
                                    $filterKey, $value);
    }

    public function getAncestors($ancestor = '#0', $offspring = null, $slice = null)
    {
        $offspring = $this->_ensureIdent($offspring);

        $idents = array();
        if ($this->isIdent($ancestor)) {
            $idents = $this->_ancestorsSeek($ancestor, $offspring);
        }
        if (!empty($idents)) {
            if (null !== $slice) {
                return array_slice($idents, $slice);
            }
            return $idents;
        }
        return null;
    }

    protected function _ancestorsSeek($ancestor, $offspring)
    {
        // outer bounds
        list($ol, $or) = array_keys($this->_btree, $ancestor, true);
        // inner bounds
        list($il, $ir) = array_keys($this->_btree, $offspring, true);

        return array_intersect(array_slice($this->_btree, $ol, $il-$ol+1),
                               array_slice($this->_btree, $ir, $or-$ir+1));
    }

    public function getIdentsByProperty($value, $filterKey = null)
    {
        $this->_loadArray();
        return $this->_filterIdents(array_keys($this->_items),
                                    $filterKey, $value);
    }

    protected function _filterIdents($idents, $keys, $value)
    {
        $keys  = $this->_filterPropertyKeys($keys);
        $array = array();
        foreach ($idents as $ident) {
            foreach (array_intersect_key($this->_items[$ident], $keys) as $pk => $pv) {
                if ($this->equals($pk, $pv, $value)) {
                    $array[] = $ident;
                }
            }
        }
        if (empty($array)) {
            return null;
        }
        return $array;
    }

    public function equals($propKey, $propVal, $value)
    {
        $equal = 'equals' . ucfirst($propKey);
        if (method_exists($this, $equal)) {
            return $this->$equal($propVal, $value);
        }
        return ($propVal == $value);
    }

    public function equalsNotation($str1, $str2)
    {
        $str1 = rtrim($this->normalizeNotation($str1), '/#');
        $str2 = rtrim($this->normalizeNotation($str2), '/#');
        return ($str1 === $str2);
    }
}
