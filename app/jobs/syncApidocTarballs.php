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

echo "Syncing API documentation packages...\n";

$config = new Zend_Config_Ini(APPLICATION_PATH . '/etc/config.ini', APPLICATION_ENV);
Zend_Registry::set('config', $config);

// Get list of stable releases from each minor branch
include_once dirname(__FILE__) . '/../models/ReleaseModel.php';
$model       = new ReleaseModel('ZendFramework');
$releases    = array();
$allReleases = $model->getReleases();
foreach ($allReleases as $release) {
    if ('0' == substr($release, 0, 1)) {
        continue;
    }
    if ($model->isVersionStable($release)) {
        $minor      = substr($release, 0, strrpos($release, '.'));
        if (array_key_exists($minor, $releases)) {
            if (1 === version_compare($release, $releases[$minor])) {
                $releases[$minor] = $release;
		continue;
            }
        } else {
            $releases[$minor] = $release;
        }
    }
}

$finalPath = APPLICATION_PATH . '/../document_root/manual/apidoc/';
$basePath  = $config->releases->path;
foreach ($releases as $release) {
    $releasePath = $basePath . '/ZendFramework-' . $release . '/';
    $filename    = 'ZendFramework-' . $release . '-apidoc';
    foreach (array('.tar.gz', '.zip') as $sfx) {
        $origpath = $releasePath . $filename . $sfx;
        $path     = $finalPath . $filename . $sfx;
        echo "    $origpath\n";
        copy($origpath, $path);
    }
}
echo "[DONE]\n";
