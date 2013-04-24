<?php
//Change this to the path where your 1.9.3 library/Zend folder lives
ini_set('include_path', '/www/PHP-Library');

require_once("Zend/Exception.php");
require_once("Zend/Pdf.php");
require_once("Zend/Pdf/Exception.php");


$doesnotCrash = 'doesnotcrash.pdf';
$crashPDF = 'Makeig.IntJNeuropharm.09.pdf';

$NormalLoad = Zend_Pdf::load($doesnotCrash);
$CrashLoad = Zend_Pdf::load($crashPDF);

?>