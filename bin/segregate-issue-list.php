<?php
$filename = __DIR__ . '/../module/Issues/config/issues.php';
$original = include $filename;

$sort = function (array $a, array $b) {
    return strnatcasecmp($a['id'], $b['id']);
};

$ZF1 = array_filter($original, function ($issue) {
    $result = preg_match('/^ZF-/', $issue['id']);
    if ($result === 0 || $result === false) {
        return false;
    }
    return true;
});
usort($ZF1, $sort);

$ZF2 = array_filter($original, function ($issue) {
    $result = preg_match('/^ZF2-/', $issue['id']);
    if ($result === 0 || $result === false) {
        return false;
    }
    return true;
});
usort($ZF2, $sort);

$issues = compact('ZF1', 'ZF2');
file_put_contents($filename, '<' . "?php\nreturn " . var_export($issues, 1) . ';');
