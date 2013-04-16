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

switch ($major) {
    case '1':
        $configFile = __DIR__ . '/../config/autoload/zf1-manual-versions.php';
        $versions   = include($configFile);
        if (!array_key_exists($minor, $versions)) {
            $versions[$minor] = $version;
        } elseif (version_compare($version, $versions[$minor], 'gt')) {
            $versions[$minor] = $version;
        }
        uksort($versions, $sort);
        break;
    case '2':
        $configFile = __DIR__ . '/../config/autoload/zf2-manual-versions.php';
        $versions   = include($configFile);
        if (!in_array($minor, $versions)) {
            $versions[] = $minor;
        }
        usort($versions, $sort);
        break;
    default:
        file_put_contents('php://stderr', 'Unsupported major version!');
        exit(1);
}

$contents = '<' . "?php\nreturn " . var_export($versions, 1) . ';';
file_put_contents('php://stdout', $contents);
