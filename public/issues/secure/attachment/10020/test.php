<?php

require_once 'Zend/Cache.php';

$backendOptions = array('cacheDir' => '/tmp/');
$frontendOptions = array('lifeTime' => 3600);
$cache = Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
$cache->clean();

$res1 = $cache->start('foo');
if (!$res1) {
    echo 'hello world1<br/>';
    $cache->end();
}

$res2 = $cache->start('foo');
if (!$res2) {
    echo 'hello world2<br/>';
    $cache->end();
}

$cache->clean();
$res3 = $cache->start('foo');
if (!$res3) {
    echo 'hello world3<br/>';
    $cache->end();
}

var_dump($res1);
var_dump($res2);
var_dump($res3);

?>
