<?php
define('APPLICATION_PATH', dirname(dirname(__FILE__)));
define('APPLICATION_ENV', 
    (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production')
);
date_default_timezone_set('GMT');
ini_set('display_errors', false);
ini_set('memory_limit', -1);
error_reporting(E_ALL | E_STRICT);
set_time_limit(0);
$paths = array(
    '.',
    dirname(__FILE__) . '/../../framework',
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $paths));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

echo "COPYING IMAGES from manual to images directory\n";

$config   = new Zend_Config_Ini(__DIR__ . '/../etc/config.ini.dist', 'production');
$viewDir  = $config->manual->views;
$imageDir = __DIR__ . '/../../document_root/images/manual';
if (!file_exists($imageDir)) {
    mkdir($imageDir, 0777, true);
}
$imageDir = realpath($imageDir);
foreach (glob("$viewDir/manual/*.*") as $dir) {
    if (!is_dir($dir)) {
        continue;
    }

    if (!preg_match('/^\d+\.\d+$/', basename($dir))) {
        continue;
    }

    echo "    Copying for version " . basename($dir) . "\n";

    foreach (glob("$dir/*") as $lang) {
        if (!preg_match('/^[a-z]{2,}/', basename($lang))) {
            continue;
        }
        if (!is_dir("$lang/images")) {
            continue;
        }
        echo "        Copying for language " . basename($lang) . "\n";
        passthru("cp -a $lang/images/*.* $imageDir");
    }
}
echo "[DONE]\n";
