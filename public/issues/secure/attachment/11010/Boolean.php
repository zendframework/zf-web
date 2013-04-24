<?php

class Zend_Filter_Boolean implements Zend_Filter_Interface
{
    /**
     * Values and their boolean counterparts.
     *
     * @var array
     */
    protected $_values = array(
        't'     => true,
        'true'  => true,
        'y'     => true,
        'yes'   => true,
        '1'     => true,
        'f'     => false,
        'false' => false,
        'n'     => false,
        'no'    => false,
        '0'     => false,
    );

    /**
     * Locale values and their boolean counterparts.
     *
     * @var array
     */
    protected $_localeValues = array();

    /**
     * The value if a match isn't found.
     *
     * @var bool
     */
    protected $_default = false;

    /**
     * Constructs a boolean filter.
     *
     * @param bool $default
     * @param Zend_Locale|string $locale
     */
    public function __construct($default = null, $locale = null)
    {
        if (!is_null($default)) {
            $this->_default = (bool) $default;
        }

        $this->setLocale($locale);
    }

    /**
     * Sets the locale.
     *
     * @param Zend_Locale|string|null
     * @return Jrm_Filter_Boolean
     */
    public function setLocale($locale)
    {
        $this->_localeValues = array();

        if (!is_null($locale)) {
            if (!class_exists('Zend_Locale')) {
                require_once 'Zend/Locale.php';
            }

            if (!$locale instanceof Zend_Locale) {
                $locale = new Zend_Locale($locale);
            }

            $q                      = $locale->getQuestion();
            $this->_localeValues    = array(
                $q['yes']           => true,
                $q['yesabbr']       => true,
                $q['no']            => false,
                $q['noabbr']        => false,
            );
        }

        return $this;
    }

    /**
     * Adds a value to the list.
     *
     * @param mixed $value
     * @param bool $bool
     * @return Jrm_Filter_Boolean
     */
    public function addValue($value, $bool)
    {
        if (!is_scalar($value)) {
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception('$value must be a scalar');
        }

        $value  = trim($value);
        $bool   = (bool) $bool;

        if (function_exists('mb_strtolower')) {
            $value = mb_strtolower($value);
        } else {
            $value = strtolower($value);
        }

        $this->_values[$value] = $bool;

        return $this;
    }

    /**
     * Casts value to a boolean.
     *
     * @param mixed $value
     * @return bool
     */
    public function filter($value)
    {
        if (!is_scalar($value)) {
            return $this->_default;
        }

        if (is_bool($value) || is_null($value)) {
            return (bool) $value;
        }

        $value = trim($value);

        if (function_exists('mb_strtolower')) {
            $value = mb_strtolower($value);
        } else {
            $value = strtolower($value);
        }

        if (isset($this->_localeValues[$value])) {
            return $this->_localeValues[$value];
        }

        if (isset($this->_values[$value])) {
            return $this->_values[$value];
        }

        return $this->_default;
    }
}
