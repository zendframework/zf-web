<?php
use Zend\Http\Client as HttpClient;

$services = $application->getServiceManager();
$config   = $services->get('Config');
if (!isset($config['changelog'])) {
    throw new RuntimeException(
        'Expecting a "changelog" key in configuration; none found'
    );
}
$config = $config['changelog'];

if (!isset($config['zf2_file'])) {
    throw new RuntimeException(
        'Expecting an "file" key in "changelog" configuration; none found'
    );
}
$changelogDataFile = $config['zf2_file'];

$client = new HttpClient('https://api.github.com', array(
    'adapter'   => 'Zend\Http\Client\Adapter\Curl',
    'keepalive' => true,
    'timeout'   => 10,
));

$data = array();
$filter = function ($string) {
    return preg_replace("/\n\-+(?:BEGIN PGP SIGNATURE).*/s", '', $string);
};

echo "Fetching list of all tags\n";
$client->setUri('https://api.github.com/repos/zendframework/zf2/tags');
$response = $client->send();
$tags     = json_decode($response->getBody());
foreach ($tags as $info) {
    $tag = $info->name;
    if (preg_match('/dev(?:el)?(?:\d+(?:[a-z]+)?)?$/', $tag)) {
        // skip development tags
        continue;
    }

    echo "    Fetching ref info for tag '$tag'\n";
    $client->setUri('https://api.github.com/repos/zendframework/zf2/git/refs/tags/' . $tag);
    $response = $client->send();
    $refInfo  = json_decode($response->getBody());
    $tagUrl   = $refInfo->object->url;

    echo "    Fetching tag metadata for tag '$tag'\n";
    $client->setUri($tagUrl);
    $response = $client->send();
    $tagInfo  = json_decode($response->getBody());

    $tag = str_replace('release-', '', $tag);
    $data[$tag] = $filter($tagInfo->message);
}

$fileContent = "<?php\n\$tags = " 
             . var_export($data, 1) 
             . ";\nreturn \$tags;";

echo "Writing to $changelogDataFile\n";
file_put_contents($changelogDataFile, $fileContent);
