<?

require_once("Zend/Locale.php");
echo "Setting locale to: ".setlocale(LC_ALL, 'C')."\n";
echo "Setting default to de_DE\n";
Zend_Locale::setDefault('de_DE');
echo "Environment: "; var_dump(Zend_Locale::getEnvironment());
$locale = new Zend_Locale('auto');
echo "This should be de_DE: $locale\n";
