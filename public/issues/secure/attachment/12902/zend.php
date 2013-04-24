<?php

require_once 'Zend/Application.php'; 
$app = new Zend_Application('test_script');



$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);

$trip = new fgSDK_Types_TTrip();
$origin = new fgSDK_Types_TPlace();
$destination = new fgSDK_Types_TPlace();

$origin->Address = 'test';
$origin->Accuracy = 5;

$destination->Address = 'test';
$destination->Accuracy = 5;


$routing = new fgSDK_Types_TRouting($origin, $destination);

$trip->Routings->Add($routing);

$encoded = Zend_Json_Encoder::encode($trip);

$decoded = Zend_Json_Decoder::decode($encoded);