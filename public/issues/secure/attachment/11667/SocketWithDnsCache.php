<?php

require_once 'Zend/Http/Client/Adapter/Socket.php';

class Zend_Http_Client_Adapter_SocketWithDnsCache extends Zend_Http_Client_Adapter_Socket
{
    protected $_ip;
    protected $_ips = array();

    public function connect($host, $port = 80, $secure = false)
    {   
        if (!isset($this->_ips[$host])) {
            $this->_ips[$host] = gethostbyname($host);
        }
        $this->_ip = $this->_ips[$host];

        return parent::connect($this->_ip, $port, $secure);
    }   

    public function write($method, $uri, $http_ver = '1.1', $headers = array(), $body = '') 
    {
        $uri->setHost($this->_ip);

        return parent::write($method, $uri, $http_ver, $headers, $body);
    }   
}
