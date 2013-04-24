<?php
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/library'),
    get_include_path())));


require_once 'Zend/Date.php';

$strInitialDate='25/02/1824';
$myDate = new Zend_Date($strInitialDate, 'dd/MM/YYYY');
$strFormattedDate= $myDate->toString('YYYY/MM/dd');

echo "Initial date ('dd/MM/YYYY'): ".$strInitialDate;
echo "\n";
echo "Formatted date ('YYYY/MM/dd'): ".$strFormattedDate;
echo "\n";

?>

