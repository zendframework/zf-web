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
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: EmailAddress.php 14560 2009-03-31 14:41:22Z thomas $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @see Zend_Validate_Hostname
 */
require_once 'Zend/Validate/Hostname.php';

/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_EmailAddress extends Zend_Validate_Abstract
{
    const INVALID                 = 'emailAddressInvalid';
    const INVALID_HOSTNAME        = 'emailAddressInvalidHostname';
    const INVALID_MX_RECORD       = 'emailAddressInvalidMxRecord';
    const INVALID_NETWORK_SEGMENT = 'emailAddressInvalidNetworkSegment';
    const DOT_ATOM                = 'emailAddressDotAtom';
    const QUOTED_STRING           = 'emailAddressQuotedString';
    const INVALID_LOCAL_PART      = 'emailAddressInvalidLocalPart';
    const LENGTH_EXCEEDED         = 'emailAddressLengthExceeded';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID                 => "'%value%' is not a valid email address in the basic format local-part@hostname",
        self::INVALID_HOSTNAME        => "'%hostname%' is not a valid hostname for email address '%value%'",
        self::INVALID_MX_RECORD       => "'%hostname%' does not appear to have a valid MX record for the email address '%value%'",
        self::INVALID_NETWORK_SEGMENT => "'%hostname%' is not in a routable network segment. The email address '%value%' should not be resolved form public network.",
        self::DOT_ATOM                => "'%localPart%' not matched against dot-atom format",
        self::QUOTED_STRING           => "'%localPart%' not matched against quoted-string format",
        self::INVALID_LOCAL_PART      => "'%localPart%' is not a valid local part for email address '%value%'",
        self::LENGTH_EXCEEDED         => "'%value%' exceeds the allowed length"
    );
    
    /**
     *  @var array
     */
    protected $_invalidSegments = array(
        '10'  => '10.0.0.0/8',        // RFC 1918
        '127' => '127.0.0.0/8',       // this host
        '169' => '169.254.0.0/16',    // link-local
        '172' => '172.16.0.0/12',     // RFC 1918
        '192' => array(
                    '192.0.2.0/24',   // example net
                    '192.168.0.0/16'  // RFC 1918
                ),
        '198' => '198.18.0.0/15',     // benchmark net
        '224' => '224.0.0.0/3'        // multicast & reserved
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'hostname'  => '_hostname',
        'localPart' => '_localPart'
    );

    /**
     * Local object for validating the hostname part of an email address
     *
     * @var Zend_Validate_Hostname
     * @depreciated
     */
    public $hostnameValidator;

    /**
     * Whether we check for a valid MX record via DNS
     *
     * @var boolean
     */
    protected $_validateMx = false;
    
    /**
     * Whether we perform a deep check for a valid MX
     *
     * @var boolean
     */
    protected $_deepMxCheck = false;

    /**
     * @var string
     */
    protected $_hostname;

    /**
     * @var string
     */
    protected $_localPart;

    /**
     * Instantiates hostname validator for local use
     *
     * You can pass a bitfield to determine what types of hostnames are allowed.
     * These bitfields are defined by the ALLOW_* constants in Zend_Validate_Hostname
     * The default is to allow DNS hostnames only
     *
     * @param integer                $allow             OPTIONAL
     * @param bool                   $validateMx        OPTIONAL
     * @param Zend_Validate_Hostname $hostnameValidator OPTIONAL
     * @param bool                   $deepMxCheck       OPTIONAL
     * @return void
     */
    public function __construct($allow = Zend_Validate_Hostname::ALLOW_DNS, $validateMx = false, Zend_Validate_Hostname $hostnameValidator = null, $deepMxCheck = false)
    {
        $this->setValidateMx($validateMx, $deepMxCheck);
        $this->setHostnameValidator($hostnameValidator, $allow);
    }

    /**
     * Returns the set hostname validator
     *
     * @return Zend_Validate_Hostname
     */
    public function getHostnameValidator()
    {
        return $this->hostnameValidator;
    }

    /**
     * @param Zend_Validate_Hostname $hostnameValidator OPTIONAL
     * @param int                    $allow             OPTIONAL
     * @return void
     */
    public function setHostnameValidator(Zend_Validate_Hostname $hostnameValidator = null, $allow = Zend_Validate_Hostname::ALLOW_DNS)
    {
        if ($hostnameValidator === null) {
            $hostnameValidator = new Zend_Validate_Hostname($allow);
        }
        $this->hostnameValidator = $hostnameValidator;
    }

    /**
     * Whether MX checking via dns_get_mx is supported or not
     *
     * This currently only works on UNIX systems
     *
     * @return boolean
     */
    public function validateMxSupported()
    {
        return function_exists('dns_get_mx');
    }
    
    /**
     * Whether MX checking via checkdnsrr is supported or not
     *
     * @return boolean
     */
    public function validateDnsSupported()
    {
      return function_exists('checkdnsrr');
    }

    /**
     * Set whether we check for a valid MX record via DNS
     *
     * This only applies when DNS hostnames are validated
     *
     * @param boolean $allowed Set allowed to true to validate for MX records, and false to not validate them
     * @param boolean $deepCheck Set deepCheck to true to perform a deep validation process for MX records
     */
    public function setValidateMx($allowed, $deepCheck)
    {
        $this->_validateMx = (bool) $allowed;
        $this->_deepMxCheck = (bool) $deepCheck;
    }
    
    /**
     * Whether the given IP address is a reservered IP
     *
     * @param string $host
     * @return boolean
     */
    private function isReservedIpAddress($host){
        if(!preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $host)){
            $host = gethostbyname($host);
        }
        $octet = explode('.',$host);
        if((int)$octet[0] >= 224) { // multicast & reserved
            return true;
        } else if(array_key_exists($octet[0], $this->_invalidSegments)){
            foreach((array)$this->_invalidSegments[$octet[0]] as $subnetData){
                // we skip the first loop as we already know that octet matches
                for($i = 1; $i < 4; $i++){
                    if(strpos($subnetData,$octet[$i]) !== $i * 4){
                        break;
                    }
                }
                $segmentData = $this->getNetworkSegment($subnetData);
                for($j = $i; $j < 4; $j++){
                    if((int)$octet[$j] < (int)$segmentData['network'][$j] ||
                       (int)$octet[$j] > (int)$segmentData['broadcast'][$j]){
                        return false;
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * calculates subnet informations
     *
     * The algorythm is based upon PHP Subnet Calculator v1.3.
     * Copyright 06/25/2003 Raymond Ferguson
     * Released under GNU GPL.
     * Special thanks to krischan at jodies.cx for ipcalc.pl
     * The presentation and concept was mostly taken from ipcalc.pl.
     *
     * @link ferguson@share-foo.com
     * @link http://jodies.de/ipcalc
     *
     * @param string $subnetData
     * @return array
     */
    private function getNetworkSegment($subnetData){
        $host = strtok($subnetData, "/");
        $networkMask = strtok("/");

        $binaryHost = $this->ipToBinary($host);
        $binaryNetwork = str_pad(substr($binaryHost, 0, $networkMask), 32, 0);
        $binaryBroadcast = str_pad(substr($binaryHost, 0, $networkMask), 32, 1);
        $binaryFirst = str_pad(substr($binaryNetwork, 0, 31), 32, 1);
        $binaryLast = str_pad(substr($binaryBroadcast, 0, 31), 32, 0);

        return array(
            'network'  => $this->binaryToIp($binaryNetwork, false),
            'min'      => $this->binaryToIp($binaryFirst, false),
            'max'      => $this->binaryToIp($binaryLast, false),
            'broadcast' => $this->binaryToIp($binaryBroadcast, false),
        );
    }
    
    /**
     * converts IP address to binary data
     *
     * @param string $ipData
     * @param boolean $implode set true to implode return data into string or false to return as array OPTIONAL
     * @return mixed
     */
    private function ipToBinary($ipData, $implode = true) {
        $binaryData = array();
        $tmp = explode(".", $ipData);
        for ($i = 0; $i < 4 ; $i++) {
           $binaryData[$i] = str_pad(decbin($tmp[$i]), 8, "0", STR_PAD_LEFT);
        }
        return $implode ? implode("", $binaryData) : $binaryData;
    }
    
    /**
     * converts binary data to IP address
     *
     * @param string $binaryData
     * @param boolean $implode set true to implode return data into string or false to return as array OPTIONAL
     * @return mixed
     */
    private function binaryToIp($binaryData, $implode = true)
    {
        $ipData = array();
        $tmp = explode(".", chunk_split($binaryData, 8, "."));
        for ($i = 0; $i < 4 ; $i++) {
            $ipData[$i] = bindec($tmp[$i]);
        }
        return $implode ? implode(".", $ipData) : $ipData;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a valid email address
     * according to RFC2822
     *
     * @link   http://www.ietf.org/rfc/rfc2822.txt RFC2822
     * @link   http://www.columbia.edu/kermit/ascii.html US-ASCII characters
     * @param  string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        $matches     = array();
        $length      = true;

        $this->_setValue($valueString);

        // Split email address up and disallow '..'
        if ((strpos($valueString, '..') !== false) or
            (!preg_match('/^(.+)@([^@]+)$/', $valueString, $matches))) {
            $this->_error(self::INVALID);
            return false;
        }

        $this->_localPart = $matches[1];
        $this->_hostname  = $matches[2];

        if ((strlen($this->_localPart) > 64) || (strlen($this->_hostname) > 255)) {
            $length = false;
            $this->_error(self::LENGTH_EXCEEDED);
        }

        // Match hostname part
        $hostnameResult = $this->hostnameValidator->setTranslator($this->getTranslator())
                               ->isValid($this->_hostname);
        if (!$hostnameResult) {
            $this->_error(self::INVALID_HOSTNAME);

            // Get messages and errors from hostnameValidator
            foreach ($this->hostnameValidator->getMessages() as $code => $message) {
                $this->_messages[$code] = $message;
            }
            foreach ($this->hostnameValidator->getErrors() as $error) {
                $this->_errors[] = $error;
            }
        } else if ($this->_validateMx) {
            // MX check on hostname via dns_get_record()
            if ($this->validateMxSupported()) {
                $result = dns_get_mx($this->_hostname, $mxHosts);
                if (count($mxHosts) < 1) {
                    // the address may can accept email even if there's no MX record but A or A6 or AAAA record found
                    if($this->_deepMxCheck && $this->validateDnsSupported()) {
                        if($this->isReservedIpAddress($this->_hostname)){
                            $hostnameResult = false;
                            $this->_error(self::INVALID_NETWORK_SEGMENT);
                        }else if(checkdnsrr($this->_hostname, "A") === false &&
                            checkdnsrr($this->_hostname, "AAAA") === false &&
                            checkdnsrr($this->_hostname, "A6") === false){
                                $hostnameResult = false;
                                $this->_error(self::INVALID_MX_RECORD);
                        }
                    } else {
                        $hostnameResult = false;
                        $this->_error(self::INVALID_MX_RECORD);
                    }
                } else {
                    // must check every MX record for at least one valid address
                    if($this->_deepMxCheck && $this->validateDnsSupported()) {
                        $hasOneValidAddress = false;
                        foreach($mxHosts as $key => $hostname){
                            if(!$this->isReservedIpAddress($hostname) &&
                                (checkdnsrr($hostname, "A") !== false ||
                                checkdnsrr($hostname, "AAAA") !== false ||
                                checkdnsrr($hostname, "A6") !== false)){
                                    $hasOneValidAddress = true;
                                    break;
                            }
                        }
                        
                        if($hasOneValidAssress === false){
                            $hostnameResult = false;
                            $this->_error(self::INVALID_MX_RECORD);
                        }
                    }
                }
            } else {
                /**
                 * MX checks are not supported by this system
                 * @see Zend_Validate_Exception
                 */
                require_once 'Zend/Validate/Exception.php';
                throw new Zend_Validate_Exception('Internal error: MX checking not available on this system');
            }
        }

        // First try to match the local part on the common dot-atom format
        $localResult = false;

        // Dot-atom characters are: 1*atext *("." 1*atext)
        // atext: ALPHA / DIGIT / and "!", "#", "$", "%", "&", "'", "*",
        //        "+", "-", "/", "=", "?", "^", "_", "`", "{", "|", "}", "~"
        $atext = 'a-zA-Z0-9\x21\x23\x24\x25\x26\x27\x2a\x2b\x2d\x2f\x3d\x3f\x5e\x5f\x60\x7b\x7c\x7d\x7e';
        if (preg_match('/^[' . $atext . ']+(\x2e+[' . $atext . ']+)*$/', $this->_localPart)) {
            $localResult = true;
        } else {
            // Try quoted string format

            // Quoted-string characters are: DQUOTE *([FWS] qtext/quoted-pair) [FWS] DQUOTE
            // qtext: Non white space controls, and the rest of the US-ASCII characters not
            //   including "\" or the quote character
            $noWsCtl    = '\x01-\x08\x0b\x0c\x0e-\x1f\x7f';
            $qtext      = $noWsCtl . '\x21\x23-\x5b\x5d-\x7e';
            $ws         = '\x20\x09';
            if (preg_match('/^\x22([' . $ws . $qtext . '])*[$ws]?\x22$/', $this->_localPart)) {
                $localResult = true;
            } else {
                $this->_error(self::DOT_ATOM);
                $this->_error(self::QUOTED_STRING);
                $this->_error(self::INVALID_LOCAL_PART);
            }
        }

        // If both parts valid, return true
        if ($localResult && $hostnameResult && $length) {
            return true;
        } else {
            return false;
        }
    }
}
