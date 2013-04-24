<?php
/*
 * problem when the extension intl is enabled
 * see http://www.php.net/intl
 * see http://www.php.net/Locale
 * see http://framework.zend.com/issues/browse/ZF-7550
 */
set_include_path('/your_path/');

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$config = array(
            'resources' =>
                array('locale' =>
                    array(
                        'default' => 'fr_FR',
                        'force'   => true
                    )
                )
);

$app = new Zend_Application('testing', $config);

$locale = $app->bootstrap()
              ->getBootstrap()
              ->getPluginResource('locale')
              ->getLocale();
			  
echo "Region: ", $locale->getRegion(), // expected FR
     "\t\tLanguage: ", $locale->getLanguage(); // expected fr