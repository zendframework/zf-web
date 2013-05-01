<?php

require_once 'Zend/Filter/Interface.php';

class Zend_Filter_StringPad implements Zend_Filter_Interface
{
    const TYPE_BOTH  = 'both';
    const TYPE_LEFT  = 'left';
    const TYPE_RIGHT = 'right';

    /**
     * Charset
     *
     * @var string
     */
    protected $_charset;
    /**
     * Padding length
     *
     * @var string
     */
    protected $_length;
    /**
     * Padding characters
     *
     * @var string
     */
    protected $_string;
    /**
     * Padding type
     *
     * @var string
     */
    protected $_type;

    public function __construct($length, $string = ' ', $type = self::TYPE_RIGHT, $charset = 'UTF-8') {
        $this->setLength($length);
        $this->setString($string);
        $this->setType($type);
        $this->setCharset($charset);
    }

    /**
     * Retrieve charset
     *
     * @return string
     */
    public function getCharset() {
        return $this->_charset;
    }

    /**
     * Retrieve padding length
     *
     * @return int
     */
    public function getLength() {
        return $this->_length;
    }

    /**
     * Retrieve padding charackters
     *
     * @return string
     */
    public function getString() {
        return $this->_string;
    }

    /**
     * Retrieve padding type
     *
     * @return string
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Set charset
     *
     * @param  string $charset
     * @return Zend_Filter_StringPad
     */
    public function setCharset($charset) {
        $this->_charset = (string) $charset;
        return $this;
    }

    /**
     * Set padding length
     *
     * @param  int $length
     * @return Zend_Filter_StringPad
     */
    public function setLength($length) {
        $this->_length = (int) $length;
        return $this;
    }

    /**
     * Set padding characters
     *
     * @param  string $string
     * @return Zend_Filter_StringPad
     */
    public function setString($string) {
        $this->_string = (string) $string;
        return $this;
    }

    /**
     * Set padding type
     *
     * @param  string $type
     * @return Zend_Filter_StringPad
     */
    public function setType($type)
    {
        switch ($type)
        {
            case self::TYPE_BOTH:
            case self::TYPE_LEFT:
            case self::TYPE_RIGHT:
                $this->_type = $type;
                break;
            default:
                throw new Zend_Filter_Exception('Pad type is invalid');
        }

        return $this;
    }

    /**
     * Pad a string to a certain length with another string
     *
     * @param  string $value
     * @return string
     */
    public function filter($value) {
        $return = '';
        $lengthOfPadding = $this->_length - iconv_strlen($value, $this->_charset);
        $padStringLength = iconv_strlen($this->_string, $this->_charset);

        if (0 === $padStringLength || 0 >= $lengthOfPadding) {
            $return = $value;
        } else {
            $repeatCount = floor($lengthOfPadding / $padStringLength);

            if (self::TYPE_BOTH  === $this->_type) {
                $lastStringLeft  = '';
                $lastStringRight = '';
                $repeatCountLeft = $repeatCountRight = ($repeatCount - $repeatCount % 2) / 2;

                $lastStringLength       = $lengthOfPadding - 2 * $repeatCountLeft * $padStringLength;
                $lastStringLeftLength   = $lastStringRightLength = floor($lastStringLength / 2);
                $lastStringRightLength += $lastStringLength % 2;

                $lastStringLeft  = iconv_substr($this->_string, 0, $lastStringLeftLength, $this->_charset);
                $lastStringRight = iconv_substr($this->_string, 0, $lastStringRightLength, $this->_charset);

                $return = str_repeat($this->_string, $repeatCountLeft) . $lastStringLeft
                        . $value
                        . str_repeat($this->_string, $repeatCountRight) . $lastStringRight;
            } else {
                $lastString = iconv_substr($this->_string, 0, $lengthOfPadding % $padStringLength, $this->_charset);

                if (self::TYPE_LEFT === $this->_type) {
                    $return = str_repeat($this->_string, $repeatCount) . $lastString . $value;
                } else {
                    $return = $value . str_repeat($this->_string, $repeatCount) . $lastString;
                }
            }
        }

        return $return;
    }
}

?>