<?php
$paths         = array();
$zf1ManualPath = '/var/local/framework/ZendFramework-%s/documentation/manual/core/%s/';
$zf1langs      = array('en', 'de', 'fr', 'ru', 'ja', 'zh');
$zf1versions   = include __DIR__ . '/zf1-manual-versions.php';

foreach ($zf1versions as $minorVersion => $specificVersion) {
    $paths[$minorVersion] = array();
    foreach ($zf1langs as $lang) {
        $paths[$minorVersion][$lang] = sprintf($zf1ManualPath, $specificVersion, $lang);
    }
}

$zf2ManualPath = '/var/local/framework/ZendFramework-%s/manual/%s/';
$zf2versions    = include __DIR__ . '/zf2-manual-versions.php';
$zf2langs       = array('en');
foreach ($zf2versions as $version) {
    $paths[$version] = array();
    foreach ($zf2langs as $lang) {
        $paths[$version][$lang] = sprintf($zf2ManualPath, $version, $lang);
    }
}

krsort($paths);

$zf1MinorVersions = array_keys($zf1versions);
usort($zf1MinorVersions, 'version_compare');

return array(
    'zf_document_path'             => $paths,
    'zf_apidoc_versions'           => include __DIR__ . '/zf-apidoc-versions.php',
    'zf_latest_version'            => max(array_keys($paths)),
    'zf1_latest_version'           => array_pop($zf1MinorVersions),
    'zf_maintained_major_versions' => array('1.12')
);
