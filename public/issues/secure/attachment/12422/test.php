<?php
$a = array(
    'alpha'  => 'ABC',
    'number' => 123456
);

$b = array(
    'alpha'  => 'DEF',
    'number' => 789,
    'object' => new stdClass()
);

var_dump($a+$b);
echo '<br />';
var_dump(array_merge($b,$a));