<?php
if ($argc != 2) {
    printf("[%s] Invalid arguments (requires 1, received %d)\n", $argv[0], $argc);
    printf("Usage:\n    %s [version]\n", $argv[0]);
    exit(1);
}
$version = $argv[1];

if (!preg_match('/^(?P<major>\d+)\.(?P<minor>\d+)\.\d+/', $version, $matches)) {
    file_put_contents('php://stderr', "Invalid version provided!\n");
    exit(1);
}
$major = $matches['major'];
$minor = $major . '.' . $matches['minor'];

$sort = function ($a, $b) {
    $compare = version_compare($a, $b);
    if (0 === $compare) {
        return 0;
    }
    return (1 === $compare ? -1 : 1);
};

$configFile = __DIR__ . '/../config/autoload/zf-manual-routes.global.php';
$config     = include($configFile);
switch ($major) {
    case '1':
        // do nothing
        break;
    case '2':
        $version    = $config['router']['routes']['manual']['options']['defaults']['version'];
        if (version_compare($version, $minor, '<')) {
            $config['router']['routes']['manual']['options']['defaults']['version'] = $minor;
        }
        $updated = true;
        break;
    default:
        file_put_contents('php://stderr', 'Unsupported major version!');
        exit(1);
}

$contents = '<' . "?php\nreturn " . var_export($config, 1) . ';';
file_put_contents('php://stdout', $contents);
