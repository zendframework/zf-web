<?php
$paths          = array();
$zf1ManualPath  = '/var/local/framework/ZendFramework-%s/documentation/manual/core/%s/';
$zf1langs       = array('en', 'de', 'fr', 'ru', 'ja', 'zh');
$zf1versions    = array(
    '1.12' => '1.12.1',
    '1.11' => '1.11.15',
    '1.10' => '1.10.9',
    '1.9'  => '1.9.8',
    '1.8'  => '1.8.5',
    '1.7'  => '1.7.9',
    '1.6'  => '1.6.2',
    '1.5'  => '1.5.3',
    '1.0'  => '1.0.3',
);
foreach ($zf1versions as $minorVersion => $specificVersion) {
    $paths[$minorVersion] = array();
    foreach ($zf1langs as $lang) {
        $paths[$minorVersion][$lang] = sprintf($zf1ManualPath, $specificVersion, $lang);
    }
}

$zf2ManualPath = '/var/local/framework/ZendFramework-%s/manual/%s/';
$zf2versions    = array('2.0');
$zf2langs       = array('en');
foreach ($zf2versions as $version) {
    $paths[$version] = array();
    foreach ($zf2langs as $lang) {
        $paths[$version][$lang] = sprintf($zf2ManualPath, $version, $lang);
    }
}
return array(
    'zf_document_path' => $paths,
);
