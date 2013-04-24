<?php 
set_include_path(get_include_path().PATH_SEPARATOR.'./library');


// setup Zend autloading, this requires that the library is available via the include_path
require 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();


// define float value that will differ between rounding and cutoff
$floatvalue = 1298.85525;
$precision = 2;
$testLocale = 'en_GB';

// setup a fixed locale to get around differeing output formats regarding . and ,
$locale = new Zend_Locale();
$locale->setLocale('en_GB');
Zend_Registry::set('Zend_Locale', $locale);

// what is produced by php round function
// expected result is 1298.86
echo round($floatvalue,$precision);
echo "\n";

// what is produce by Zend_Locale_Math
// expected result is 1298.86
echo Zend_Locale_Math::round($floatvalue,$precision);
echo "\n";

// what is produced by Zend_Locale_Math without specifiying an explicit format
// expected result is 1,298.86
// this will return 1,298.85 instead!
echo Zend_Locale_Format::toNumber($floatvalue,array('precision' => $precision,'locale' => $locale));
echo "\n";

// what is produced by Zend_Locale_Math with specifiying an explicit format
// expected result is 1,298.86
$format  = Zend_Locale_Data::getContent((string)$locale, 'decimalnumber');
echo Zend_Locale_Format::toNumber($floatvalue,array('precision' => $precision,'locale' => $locale,'number_format' => $format));
echo "\n";
