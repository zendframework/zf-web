<?php

class Hapi_Protocol_Json_Client
{
    protected $_client;
    protected $_id;

    public function __construct($uri, $id = 1)
    {
        $this->_client = new Zend_Http_Client($uri);
        $this->_id     = $id;
    }

    public function __call($name, $arguments)
    {
        $setup = array(
            'method' => $name,
            'params' => $arguments,
            'id'     => $this->_id);

        $json = Zend_Json::encode($setup);
        $this->_client->setRawData($json);
        $response = $this->_client->request('POST');
        $jsonRet  = Zend_Json::decode($response->getBody());

        if (array_key_exists('id', $jsonRet) && $jsonRet['id'] == $this->_id) {
            if (array_key_exists('result', $jsonRet)) {
                return $jsonRet['result'];
            } else if (in_array('error', $jsonRet)) {
                throw new Exception($jsonRet['error']);
            } else {
                throw new Exception('Invalid response.');
            }
        } else {
            throw new Exception('Invalid response ID #' . $this->_id . '.');
        }
    }
}