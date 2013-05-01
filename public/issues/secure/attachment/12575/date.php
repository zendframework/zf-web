<?php

require 'Zend/Date.php';

Zend_Date::setOptions(array('format_type'=>'php'));


$date = Zend_Date::now();
$date1 = Zend_Date::now();
$date1->addMonth(1);

var_dump($date->compare($date1));