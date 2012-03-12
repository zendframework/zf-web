<?php
define('APPLICATION_PATH', dirname(dirname(__FILE__)));
ini_set('display_errors', false);

$appDir      = dirname(dirname(__FILE__));
$modelsDir   = $appDir . '/models';
$etcDir      = $appDir . '/etc';
$environment = 'production';

require_once 'Zend/Config/Ini.php';
require_once $modelsDir . '/ManualStatusModel.php';

$config = new Zend_Config_Ini($etcDir . '/config.ini', $environment);

$manualStatus = new ManualStatusModel($config);
$manualStatus->save();
