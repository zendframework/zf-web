<?php
use Zend\XmlRpc\Client as XmlRpcClient;

$services = $application->getServiceManager();
$config   = $services->get('Config');
if (!isset($config['changelog'])) {
    throw new RuntimeException(
        'Expecting a "changelog" key in configuration; none found'
    );
}
$config = $config['changelog'];
if (!isset($config['jira']) || !is_array($config['jira'])) {
    throw new RuntimeException(
        'Expecting an array of JIRA credentials in "changelog" configuration; none found'
    );
}
$jiraCredentials = $config['jira'];
$jiraUrl         = isset($config['jira']['url']) ? $config['jira']['url'] : 'http://framework.zend.com/issues/rpc/xmlrpc';

if (!isset($config['zf1_file'])) {
    throw new RuntimeException(
        'Expecting an "file" key in "changelog" configuration; none found'
    );
}
$changelogDataFile = $config['zf1_file'];

echo "Fetching changelog from JIRA... (this may take a minute or two)\n";
$cxn    = new XmlRpcClient($jiraUrl);
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
