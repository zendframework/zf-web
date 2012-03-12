<?php

class ZfWeb_Auth_CrowdAdapter implements Zend_Auth_Adapter_Interface
{
    const MODE_SSO_ONLY = 'ssoOnly';
    const MODE_PRINCIPAL_ONLY = 'principalOnly';
    const MODE_SSO_THEN_PRINCIPAL = 'ssoThenPrincipal';
    
    protected $_crowdWSDLUrl = null;
    protected $_appName = null;
    protected $_appPassword = null;
    protected $_ssoCookieName = null;

    protected $_username = null;
    protected $_password = null;
    
    /**
     * @var string
     */
    protected $_authenticationMode = self::MODE_SSO_THEN_PRINCIPAL;
    
    /**
     * @var SoapClient
     */
    protected $_soapClient = null;
    protected $_soapAppToken = null;
    
    protected $_authenticateResultInfo = array();
    protected $_resultIdentity = array();
    
    public function __construct($options = array())
    {
        if ($options) {
            $this->setOptions($options);
        }
    }
    
    public function setOptions(Array $options)
    {
        foreach ($options as $optionName => $optionValue) {
            $method = 'set' . $optionName;
            if (method_exists($this, $method)) { 
                $this->{$method}($optionValue);
            }
        }
    }
    
    public function setCrowdWSDLUrl($crowdWSDLUrl)
    {
        $this->_crowdWSDLUrl = $crowdWSDLUrl;
        return $this;
    }
    
    public function setAppName($appName)
    {
        $this->_appName = $appName;
        return $this;
    }
    
    public function setAppPassword($appPassword)
    {
        $this->_appPassword = $appPassword;
        return $this;
    }
    
    public function setSSOCookieName($ssoCookieName)
    {
        $this->_ssoCookieName = $ssoCookieName;
        return $this;
    }
    
    public function setAuthenticationMode($authenticationMode)
    {
        if (in_array($authenticationMode, array(self::MODE_PRINCIPAL_ONLY, self::MODE_SSO_ONLY, self::MODE_SSO_THEN_PRINCIPAL))) {
            $this->_authenticationMode = $authenticationMode;
        }
        return $this;
    }
    
    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }
    
    public function setPassword($password)
    {
        $this->_password = $password;
        return $this;
    }
    
    public function requestHasSSOToken()
    {
        if (!isset($this->_ssoCookieName)) {
            return false;
        }
        
        return (isset($_COOKIE[$this->_ssoCookieName]));
    }
    
    public function getResultIdentity()
    {
        return $this->_resultIdentity;
    }
    
    public function authenticate()
    {
        $this->_authenticateSetup();

        if ($this->_authenticationMode == self::MODE_SSO_ONLY || $this->_authenticationMode == self::MODE_SSO_THEN_PRINCIPAL) {
            if ($this->_authenticateProcessSSO() === true) {
                return $this->_authenticateCreateResult();
            }
        }
        
        if ($this->_authenticationMode == self::MODE_PRINCIPAL_ONLY || $this->_authenticationMode == self::MODE_SSO_THEN_PRINCIPAL) {
            $this->_authenticateProcessPrincipal();
        }
        
        return $this->_authenticateCreateResult();
    }
    
    protected function _authenticateSetup()
    {
        if (!isset($this->_crowdWSDLUrl)) {
            throw new Exception('No Crowd WSDL URL (crowdWSDLUrl) was provided.');
        }
        
        if (!isset($this->_appName)) {
            throw new Exception('No application name (appName) was provided.');
        }
        
        if (!isset($this->_appPassword)) {
            throw new Exception('No application password (appPassword) was provided.');
        }
        
        if (($this->_authenticationMode == self::MODE_SSO_ONLY || $this->_authenticationMode == self::MODE_SSO_THEN_PRINCIPAL)
            && !isset($this->_ssoCookieName)) {
            throw new Exception('Authentication mode that includes SSO is selected, but no Crowd cookie name was provided (crowdCookieName)');
        }
        
        if (($this->_authenticationMode == self::MODE_PRINCIPAL_ONLY || $this->_authenticationMode == self::MODE_SSO_THEN_PRINCIPAL)
            && ((!isset($this->_username) || !isset($this->_password)))) {
            throw new Exception('Authentication mode that includes principal lookup was selected, but either a username or password was not provided.');
        }

        $this->_resultIdentity = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
        $this->_resultIdentity->username = null;
        
        // first create a soap client with the crowd WSDL
        try {
            $this->_soapClient = new SoapClient($this->_crowdWSDLUrl);
        } catch (Exception $e) {
            throw new Exception('Unable to connect to Crowd web service');
        }
        
        // next authenticate this soap client with the proper app name & password
        // (this would have been setup by the crowd Administrator)
        try {
            $authAppResponse = $this->_soapClient->authenticateApplication(array(
                'in0' => array(
                    'name' => $this->_appName,
                    'credential' => array('credential' => $this->_appPassword)
                    )
                ));
        } catch (Exception $e) {
            throw new Exception('Error authenticating application to webservice: ' . $e->getMessage());
        }

        $this->_soapAppToken = $authAppResponse->out->token;
        
        $this->_authenticateResultInfo = array(
            'code'     => Zend_Auth_Result::FAILURE,
            'identity' => $this->_username,
            'messages' => array()
            );
        
        return true;
    }
    
    protected function _authenticateProcessSSO()
    {
        $sessionToken = $_COOKIE[$this->_ssoCookieName];
        
        return $this->_authenticateCrowdGetUserByToken($sessionToken);
    }
    
    protected function _authenticateProcessPrincipal()
    {
        
        try {
            $findPrincipalResponse = $this->_soapClient->authenticatePrincipal(array(
                'in0' => array(
                    'name' => $this->_appName,
                    'token' => $this->_soapAppToken
                    ),
                'in1' => array(
                    'application' => $this->_appName,
                    'name' => $this->_username,
                    'credential' => array('credential' => $this->_password),
                    'validationFactors'=> array(
                        array('name'  => 'User-Agent', 'value' => $_SERVER['HTTP_USER_AGENT']),
                        array('name'  => 'remote_address', 'value' => $_SERVER['REMOTE_ADDR'])
                        ))
                ));
        } catch (SoapFault $soapFault) {
            $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->_authenticateResultInfo['messages'][] = 'Not able to get user information from crowd server with provided username and password.';
            return false;
        } catch (Exception $e) {
            throw new Exception('Crowd user lookup error: ' . $e->getMessage());
        }
        
        $principalToken = $findPrincipalResponse->out;
        
        return $this->_authenticateCrowdGetUserByToken($principalToken);
    }
    
    protected function _authenticateCrowdGetUserByToken($principalToken)
    {
        
        try {
            $findPrincipalResponse = $this->_soapClient->findPrincipalByToken(array(
                'in0' => array(
                    'name' => $this->_appName,
                    'token' => $this->_soapAppToken
                    ),
                'in1' => $principalToken
                ));
        } catch (SoapFault $soapFault) {
            $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->_authenticateResultInfo['messages'][] = 'Not able to get user information from crowd server with available token.';
            return false;
        } catch (Exception $e) {
            throw new Exception('Crowd user lookup error: ' . $e->getMessage());
        }
        
        $this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
        $this->_authenticateResultInfo['identity'] = $findPrincipalResponse->out->name;
        $this->_authenticateResultInfo['messages'][] = 'Found user information via SSO token.';
        
        // store the full result as an ArrayObject
        $this->_resultIdentity->username = $findPrincipalResponse->out->name; // username
        $this->_resultIdentity->name = $findPrincipalResponse->out->attributes->SOAPAttribute[2]->values->string; // display name
        $this->_resultIdentity->email = $findPrincipalResponse->out->attributes->SOAPAttribute[3]->values->string; // mail
        
        return true;
    }
    
    protected function _authenticateCreateResult()
    {
        return new Zend_Auth_Result(
            $this->_authenticateResultInfo['code'],
            $this->_authenticateResultInfo['identity'],
            $this->_authenticateResultInfo['messages']
            );
    }
    
}