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
 * @version    $Id:$
 */

/**
 * Zend_Validate_StringLength
 */
require_once 'Zend/Validate/StringLength.php';

/**
 * Zend_Validate_Digits
 */
require_once 'Zend/Validate/Digits.php';

/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Validate_Isbn implements Zend_Validate_Interface
{
    /**
     * Sets ISBN-10 format
     */
    const ISBN10 = 10;
    
    /**
     * Sets ISBN-13 format
     */
    const ISBN13 = 13;
    
    /**
     * @acces protected
     * @var   integer
     */
    protected $_allowVersion = self::ISBN13;
    
    /**
     * Sets the allow version.
     *
     * @access public
     * @param  integer $version
     * @return bool
     */
    public function allowVersion($version)
    {
        if (($version != self::ISBN10) && ($version != self::ISBN13)) {
            $this->_messages[] = "unknown isbn version given";
            return false;
        }
        $this->_allowVersion = $version;
        return true;
    }
    
    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if the given value is a valid ISBN-10 or ISBN-13.
     * http://en.wikipedia.org/wiki/Isbn
     * http://www.isbn-international.org/
     * 
     * @access public
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $valueString = (string) $value;
        
        if ($this->_allowVersion == self::ISBN10) {
            return $this->_validateIsbn10($valueString);
        } else if ($this->_allowVersion == self::ISBN13) {
            return $this->_validateIsbn13($valueString);
        }
        
        $this->_messages[] = "invalid isbn version set";
        return false;
    }
    
    /**
     * Validates ISBN-10
     * 
     * @access protected
     * @param  string $value
     * @return bool
     */
    protected function _validateIsbn10($value)
    {
        $stringLength = new Zend_Validate_StringLength(10, 10);
        if (!$stringLength->isValid($value)) {
            $this->_messages[] = "'" . $value . "' must have 10 digits";
            return false;
        }
        
        $digits = new Zend_Validate_Digits();
        if (!$digits->isValid($value)) {
            $this->_messages[] = "'" . $value . "' contains invalid characters";
            return false;
        }
        
        $remainder = $value[9];
        $checksum  = 0;
        
        for ($i=10, $a=0; $i>1; $i--, $a++)
        {
            $digit     = (int) $value[$a];
            $checksum += $digit * $i;
        }
        
        $valide = ((11 - ($checksum % 11)) == $remainder);
        
        if (!$valide) {
            $this->_messages[] = "'" . $value . "' does not appear to be a valid isbn10";
        }
        
        return $valide;
    }
    
    /**
     * Validates ISBN-13
     * 
     * @access protected
     * @param  string $value
     * @return bool
     */
    protected function _validateIsbn13($value)
    {
        $stringLength = new Zend_Validate_StringLength(13, 13);
        if (!$stringLength->isValid($value)) {
            $this->_messages[] = "'" . $value . "' must have 13 digits";
            return false;
        }
        
        $digits = new Zend_Validate_Digits();
        if (!$digits->isValid($value)) {
            $this->_messages[] = "'" . $value . "' contains invalid characters";
            return false;
        }
        
        $remainder = $value[12];
        $checksum  = 0;
        
        for ($i=0; $i<12; $i++)
        {
            $multi     = (($i % 2) == 1) ? 1 : 3;
            $digit     = (int) $value[$i];
            $checksum += $digit * $multi;
        }
        
        $valide = ((10 - ($checksum % 10)) == $remainder);
        
        if (!$valide) {
            $this->_messages[] = "'" . $value . "' does not appear to be a valid isbn13";
        }
        
        return $valide;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns array of validation failure messages
     * 
     * @access public
     * @return array
     */
    public function getMessages()
    {
        return $this->_messages;
    }
}