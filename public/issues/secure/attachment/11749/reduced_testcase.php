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

class ArrayClass implements Iterator
{
    protected $_data;
    
    function __construct() 
    {
        $this->_data = array(
            'first'  => (1),
            'second' => (2),
            'third'  => (3)
            );
    }
    
    public function count()
    {
        return count($this->_data);
    }

    public function current()
    {
        return current($this->_data);
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
        return !$end;
    }

    public function __unset($name)
    {
        unset($this->_data[$name]);
    }
}

$class = new ArrayClass();
foreach ($class as $key => $value)
{
    echo "start of loop: $key\n";
    if ($key == 'first') {
        echo "__unset() called\n";
        unset($class->$key);
    }
}
