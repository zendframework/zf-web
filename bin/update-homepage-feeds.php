<?php
use Zend\Feed\Reader\Reader;

require_once __DIR__ . '/../vendor/autoload.php';

if ($argc != 5) {
    printf("[%s] Invalid arguments (requires 4, received %d)\n", $argv[0], $argc);
    printf("Usage:\n    %s [blog-rss] [security-config] [template] [homepage-path]\n", $argv[0]);
    exit(1);
}
$blogRssPath        = $argv[1];
$securityConfigPath = $argv[2];
$template           = $argv[3];
$homepagePath       = $argv[4];

$items = array();
$max   = 4;

$feed  = Reader::importFile($blogRssPath);
$count = 0;
foreach ($feed as $item) {
    $items[] = array(
        'title' => $item->getTitle(),
        'href'  => $item->getLink(),
        'date'  => $item->getDateCreated(),
    );
    $count += 1;
    if ($count == $max) {
        break;
    }
}

$securityConfig = include $securityConfigPath;
$count          = 0;
foreach ($securityConfig['security']['advisories'] as $issue => $item) {
    $items[] = array(
        'title' => $item['title'],
        'href'  => sprintf('/security/advisory/%s', $issue),
        'date'  => DateTime::createFromFormat('D, d F Y H:i:s O', $item['date']),
    );
    $count += 1;
    if ($count == $max) {
        break;
    }
}

// Sort by date, DESC
usort($items, function ($a, $b) {
    $aDate = $a['date'];
    $bDate = $b['date'];
    if ($aDate == $bDate) {
        // If dates match, prefer /blog urls over /security, and list security URLs in order
        return ($a['href'] < $b['href']) ? 1 : -1;
    }
    return ($aDate < $bDate) ? 1 : -1;
});

$listitems = '';
$i         = 0;
while ($i < 4) {
    $item = array_shift($items);
    $listitems .= sprintf('<h5><a href="%s">%s</a></h5>', $item['href'], $item['title']) . "\n";
    $i +=1;
}

$homepage = file_get_contents($template);
$homepage = str_replace('{%news%}', $listitems, $homepage);

file_put_contents($homepagePath, $homepage);
