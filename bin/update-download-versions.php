<?php
if ($argc != 3) {
    printf("[%s] Invalid arguments (requires 2, received %d)\n", $argv[0], $argc);
    printf("Usage:\n    %s [version] [date]\n", $argv[0]);
    exit(1);
}
$version = $argv[1];
$date    = $argv[2];

if (!preg_match('/^(?P<major>\d+)\.(?P<minor>\d+)\.\d+/', $version, $matches)) {
    file_put_contents('php://stderr', "Invalid version provided!\n");
    exit(1);
}

$configFile = __DIR__ . '/../config/autoload/module.downloads.global.php';
$config = include($configFile);
$versions = $config['downloads']['versions'];

if (isset($versions[$version])) {
    $contents = file_get_contents($configFile);
    file_put_contents('php://stdout', $contents);
    exit(0);
}

// Add version and sort
$versions[$version] = $date;
uksort($versions, function ($a, $b) {
    $compare = version_compare($a, $b);
    if (0 === $compare) {
        return 0;
    }
    return (-1 === $compare ? 1 : -1);
});
$config['downloads']['versions'] = $versions;

$contents = '<' . "?php\nreturn " . var_export($config, 1) . ";";
file_put_contents('php://stdout', $contents);
exit(0);
