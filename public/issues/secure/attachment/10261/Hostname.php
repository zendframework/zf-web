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
 * @version    $Id: Hostname.php 3429 2007-02-15 14:19:43Z studio24 $
 */


/**
 * @see Zend_Validate_Interface
 */
require_once 'Zend/Validate/Interface.php';


/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_Hostname implements Zend_Validate_Interface
{
    /**
     * Allows Internet domain names (e.g., example.com)
     */
    const ALLOW_DNS   = 1;

    /**
     * Allows IP addresses
     */
    const ALLOW_IP    = 2;

    /**
     * Allows local network names (e.g., localhost, www.localdomain)
     */
    const ALLOW_LOCAL = 4;

    /**
     * Allows all types of hostnames
     */
    const ALLOW_ALL   = 7;

    /**
     * Default regular expression for local network name validation
     * @todo 0.9 Check allowed characters for a local hostname and possibly deprecate this constant
     */
    const REGEX_LOCAL_DEFAULT = '/^(?:[^\W_](?:[^\W_]|-){0,61}[^\W_]\.)*(?:[^\W_](?:[^\W_]|-){0,61}[^\W_])\.?$/';

    /**
     * Bit field of ALLOW constants; determines which types of hostnames are allowed
     *
     * @var integer
     */
    protected $_allow;

    /**
     * Array of regular expressions used for validation
     *
     * @var array
     */
     protected $_regex = array(
        'local' => self::REGEX_LOCAL_DEFAULT
        );

    /**
     * Array of validation failure messages
     *
     * @var array
     */
    protected $_messages = array();

    /**
     * Array of valid top-level-domains
     *
     * @var array
     * @see ftp://data.iana.org/TLD/tlds-alpha-by-domain.txt  List of all TLDs by domain
     */
    protected $_validTlds = array(
        'ac', 'ad', 'ae', 'aero', 'af', 'ag', 'ai', 'al', 'am', 'an', 'ao',
        'aq', 'ar', 'arpa', 'as', 'at', 'au', 'aw', 'ax', 'az', 'ba', 'bb',
        'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'biz', 'bj', 'bm', 'bn', 'bo',
        'br', 'bs', 'bt', 'bv', 'bw', 'by', 'bz', 'ca', 'cat', 'cc', 'cd',
        'cf', 'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co', 'com', 'coop',
        'cr', 'cu', 'cv', 'cx', 'cy', 'cz', 'de', 'dj', 'dk', 'dm', 'do',
        'dz', 'ec', 'edu', 'ee', 'eg', 'er', 'es', 'et', 'eu', 'fi', 'fj',
        'fk', 'fm', 'fo', 'fr', 'ga', 'gb', 'gd', 'ge', 'gf', 'gg', 'gh',
        'gi', 'gl', 'gm', 'gn', 'gov', 'gp', 'gq', 'gr', 'gs', 'gt', 'gu',
        'gw', 'gy', 'hk', 'hm', 'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il',
        'im', 'in', 'info', 'int', 'io', 'iq', 'ir', 'is', 'it', 'je', 'jm',
        'jo', 'jobs', 'jp', 'ke', 'kg', 'kh', 'ki', 'km', 'kn', 'kr', 'kw',
        'ky', 'kz', 'la', 'lb', 'lc', 'li', 'lk', 'lr', 'ls', 'lt', 'lu',
        'lv', 'ly', 'ma', 'mc', 'md', 'mg', 'mh', 'mil', 'mk', 'ml', 'mm',
        'mn', 'mo', 'mobi', 'mp', 'mq', 'mr', 'ms', 'mt', 'mu', 'museum', 'mv',
        'mw', 'mx', 'my', 'mz', 'na', 'name', 'nc', 'ne', 'net', 'nf', 'ng',
        'ni', 'nl', 'no', 'np', 'nr', 'nu', 'nz', 'om', 'org', 'pa', 'pe',
        'pf', 'pg', 'ph', 'pk', 'pl', 'pm', 'pn', 'pr', 'pro', 'ps', 'pt',
        'pw', 'py', 'qa', 're', 'ro', 'ru', 'rw', 'sa', 'sb', 'sc', 'sd',
        'se', 'sg', 'sh', 'si', 'sj', 'sk', 'sl', 'sm', 'sn', 'so', 'sr',
        'st', 'su', 'sv', 'sy', 'sz', 'tc', 'td', 'tf', 'tg', 'th', 'tj',
        'tk', 'tl', 'tm', 'tn', 'to', 'tp', 'tr', 'travel', 'tt', 'tv', 'tw',
        'tz', 'ua', 'ug', 'uk', 'um', 'us', 'uy', 'uz', 'va', 'vc', 've',
        'vg', 'vi', 'vn', 'vu', 'wf', 'ws', 'ye', 'yt', 'yu', 'za', 'zm',
        'zw'
        );

    /**
     * Sets validator options
     *
     * @param  integer $allow
     * @return void
     * @see http://www.iana.org/cctld/specifications-policies-cctlds-01apr02.htm  Technical Specifications for ccTLDs
     */
    public function __construct($allow = self::ALLOW_DNS)
    {
        $this->setAllow($allow);
    }

    /**
     * Returns the allow option
     *
     * @return integer
     */
    public function getAllow()
    {
        return $this->_allow;
    }

    /**
     * Sets the allow option
     *
     * @param  integer $allow
     * @return Zend_Validate_Hostname Provides a fluent interface
     */
    public function setAllow($allow)
    {
        $this->_allow = $allow;
        return $this;
    }

    /**
     * Returns the regular expression used for validating $type hostnames
     *
     * @param  string $type
     * @return string
     */
    public function getRegex($type)
    {
        return $this->_checkRegexType($type)->_regex[$type];
    }

    /**
     * Enter description here...
     *
     * @param  string $type
     * @param  string $pattern
     * @return Zend_Validate_Hostname Provides a fluent interface
     */
    public function setRegex($type, $pattern)
    {
        $this->_checkRegexType($type)->_regex[$type] = $pattern;
        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if the $value is a valid hostname with respect to the current allow option
     *
     * @param  mixed $value
     * @throws Zend_Validate_Exception if a fatal error occurs for validation process
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_messages = array();

        // Check input against IP address schema
        /**
         * @see Zend_Validate_Ip
         */
        require_once 'Zend/Validate/Ip.php';
        $ip = new Zend_Validate_Ip();
        if ($ip->isValid($value)) {
            if (!($this->_allow & self::ALLOW_IP)) {
                $this->_messages[] = "'$value' appears to be an IP address but IP addresses are not allowed";
                return false;
            } else{
                return true;
            }
        }

        // Check input against DNS hostname schema
        $domainParts = explode('.', $value);
        if ((count($domainParts) > 1) && (strlen($value) >= 4) && (strlen($value) <= 254)) {
            $status = false;
            
            do {
                // First check TLD
                if (preg_match('/([a-z]{2,10})$/i', end($domainParts), $matches)) {

                    reset($domainParts);

                    // Hostname characters are: *(label dot)(label dot label); max 254 chars
                    // label: id-prefix [*ldh{61} id-prefix]; max 63 chars
                    // id-prefix: alpha / digit
                    // ldh: alpha / digit / dash

                    // Match TLD against known list
                    $valueTld = strtolower($matches[1]);
                    if (!in_array($valueTld, $this->_validTlds)) {
                        $this->_messages[] = "'$value' appears to be a DNS hostname but cannot match TLD against known list";
                        $status = false;
                        break;
                    }

                    /**
                     * @todo 0.9 ZF-881 Implement UTF-8 support for IDN characters allowed in some TLD hostnames, i.e. bürger.de
                     */
                    
                    // Keep label regex short to avoid issues with long patterns when matching IDN hostnames
                    $labelChars = 'a-zA-Z0-9';
                    $regexLabel = '/^[' . $labelChars . '\x2d]{1,63}$/';
                    
                    // Check each hostname part
                    $valid = true;
                    foreach ($domainParts as $domainPart) {

                        // Check dash (-) does not start, end or appear in 3rd and 4th positions
                        if (strpos($domainPart, '-') === 0 || 
                        (strlen($domainPart) > 2 && strpos($domainPart, '-', 2) == 2 && strpos($domainPart, '-', 3) == 3) ||
                        strrpos($domainPart, '-') === strlen($domainPart) - 1) {

                            $this->_messages[] = "'$value' appears to be a DNS hostname but contains a dash(-) " .  
                                                 "in an invalid position";
                            $status = false;
                            break 2;
                        }

                        // Check each domain part
                        $status = @preg_match($regexLabel, strtolower($domainPart));
                        if ($status === false) {
                            /**
                             * Regex error
                             * @see Zend_Validate_Exception
                             */
                            require_once 'Zend/Validate/Exception.php';
                            throw new Zend_Validate_Exception('Internal error: DNS validation failed');
                        } elseif ($status === 0) {
                            $valid = false;
                        }
                    }

                    // If all labels didn't match, the hostname is invalid
                    if (!$valid) {
                        $this->_messages[] = "'$value' appears to be a DNS hostname but cannot match against " .  
                                             "hostname schema for TLD '$valueTld'";
                        $status = false;
                    }

                } else {
                    // Hostname not long enough
                    $this->_messages[] = "'$value' appears to be a DNS hostname but cannot extract TLD part";
                    $status = false;
                }
            } while (false);
            
            // If the input passes as an Internet domain name, and domain names are allowed, then the hostname
            // passes validation
            if ($status && ($this->_allow & self::ALLOW_DNS)) {
                return true;
            }
        } else {
            $this->_messages[] = "'$value' does not match the expected structure for a DNS hostname";
        }
        
        // Check input against local network name schema; last chance to pass validation
        $status = @preg_match($this->_regex['local'], $value);
        if (false === $status) {
            /**
             * Regex error
             * @see Zend_Validate_Exception
             */
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception('Internal error: local network name validation failed');
        }

        // If the input passes as a local network name, and local network names are allowed, then the
        // hostname passes validation
        $allowLocal = $this->_allow & self::ALLOW_LOCAL;
        if ($status && $allowLocal) {
            return true;
        }

        // If the input does not pass as a local network name, add a message
        if (!$status) {
            $this->_messages[] = "'$value' does not appear to be a valid local network name";
        }

        // If local network names are not allowed, add a message
        if (!$allowLocal) {
            $this->_messages[] = "'$value' appears to be a local network name but but local network names are not allowed";
        }

        return false;
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

    /**
     * Throws an exception if a regex for $type does not exist
     *
     * @param  string $type
     * @throws Zend_Validate_Exception
     * @return Zend_Validate_Hostname Provides a fluent interface
     */
    protected function _checkRegexType($type)
    {
        if (!isset($this->_regex[$type])) {
            /**
             * @see Zend_Validate_Exception
             */ 
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception("'$type' must be one of ('" . implode(', ', array_keys($this->_regex))
                                            . "')");
        }
        return $this;
    }
}
