<?php 

set_include_path('/storage/htdocs/testing/ZendFramework-0.1.5/library');

require_once 'Zend/Http/Client.php';

// Instantiate our client object
$http = new Zend_Http_Client();

// Set the URI to a POST data processor
$http->setUri('http://unicron.bla/testing/server.php');

// Save specific GET variables as HTTP POST data
$postData = 'foo=' . urlencode('this is foo') . '&bar=' . urlencode('this is bar');

// Make the HTTP POST request and save the HTTP response
$httpResponse = $http->post($postData);

echo $httpResponse->getBody();
?>