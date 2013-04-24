<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Http
 * @subpackage Client_Adapter
 * @version    $Id: Proxy.php 8064 2008-02-16 10:58:39Z thomas $
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

require_once 'Zend/Uri/Http.php';
require_once 'Zend/Http/Client.php';
require_once 'Zend/Http/Client/Adapter/Curl.php';

/**
 * HTTP Proxy-supporting Zend_Http_Client adapter class, based on the default
 * socket based adapter.
 *
 * Should be used if proxy HTTP access is required. If no proxy is set, will
 * fall back to Zend_Http_Client_Adapter_Socket behavior. Just like the
 * default Socket adapter, this adapter does not require any special extensions
 * installed.
 *
 * @category   Zend
 * @package    Zend_Http
 * @subpackage Client_Adapter
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Http_Client_Adapter_CurlProxy extends Zend_Http_Client_Adapter_Curl
{
    /**
     * Parameters array
     *
     * @var array
     */
    public $config = array(
        'ssltransport'  => 'ssl',
        'proxy_host'    => '',
        'proxy_port'    => 8080,
        'proxy_user'    => '',
        'proxy_pass'    => '',
        'proxy_auth'    => Zend_Http_Client::AUTH_BASIC
    );

    /**
     * Last headers
     *
     * @var array
     */
    static private $headers = array();
    
    
    /**
	 * Response gotten from server
	 *
	 * @var string
	 */
    private $response = null;
    
    /**
     * Connect to the remote server
     *
     * Will try to connect to the proxy server. If no proxy was set, will
     * fall back to the target server (behave like regular Socket adapter)
     *
     * @param string  $host
     * @param int     $port
     * @param boolean $secure
     * @param int     $timeout
     */
    public function connect($host, $port = 80, $secure = false)
    {
        // If no proxy is set, fall back to Socket adapter
        if (! $this->config['proxy_host']) return parent::connect($host, $port, $secure);        

        // If we are connected to a different server or port, disconnect first
        if ($this->curl && is_array($this->connected_to) &&
                ($this->connected_to[0] != $host || $this->connected_to[1] != $port)) {
            $this->close();
        }
        
        // Do the actual connection
        $this->curl = curl_init();
        if ($port != 80) {
            curl_setopt($this->curl, CURLOPT_PORT, intval($port));
        }
        
        // Go through a proxy - the connection is actually to the proxy server
        $host = $this->config['proxy_host'];
        $port = $this->config['proxy_port'];
        
//        curl_setopt ($this->curl, CURLOPT_HTTPPROXYTUNNEL, TRUE);
//        curl_setopt ($this->curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        curl_setopt ($this->curl, CURLOPT_PROXY, $host .":". $port);
        
        
        
        if (! $this->curl) {
            $this->close();
            throw new Zend_Http_Client_Adapter_Exception('Unable to Connect to use proxy' .
            $host . ':' . $port);
        }
        // Update connected_to
        $this->connected_to = array($host, $port);
    }

    /**
     * Send request to the proxy server
     *
     * @param string        $method
     * @param Zend_Uri_Http $uri
     * @param string        $http_ver
     * @param array         $headers
     * @param string        $body
     * @return string Request as string
     */
    public function write($method, $uri, $http_ver = '1.1', $headers = array(), $body = '')
    {
        // set URL
        curl_setopt($this->curl, CURLOPT_URL, $uri->__toString());
//        var_dump($this->curl);
        // Make sure we're properly connected
//        if (! $this->curl)
//            require_once 'Zend/Http/Client/Adapter/Exception.php';
//            throw new Zend_Http_Client_Adapter_Exception("Trying to write but we are not connected");

//        if ($this->connected_to[0] != $uri->getHost() || $this->connected_to[1] != $uri->getPort())
//            require_once 'Zend/Http/Client/Adapter/Exception.php';
//            throw new Zend_Http_Client_Adapter_Exception("Trying to write but we are connected to the wrong host");

        // ensure correct curl call
        if ($method == Zend_Http_Client::GET) {
            $curlMethod = CURLOPT_HTTPGET;
        } elseif ($method == Zend_Http_Client::POST) {
            $curlMethod = CURLOPT_POST;
        } else {
            // TODO: use CURLOPT_PUT for PUT requests, CURLOPT_CUSTOMREQUEST for other types of calls
            // For now, through an exception for unsupported request methods
            require_once 'Zend/Http/Client/Adapter/Exception.php';
            throw new Zend_Http_Client_Adapter_Exception("Method currently not supported");
        }        
        
        // get http version to use
        $curlHttp = ($http_ver = 1.1) ? CURL_HTTP_VERSION_1_1 : CURL_HTTP_VERSION_1_0;

        curl_setopt($this->curl, $curlMethod, true);
        curl_setopt($this->curl, $curlHttp, true);

        if ( $uri->getScheme() == 'https') {
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);     
        }
        
        $host = $this->config['proxy_host'];
        $port = $this->config['proxy_port'];                
        var_dump($host);
        var_dump($port);
        curl_setopt ($this->curl, CURLOPT_PROXY, $host);
        curl_setopt ($this->curl, CURLOPT_PROXYPORT, $port);
        
        
        // ensure headers are also returned
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        // ensure actual response is returned
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        // set additional headers
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
       
//        curl_setopt($this->curl, CURLOPT_ENCODING, "gzip"); 
         
//        var_dump($this->config);
        
//        if(!$this->config['followlocation']){
//            var_dump("OFF followlocation");
//            curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 0);
//        } else {
//            var_dump("ON followlocation");
//            curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
//        }
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->config['timeout']);
        
        $ob =& $this; 
        
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, array(&$ob,'readHeader'));        
        if ($method == Zend_Http_Client::POST) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        }
        
        // send the request
        $this->response = curl_exec($this->curl); 
        $this->response = $this->getHeaders() . $this->response;
        $con_est_str = "HTTP/1.0 200 Connection established";
        if (strstr($this->response, $con_est_str)!==false) {
            $response = substr($this->response, strlen($con_est_str)+strlen("\r\n\r\n"));
            $this->response = $response;
        } else {
            if ($this->response===false){
                require_once 'Zend/Http/Client/Adapter/Exception.php';
                throw new Zend_Http_Client_Adapter_Exception("\nCurl error: ".curl_error($this->curl)."\n");
            }
        }
    }
    
    /**
	 * CURL callback function for reading and processing headers
	 * Override this for your needs
	 * 
	 * @param object $ch
	 * @param string $header
	 * @return integer
	 */
	private function readHeader($ch, $header) {
        self::$headers[] = $header;
        $chead = count(self::$headers);
	    if ( (trim(self::$headers[count(self::$headers)-2])==='') && $chead>1){
	        $temp = self::$headers[count(self::$headers)-1];
            self::$headers = array();
	        self::$headers[] = $temp;
	    }
		return strlen($header);
	}
	
	
	private function getHeaders() {
		return implode('', self::$headers);
	}

    
    /**
	 * Return read response from server
	 *
	 * @return string
	 */
    public function read()
    {
        return $this->response;
    }
    
    /**
	 * Close the connection to the server
	 *
	 */
    public function close()
    {
        curl_close($this->curl);
        $this->curl = null;
        $this->connected_to = array(null, null);
    }

    /**
	 * Destructor: make sure curl is disconnected
	 *
	 */
    public function __destruct()
    {
        if ($this->curl) $this->close();
    }
    
    
    /**
     * Set configuration parameters for this HTTP client
     *
     * @param array $config
     * @return Zend_Http_Client
     */
    public function setConfig($config = array())
    {
//        var_dump($config);
        echo "Set CurlProxy Config\n";        
        if (! is_array($config)) {
            require_once 'Zend/Http/Client/Exception.php';
            throw new Zend_Http_Client_Exception('Expected array parameter, given ' . gettype($config));
        }

        foreach ($config as $k => $v)
            $this->config[strtolower($k)] = $v;

        return $this;
    }
}
