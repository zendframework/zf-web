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
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: EmailAddress.php 3827 2007-03-08 18:26:49Z darby $
 */


/**
 * @see Zend_Validate_Interface
 */
require_once 'Zend/Validate/Interface.php';


/**
 * @see Zend_Validate_Hostname
 */
require_once 'Zend/Validate/Hostname.php';


/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_EmailAddress implements Zend_Validate_Interface
{
    /**
     * Array of validation failure messages
     *
     * @var array
     */
    protected $_messages = array();

    /**
     * Local object for validating the hostname part of an email address
     *
     * @var Zend_Validate_Hostname
     */
    protected $_hostnameValidator;

    /**
     * Whether we check for a valid MX record via DNS
     *
     * @var boolean
     */
    protected $_mxCheck = false;
    
    /**
     * Instantiates hostname validator for local use
     *
     * You can pass a bitfield to determine what types of hostnames are allowed.
     * These bitfields are defined by the ALLOW_* constants in Zend_Validate_Hostname
     * The default is to allow DNS hostnames only
     *
     * @see Zend_Validate_Hostname
     * @param integer $allow
     * @return void
     */
    public function __construct($allow = Zend_Validate_Hostname::ALLOW_DNS, $mxCheck = false)
    {
        // Initialise Zend_Validate_Hostname
        $this->_hostnameValidator = new Zend_Validate_Hostname($allow);
        
        $this->_mxCheck = $mxCheck;
    }   
    
    /**
     * Whether MX checking via dns_get_mx is supported or not
     * 
     * This currently only works on UNIX systems
     *
     * @return boolean
     */
    public function mxSupported ()
    {
        return (($this->_mxCheck) && (function_exists('dns_get_mx')));
    }
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is a valid email address
     * according to RFC2822
     *
     * @link http://www.ietf.org/rfc/rfc2822.txt RFC2822
     * @link http://www.columbia.edu/kermit/ascii.html US-ASCII characters
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_messages = array();

        // Split email address up
        if (!preg_match('/^(.+)@([^@]+)$/', $value, $matches)) {
            $this->_messages[] = "'$value' is not a valid email address in the basic format local-part@hostname";
            return false;
        }

        $localPart	= $matches[1];
        $hostname 	= $matches[2];
        
        // Match hostname part
        $hostnameResult = $this->_hostnameValidator->isValid($hostname);
        if (!$hostnameResult) {
            $this->_messages[] = "'$hostname' is not a valid hostname for email address '$value'";

            // Get messages from hostnameValidator
            foreach ($this->_hostnameValidator->getMessages() as $message) {
                $this->_messages[] = $message;
            }
        }

        // MX check on hostname via dns_get_record()
        if ($this->mxSupported()) {
            $result = dns_get_mx($hostname, $mxHosts); 
            print "checking $hostname MX: ";
            var_dump($result, $mxHosts);
            if (count($result) < 1) {
                $hostnameResult = false;
                $this->_messages[] = "'$hostname' does not appear to have a valid MX record for the email address '$value'";
            }
        }
        
        // First try to match the local part on the common dot-atom format
        $localResult = false;

        // Dot-atom characters are: 1*atext *("." 1*atext)
        // atext: ALPHA / DIGIT / and "!", "#", "$", "%", "&", "'", "*",
        //        "-", "/", "=", "?", "^", "_", "`", "{", "|", "}", "~"
        $atext = 'a-zA-Z0-9\x21\x23\x24\x25\x26\x27\x2a\x2b\x2d\x2f';
        $atext .= '\x3d\x3f\x5e\x5f\x60\x7b\x7c\x7d';
        if (preg_match('/^[' . $atext . ']+(\x2e+[' . $atext . ']+)*$/', $localPart)) {
            $localResult = true;
        } else {
             $this->_messages[] = "'$localPart' not matched against dot-atom format";
        }

        // If not matched, try quoted string format
        if (!$localResult) {

            // Quoted-string characters are: DQUOTE *([FWS] qtext/quoted-pair) [FWS] DQUOTE
            // qtext: Non white space controls, and the rest of the US-ASCII characters not
            //   including "\" or the quote character
            $noWsCtl    = '\x01-\x08\x0b\x0c\x0e-\x1f\x7f';
            $qtext      = $noWsCtl . '\x21\x23-\x5b\x5d-\x7e';
            $ws         = '\x20\x09';
            if (preg_match('/^\x22([' . $ws . $qtext . '])*[$ws]?\x22$/', $localPart)) {
                $localResult = true;
            } else {
                $this->_messages[] = "'$localPart' not matched against quoted-string format";
            }
        }

        if (!$localResult) {
             $this->_messages[] = "'$localPart' is not a valid local part for email address '$value'";
        }

        // If both parts valid, return true
        if ($localResult && $hostnameResult) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns array of validation failure messages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }

}
