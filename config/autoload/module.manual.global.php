<?php
$paths          = array();
$zf1ManualPath = '/var/local/framework/manual/views/manual/%s/%s/';
$zf1versions    = array('1.12', '1.11', '1.10', '1.9', '1.8', '1.7', '1.6', '1.5', '1.0');
$zf1langs       = array('en', 'de', 'fr', 'ru', 'ja', 'zh');
foreach ($zf1versions as $version) {
    $paths[$version] = array();
    foreach ($zf1langs as $lang) {
        $paths[$version][$lang] = sprintf($zf1ManualPath, $version, $lang);
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
