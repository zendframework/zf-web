<?php

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", "on");

// Modify include_path to include the Zend library
// this is because they assume that their folder is in the path
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR."../zend_framework/library");

date_default_timezone_set('America/Boise');

require_once("Zend/Amf/Server.php");
require_once('Zend/Amf/Value/ByteArray.php');

$server = new Zend_Amf_Server();

class Tester {
	public function Gimme() {
		$ret = "howdy";
		// do a bunch of mcrypt calls on $ret here
		return new Zend_Amf_Value_ByteArray($ret);
	}
}

//adding our class to Zend AMF Server
$server->setClass("Tester");

// Change this to true when released to production
$server->setProduction(false);
echo($server->handle());