<?php
/**
 * Extract the blog posts in Markdown format
 * ready for Jekyll
 *
 * @author Enrico Zimuel
 */
chdir(dirname(__DIR__ . '/../public'));
include 'vendor/autoload.php';

use League\HTMLToMarkdown\HtmlConverter;

$folder = 'module/Security/view/security/advisory';
$output = 'data/advisory';

$converter = new HtmlConverter(array('strip_tags' => true));

$config = include 'module/Security/config/module.config.php';

foreach ($config['security']['advisories'] as $key => $value) {
    printf("Extracting $key...");
    $advisory = file_get_contents($folder . '/' . $key . '.phtml');
    $format = <<<EOD
---
layout: advisory
title: "%s"
date: %s
---

%s
EOD;

    $content = sprintf($format,
        str_replace('\\', '\\\\', $value['title']),
        date("Y-m-d", strtotime($value['date'])),
        $converter->convert($advisory)
    );
    file_put_contents($output . '/' . $key . '.md', $content);

    printf("done\n");
}
