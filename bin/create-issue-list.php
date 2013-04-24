<?php
$issueDir       = __DIR__ . '/../data/issues/';
$issuesListFile = __DIR__ . '/../module/Issues/config/issues.php';

$issues = array();
foreach (new GlobIterator($issueDir . '**/*.json', FilesystemIterator::KEY_AS_FILENAME|FilesystemIterator::CURRENT_AS_PATHNAME) as $key => $path) {
    echo "Processing $key...\n";
    $json = file_get_contents($path);
    $data = json_decode($json);

    $issues[] = array(
        'id'    => $data->key,
        'title' => $data->fields->summary->value,
    );
}
echo "Writing to $$issuesListFile\n";
file_put_contents($issuesListFile, '<' . "?php\nreturn " . var_export($issues, 1) . ';');
