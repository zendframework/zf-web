<?php
namespace ZfSite;

if (!isset($_SERVER['REQUEST_URI'])) {
    return;
}

$rewriteTable = array(
    '/zf2/blog'         => '/blog/index.html',
    '/cla'              => '/framework_cla_1.0.pdf',
    '/ccla'             => '/framework_cla_corporate_1.0.pdf',
    '/code'             => '/code/',
    '/download/gdata'   => '/downloads/latest',
    '/download/gdata/'  => '/downloads/latest',
    '/downloads/gdata'  => '/downloads/latest',
    '/downloads/gdata/' => '/downloads/latest',
    '/security/zf2013-01' => '/security/advisory/ZF2013-01',
    '/security/zf2013-02' => '/security/advisory/ZF2013-02',
    '/security/zf2013-03' => '/security/advisory/ZF2013-03',
    '/zf2'              => '/',
    '/zf2/status'       => '/',
    '/zf2/board'        => '/',
    '/zf2/board/invite' => '/',
    '/zf2/blog/feed'    => '/blog/feed-rss.xml',
    '/docs/quickstart'  => '/manual/1.12/en/learning.quickstart.html',
);

$rewriteRegexes = array(
    '#^/zf2/blog/entry/(?P<id>[^/]+)#' => '/blog/%id%.html',
    '#^/manual/(?P<lang>[a-z]{2}(_[a-zA-Z]+)?)(?P<page>.*)$#' => '/manual/1.12/%lang%%page%',
    '#^/manual(/(?P<version>\d+\.\d+)(/(?P<lang>[a-z]{2}(_[a-zA-Z]+)?)(/)?)?)?$#' => function ($uri, array $matches) {
        $lang    = isset($matches['lang'])    ? $matches['lang']    : 'en';
        $version = isset($matches['version']) ? $matches['version'] : '2.0';
        if ('1.1' === substr($matches['version'], 0, 3) ||
            '1.9' === substr($matches['version'], 0, 3)) {
            $file = 'manual.html';
        } else {
            $file = 'index.html';
        }
        return sprintf('/manual/%s/%s/%s', $version, $lang, $file);
    },
    '#^/community*#' => '/participate',
    '#^/releases/(?P<path>[^?]+\.(?:zip|tar.gz|tgz))#' => 'http://packages.zendframework.com/releases/%path%',
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

        foreach ($matches as $key => $value) {
            if (is_int($key) || is_numeric($key)) {
                continue;
            }
            $rewriteUri = str_replace(sprintf('%%%s%%', $key), $value, $rewriteUri);
        }
        $rewrite($rewriteUri);
        return;
    }
};
$test();
unset($rewriteTable, $rewriteRegexes, $rewrite, $test);
