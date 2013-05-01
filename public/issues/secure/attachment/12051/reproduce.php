<?php
ini_set('include_path', '/www/PHP-Library');

require_once("Zend/Exception.php");
require_once("Zend/Pdf.php");
require_once("Zend/Pdf/Exception.php");

$testPDF = 'Nonlinear Interation Between Shunting and Adaptation Controls a Switch Between Integration and Coincidence Detection 2006-3877.pdf';
$ZendPDF = Zend_Pdf::load($testPDF);

print_r($ZendPDF->properties);

$ZendPDF->properties['foo'] = 'bar';
$ZendPDF->save($testPDF);
?>