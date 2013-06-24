<?php
$globPaths = array(
    'config/autoload/{,*.}{global,local}.php',
);
if (file_exists('/var/local/framework/zfweb-config')
    && is_dir('/var/local/framework/zfweb-config')
) {
    $globPaths[] = '/var/local/framework/zfweb-config/{,*.}{global,local}.php';
}
return array(
    'modules' => array(
        'Application',
        'Api',
        'Archives',
        'PageController',
        'Changelog',
        'Downloads',
        'Issues',
        'Manual',
        // 'Search',
        'Security',
        'Sitemap',
        'PhlyCommon',
        'PhlyBlog',
        'PhlyContact',
        'ZfSiteBlog',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => $globPaths,
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
