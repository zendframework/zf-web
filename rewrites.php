<?php
namespace ZfSite;

$rewriteTable = array(
    '/zf2/blog'  => '/blog.html',
);

$rewriteRegexes = array(
    '#^/zf2/blog/entry/(?P<id>[^/]+)#' => '/blog/%id%.html',
    '#^/manual(/(?P<lang>[a-z]{2}(_[a-zA-Z]+)?)(/(?P<version>\d+.\d)(/)?)?)?$#' => function ($uri, array $matches) {
        $lang    = isset($matches['lang'])    ? $matches['lang']    : 'en';
        $version = isset($matches['version']) ? $matches['version'] : '2.0';
        return sprintf('/manual/%s/%s/index.html', $lang, $version);
    },
);

$rewrite = function ($uri) {
    header(sprintf('Location: %s', $uri), true, 301);
    exit(0);
};

$test = function () use ($rewriteTable, $rewriteRegexes, $rewrite) {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = parse_url($uri, PHP_URL_PATH);
    $uri = rtrim($uri, '/');

    if (!$uri) {
        return;
    }

    $uri = strtolower($uri);
    if (isset($rewriteTable[strtolower($uri)])) {
        $rewriteTo = $rewriteTable[strtolower($uri)];
        if (is_callable($rewriteTo)) {
            $rewriteTo = $rewriteTo();
        }
        $rewrite($rewriteTo);
        return;
    }

    foreach ($rewriteRegexes as $regex => $rewriteUri) {
        $matches = array();
        if (!preg_match($regex, $uri, $matches)) {
            continue;
        }

        if (is_callable($rewriteUri)) {
            $rewriteUri = $rewriteUri($uri, $matches);
            $rewrite($rewriteUri);
            return;
        }

        $replacements = array();
        foreach ($matches as $key => $value) {
            if (is_int($key) || is_numeric($key)) {
                continue;
            }
            $rewriteUri = str_replace(sprintf('%%%s%%', $key), $value, $rewriteUri);
        }
        $rewrite(strtolower($rewriteUri));
        return;
    }
};
$test();
unset($rewriteTable, $rewriteRegexes, $rewrite, $test);
