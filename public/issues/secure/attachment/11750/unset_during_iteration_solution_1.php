<?php
/*
The output of this script is:


ARRAY:
start of loop: first
__unset() called
start of loop: second
start of loop: third

OBJECT USING ITEARATOR:
start of loop: first
__unset() called
start of loop: third


How do I fix my ArrayClass object to ensure that the
foreach() iterates over the second item in the internal
array?
*/

echo "ARRAY:\n";

$array = array(
            'first'  => (1),
            'second' => (2),
            'third'  => (3)
            );
foreach ($array as $key => $value)
{
    echo "start of loop: $key\n";
    if ($key == 'first') {
        echo "__unset() called\n";
        unset($array[$key]);
    }
}


echo "\nOBJECT USING ITEARATOR:\n";

class ArrayClass implements Iterator, Countable
{
    const DELETED = '33291d5171f38d3d0f5f8da5533fb6e6cb3fad26'; // sha1('__deleted__')
    protected $_data;
    
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
        $value = $this->get($key);
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
        $end = (key($this->_data) === null);
        if ($end == false) {
            if (current($this->_data) == self::DELETED) {
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
    }
}

$class = new ArrayClass();
foreach ($class as $key => $value)
{
    echo "start of loop: $key\n";
    if ($key == 'fourth') {
        echo "__unset() called\n";
        unset($class->$key);
    }
}

echo "\nNumber of elements:";
echo count($class);
echo "\n";

echo "\nSimple iteration after unset:\n";
foreach ($class as $key => $value)
{
    echo "$key\n";
}

echo "\nisset first? ";
var_dump(isset($class->first));
echo "isset second? ";
var_dump(isset($class->second));
echo "\n";