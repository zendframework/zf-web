<?php
namespace ZfSite;

$rewriteTable = array(
    '/zf2/blog' => '/blog.html',
);

$rewriteRegexes = array(
    '#^/zf2/blog/entry/(?P<id>[^/])#' => '/blog/%id%.html',
);

$rewrite = function ($uri) {
    header(sprintf('Location: %s', $uri), true, 301);
    exit(0);
};

$test = function () use ($rewriteTable, $rewriteRegexes, $rewrite) {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);

    if (!$uri) {
        return;
    }

    $uri = strtolower($uri);
    if (isset($rewriteTable[strtolower($uri)])) {
        $rewrite($rewriteTable[strtolower($uri)]);
        return;
    }

    foreach ($rewriteRegexes as $regex => $rewriteUri) {
        $matches = array();
        if (preg_match($regex, $uri, $matches)) {
            $replacements = array();
            foreach ($matches as $key => $value) {
                if (is_int($key) || is_numeric($key)) {
                    continue;
                }
                $rewriteUri = str_replace(sprintf('\%%s\%', $key), $value, $rewriteUri);
            }
            $redirect(strtolower($rewriteUri));
            return;
        }
    }
};
$test();
unset($rewriteTable, $rewriteRegexes, $rewrite, $test);
