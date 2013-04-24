<?php
/*
How do we ensure that ArrayClass object to ensure that the
foreach() iterates over the second item in the internal
array?
*/

class ArrayClass implements Iterator, Countable
{
    const DELETED = '33291d5171f38d3d0f5f8da5533fb6e6cb3fad26'; // sha1('__deleted__')
    protected $_data;
    protected $_deleted = array();
    
    function __construct() 
    {
        $this->_data = array(
            'first'  => (1),
            'second' => (2),
            'third'  => (3),
            'fourth' => (4)
            );
    }
    
    public function __get($key)
    {
        $value = isset($this->_data[$key]) ? $this->_data[$key] : null;
        if (self::DELETED === $value) {
            $value = null;
            unset($this->_data[$key]);
        }
        return $value;
    }
    
    public function __isset($key)
    {
        $result = isset($this->_data[$key]);
        if ($result && self::DELETED === $this->_data[$key]) {
            $result = false;
            unset($this->_data[$key]);
        }
        return $result;
    }
    
    public function count()
    {
        foreach ($this->_data as $key=>$value) {
            if (self::DELETED === $value) {
                unset($this->_data[$key]);
            }
        }
        return count($this->_data);
    }

    public function current()
    {
        $value = current($this->_data);
        if (self::DELETED === $value) {
            $this->next();
            $value = $this->current();
        }
        return $value;
    }

    public function key()
    {
        return key($this->_data);
    }

    public function next()
    {
        next($this->_data);
    }

    public function rewind()
    {
        $this->_beyondLastField = false;
        reset($this->_data);
    }

    public function valid()
    {
        $key = key($this->_data);
        $end = ($key === null);
        if ($end == false) {
            if (isset($this->_deleted[$key])) {
                next($this->_data);
                $end = (key($this->_data) === null);
                if ($end == false) {
                    prev($this->_data);
                }
            }
        }
        return !$end;
    }

    public function __unset($key)
    {
        $this->_data[$key] = self::DELETED;
        $this->_deleted[$key] = $key;
    }
}


function testOne($keyToUnset)
{
    echo "Test by unsetting '$keyToUnset':\n\n";    
    $class = new ArrayClass();
    foreach ($class as $key => $value)
    {
        echo "start of loop: $key\n";
        if ($key == $keyToUnset) {
            echo "    __unset() called\n";
            unset($class->$key);
        }
        echo "arbitary access of an element in the array: ". $class->second. "\n";
    }
    
    echo "\nNumber of elements: ";
    echo count($class);
    echo "\n";
    
    echo "\nSimple iteration after unset:\n";
    foreach ($class as $key => $value)
    {
        echo "    $key\n";
    }
    
    echo "\nisset first? ";
    var_dump(isset($class->first));
    echo "isset second? ";
    var_dump(isset($class->second));
    echo "isset third? ";
    var_dump(isset($class->third));
    echo "isset fourth? ";
    var_dump(isset($class->fourth));
    echo "\n";
}

function testTwo()
{
    echo "--- Secondary test ---\n";
    $class = new ArrayClass();
    foreach ($class as $key => $value)
    {
        echo "    $key\n";
    }
    echo "--\n";
    unset($class->first);
    foreach ($class as $key => $value)
    {
        echo "    $key\n";
    }
}

testOne('first');
testOne('fourth');
testTwo();
