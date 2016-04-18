<?php
/**
 * Extract the changelog and convert in Markdown format
 *
 * @author Enrico Zimuel
 */
chdir(dirname(__DIR__ . '/../public'));
include 'vendor/autoload.php';

use League\HTMLToMarkdown\HtmlConverter;

$folder = 'data';
$output = 'data/changelog';

$converter = new HtmlConverter(array('strip_tags' => true));

$zf1 = include $folder . '/zf1-changelog.php';
$zf2 = include $folder . '/zf2-changelog.php';

extractChangelog($zf1, $output);
extractChangelog($zf2, $output);

$versions = array_merge(array_keys($zf1), array_keys($zf2));
rsort($versions, SORT_NATURAL);

$content = '';
foreach ($versions as $version) {
  $content .= sprintf("- version : %s\n\n", $version);
}
file_put_contents($output . '/changelog.yml', $content);

function extractChangelog(array $changelog, $output) {
  foreach ($changelog as $id => $issues) {
    printf("Extracting %s...", $id);

    if (is_array($issues)) {
      $content = sprintf("## Zend Framework %s\n\n", $id);
      foreach ($issues as $issue) {
        $content .= sprintf("- %s\t[%s](%s)\n", $issue['key'], $issue['summary'], '/issue/browse/' . $issue['key']);
      }
    } else {
      $content = $issues;
    }

    if (! isset($issue['updated'])) {
      if (! preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', substr($content, 0, 256), $matches)) {
          preg_match('/[0-9]{1,2} [A-Z]{1}[a-z]* [0-9]{4}/', substr($content, 0, 512), $matches);
          if (isset($matches[0])) {
            $matches[0] = date("Y-m-d", strtotime($matches[0]));
          } else {
            $matches[0] = '';
          }
      }
      $date = $matches[0];
    } else {
      $date = date("Y-m-d", strtotime($issue['updated']));
    }
    $content = str_replace('## SECURITY', '### SECURITY', $content);

    var_dump($date);

    $format = <<<EOD
---
layout: changelog
title: Changelog ver. %s
date: %s
---

%s
EOD;

    $content = sprintf($format,
        $id,
        $date,
        $content
    );
    file_put_contents($output . '/' . $id . '.md', $content);
    printf("done.\n");
  }
}
