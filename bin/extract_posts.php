<?php
/**
 * Extract the blog posts in Markdown format
 *
 * @author Enrico Zimuel
 */
chdir(dirname(__DIR__ . '/../public'));
include 'vendor/autoload.php';

use League\HTMLToMarkdown\HtmlConverter;

$folder = 'data/posts';
$output = 'data/blog';

$converter = new HtmlConverter(array('strip_tags' => true));

foreach (glob($folder . "/*.php") as $filename) {
    printf("Extracting %s...", $filename);

    $post   = include $filename;
    $format = <<<EOD
---
layout: post
title: %s
date: %s
author: %s
url_author: %s
permalink: %s
categories:
- blog
%s
---

%s
EOD;

    $categories = [ 'Apigility', 'Released', 'IRC', 'Dev', 'Expressive', 'zf3' ];
    $category   = '';
    foreach ($categories as $cat) {
        if (strpos($post->getTitle(), $cat) !== false) {
            $category .= '- ' . strtolower($cat) . "\n";
        }
    }

    $content = sprintf($format,
        $post->getTitle(),
        date("Y-m-d", $post->getCreated()),
        $post->getAuthor()->getName(),
        $post->getAuthor()->getUrl(),
        '/blog/' . $post->getId() . '.html',
        $category,
        $converter->convert($post->getBody() . $post->getExtended())
    );
    file_put_contents($output . '/' . $post->getId() . '.md', $content);

    printf("done\n");
}
