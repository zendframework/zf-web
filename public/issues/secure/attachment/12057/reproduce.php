<?php
ini_set('include_path', '/www/PHP-Library');

require_once("Zend/Exception.php");
require_once("Zend/Pdf.php");
require_once("Zend/Pdf/Exception.php");

$testPDF = 'Monte Carlo Model of Background Glutamate Spillover in the Hippocampus 2004-3291.pdf';
$ZendPDF = Zend_Pdf::load($testPDF);

print_r($ZendPDF->properties);

$ZendPDF->properties['foo'] = 'bar';
$ZendPDF->save($testPDF);

?>