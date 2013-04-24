<?php

/*
	PHP 5.2.0
	ZF 1.6.2
	
	running this script throws the following in Zend_File_Transfer_Adapter_Abstract on my machine:
	
	PHP Notice: 
	Undefined variable: result in /Users/michel/web-lib/ZendFramework-1.6.2-minimal/library/Zend/File/Transfer/Adapter/Abstract.php on line 803 
	PHP Warning: Invalid argument supplied for foreach() in /Users/michel/web-lib/ZendFramework-1.6.2-minimal/library/Zend/Form/Element.php on line 524
	
*/

// edit as required:
set_include_path('/Users/michel/web-lib/ZendFramework-1.6.2-minimal/library');

require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

$element = new Zend_Form_Element_File('test');

$element->setDecorators(array('ViewHelper'));
$element->setDestination('/tmp');
$element->clearFilters();
$element->render(new Zend_View);
