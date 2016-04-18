<?php
/**
 * Extract the issues in Markdown format
 *
 * @author Enrico Zimuel
 */
chdir(dirname(__DIR__ . '/../public'));
include 'vendor/autoload.php';

use League\HTMLToMarkdown\HtmlConverter;

$folder = 'module/Issues/view/issues/issues';
$output = 'data/issue';

$converter = new HtmlConverter(array('strip_tags' => true));

foreach (glob($folder . "/*.phtml") as $filename) {
    printf("Extracting %s...", $filename);

    $issue   = file_get_contents($filename);
    $format = <<<EOD
---
layout: issue
title: "%s"
id: %s
---

%s
EOD;

    $id = basename($filename, '.phtml');

    $issue = str_replace('{{', '', $issue);
    $issue = str_replace('}}', '', $issue);

    preg_match("/<h2>ZF[2]?\-[0-9]+\:(.*?)<\/h2>/", $issue, $matches);
    if (isset($matches[1])) {
      $title = trim($matches[1]);
    } else {
      die("ERROR!");
    }
    $content = sprintf($format,
        $title,
        $id,
        $converter->convert($issue)
    );
    file_put_contents($output . '/' . $id . '.md', $content);

    printf("done\n");
}
