<?php
require 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
var_dump(extension_loaded('bcmath'));
var_dump(Zend_Locale_Format::toNumber(
	13547.3678,
	array('precision' => 2,'locale' => new Zend_Locale('en_US')))
);