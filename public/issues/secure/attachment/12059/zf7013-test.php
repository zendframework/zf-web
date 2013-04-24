<?php
require_once('Zend/Loader.php');
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

$username = 'user@example.com';
$password = 'password';
$service = 'blogger';
$client = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service);

$gdata = new Zend_Gdata($client);
$entry = $gdata->newEntry();

$category = $gdata->newCategory();
$category->setScheme('http://www.blogger.com/atom/ns#');
$category->setTerm('Test Label');
$entry->setCategory(array($category));

$content = $gdata->newContent();
$content->setText("Hello world");
$entry->setContent($content);

$gdata->insertEntry($entry, "http://www.blogger.com/feeds/8273578352962669317/posts/default");