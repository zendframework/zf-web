<?php


set_include_path(get_include_path() . PATH_SEPARATOR . 'C:\svn\zend\ZendFramework-1.10.2-minimal\library');
require_once 'Zend/Amf/Server.php';

class XCRpcProtocol_AMF
{
    public function getConfiguration($whatever)
    {
    }

    public function beginSession($configuration_blob = null, $login_method = null, $login_blob = null)
    {
        $ret = new stdClass();
        $ret->session_info = new stdClass();
        $ret->session_info->code = "123";
        return $ret;
    }

    public function login($session_info, $login_method, $login_blob)
    {
        $ret = new stdClass();
        $ret->session_info = $session_info;
        $ret->login_method = $login_method;
        $ret->login_blob = $login_blob;
        return $ret;
    }
}

$server = new Zend_Amf_Server();
$server->setProduction(false);
$server->setClass("XCRpcProtocol_AMF");
$response = $server->handle();
echo $response;

