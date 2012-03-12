<?php
define('APPLICATION_PATH', 
    ((getenv('APPLICATION_PATH')) ? rtrim(getenv('APPLICATION_PATH'), '/') : realpath(dirname(__FILE__) . '/..')));
define('APPLICATION_ENV', 
    ((getenv('APPLICATION_ENV')) ? getenv('APPLICATION_ENV') : 'production'));

ini_set('display_errors', true);
error_reporting(E_ALL|E_STRICT);

set_include_path(implode(PATH_SEPARATOR, array(
    '.',
    realpath(dirname(dirname(__FILE__)) . '/framework/library'),
    get_include_path(),
)));

require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/etc/config.ini'
);

$options           = $application->getOptions();
$jiraCredentials   = $options['jira']['credentials'];
$changelogDataFile = $options['changelog']['file'];

$cxn    = new Zend_XmlRpc_Client('http://framework.zend.com/issues/rpc/xmlrpc');
$client = $cxn->getProxy('jira1');
$auth   = $client->login(
    $jiraCredentials['username'], 
    $jiraCredentials['password']
);

$filters = $client->getFavouriteFilters($auth);

$versions = array();
foreach ($filters as $filter) {
    if (preg_match('/fix.*?(\d+\.\d+\.\d+)/i', $filter['name'], $m)) {
        $versions[$m[1]] = $filter['id'];
    }
}

$issues = array();
foreach ($versions as $version => $filterId) {
    $issues[$version] = $client->getIssuesFromFilter($auth, $filterId);

    /*
    // Weed out duplicate IDs
    $issueKeys    = array();
    $finalIssues  = array();
    foreach ($client->getIssuesFromFilter($auth, $filterId) as $issue) {
        if (!in_array($issue['key'], $issueKeys)) {
            $issueKeys[]   = $issue['key'];
            $finalIssues[] = $issue;
        }
    }

    $issues[$version] = $finalIssues;
     */
}

$fileContent = "<?php\n\$issues = " 
             . var_export($issues, 1) 
             . ";\nreturn \$issues;";

echo "Writing to $changelogDataFile\n";
file_put_contents($changelogDataFile, $fileContent);

echo "Removing duplicates\n";
unset($issues, $fileContent);
$allIssues = include $changelogDataFile;
foreach ($allIssues as $version => $versionIssues) {
    $keys = array();
    foreach ($versionIssues as $index => $issue) {
        if (!array_key_exists('key', $issue)) {
            continue;
        }
        $key = $issue['key'];
        if (in_array($key, $keys)) {
            unset($versionIssues[$index]);
            continue;
        }
        $keys[] = $key;
    }
    $allIssues[$version] = $versionIssues;
}
$fileContent = "<?php\n\$issues = " 
             . var_export($allIssues, 1) 
             . ";\nreturn \$issues;";

echo "Re-writing to $changelogDataFile\n";
file_put_contents($changelogDataFile, $fileContent);
