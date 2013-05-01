<?php

/**
 * Zend Framework
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @copyright  Copyright (c) 2006 Eris Ristemena (http://www.ngoprekweb.com)
 * @license    http://www.gnu.org/copyleft/lesser.html  GNU LGPL 2.1

/**
 * Zend
 */
require_once 'Zend.php';

/**
 * Zend_Http_Exception
 */
require_once 'Zend/Http/Exception.php';

/**
 * Zend_Http_Client
 */
require_once 'Zend/Http/Client.php';


/**
 * Wrapper around Zend_Http_Client to facilitate Google's "Account Authentication 
 * for Installed Applications". 
 * 
 * @see http://code.google.com/apis/accounts/AuthForInstalledApps.html
 * 
 * @category   Zend
 * @package    Zend_Gdata
 * @copyright  Copyright (c) 2006 Eris Ristemena (http://www.ngoprekweb.com)
 * @license    http://www.gnu.org/copyleft/lesser.html  GNU LGPL 2.1
 */
class Zend_Gdata_ClientLogin{
	/**
   * Username
   *
   * @var string
   */
  protected $_username;
  
  /**
   * Password
   *
   * @var string
   */
  protected $_password;
  
  /**
   * Hold Auth code from Google response to ClientLogin authentication
   *
   * @var string
   */
  protected $_auth_code='';
  
	const CLIENTLOGINREQUEST_URI 		    = 'https://www.google.com/accounts/ClientLogin';
	 
	 /**
	  * Get Google ClientLogin Auth code.
	  *
	  * Ex:
	  *   $username = "yourusername";
	  *   $password = "yourpassword";
	  *   $service  = "blogger";
	  *   $source   = "Ngoprekweb-Ngeblog-0.1.1"; // companyName-applicationName-versionID
	  *   $auth     = Zend_Gdata_ClientLogin::getClientLoginAuth($username,$password,$service,$source);
	  *
	  * @param  string  $username     Username for Google Service (usually in form of email)
	  * @param  string  $password     Password
	  * @param  string  $service      Google Service identification ('blogger' for Blogger, 'cl' for Calendar and so on)
	  * @param  string  $source       Source in form of companyName-applicationName-versionID
	  * @param  string  $logintoken   token to use when you must solve CAPTCHA challenge (optional)
	  * @param  string  $logincaptcha CAPTCHA image URI (optional)
	  */
	 static public function getClientLoginAuth($username,$password,$service,$source,$logintoken='',$logincaptcha='') {
	 	$client = new Zend_Http_Client(self::CLIENTLOGINREQUEST_URI);
	 	
	 	$headers = array('Content-type'=>'application/x-www-form-urlencoded');
	 	$client->setHeaders($headers);
	 	
	 	$client->setParameterPost('Email',$username);
	 	$client->setParameterPost('Passwd',$password);
	 	$client->setParameterPost('service',$service);
	 	$client->setParameterPost('source',$source);
	 	
	 	ob_start();
    	$response = $client->request('POST');
	 	ob_end_clean();
	 	
	 	// Parse Google's response		
	 	if ($response->isSuccessful()) {
	 		$goog_resp = array();
			foreach (explode("\n", $response->getBody()) as $l) {
				$l = chop($l);
				if ($l) {
					list($key, $val) = explode('=', chop($l),2);
					$goog_resp[$key] = $val;
				}
			}
			return array( 'response'  =>  'authorized',
			              'auth'      =>  $goog_resp['Auth']
			            );
		} else {
			$goog_resp = array();
			foreach (explode("\n", $response->getBody()) as $l) {
				$l = chop($l);
				if ($l) {
					list($key, $val) = explode('=', chop($l),2);
					$goog_resp[$key] = $val;
				}
			}
			
			if ( $goog_resp['Error']=='CaptchaRequired' ) {
			  return array( 'response'      =>  'captcha',
			                'captchatoken'  =>  $goog_resp['CaptchaToken'],
			                'captchaurl'    =>  $goog_resp['CaptchaUrl']
			               );
			} else {
			  throw new Zend_Http_Exception("Auth failed. Reason: " .$response->getBody());
			}
		}
	 } 
	 
	 static public function getHttpClient($authcode) {
	 	$client = new Zend_Http_Client();
    $client->setHeaders('Authorization: GoogleLogin auth='.$authcode);
    
    return $client;
	 }
}