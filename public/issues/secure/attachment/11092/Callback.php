<?php

class Zend_Validate_Callback extends Zend_Validate_Abstract
{
    /**
     * @var string
     */
    const INVALID_CALLBACK = 'invalidCallback';

    /**
     * Message templates.
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_CALLBACK => "'%value%' is not valid"
    );

    /**
     * The callback function/method.
     *
     * @var callback
     */
    protected $_callback;

    /**
     * Additional parameters to send to the callback function/method.
     *
     * @var array
     */
    protected $_params = array();

    /**
     * The position of the value in the parameter list.
     *
     * @var int
     */
    protected $_pos = 0;

    /**
     * Instantiates the callback filter.
     *
     * @param   $callback   The callback function/method.
     * @param   $params     Additional parameters to be sent to the callback function/method.
     * @param   $pos        The position of the value in the parameter list.
     */
    public function __construct($callback, array $params = array(), $pos = 0)
    {
        $this->setCallback($callback)->_params  = array_values($params);
        $this->_pos                             = (int) $pos;
    }

    /**
     * Sets the callback function/method.
     *
     * @param   callback    $callback
     *
     * @return  Zend_Validator_Callback
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback)) {
            require_once('Zend/Filter/Exception.php');
            throw new Zend_Filter_Exception('Invalid callback');
        }

        $this->_callback = $callback;

        return $this;
    }

    /**
     * Validates the value using the callback function/method.
     *
     * @param   string  $value
     *
     * @return  bool
     */
    public function isValid($value)
    {
        $valueString = (string) $value;

        $this->_setValue($valueString);

        $params     = $this->_params;
        $numParams  = count($params);

        if ($numParams <= $this->_pos) {
            for ($i = $numParams; $i < $this->_pos; $i++) {
                $params[$i] = null;
            }
        } else {
            for ($i = $numParams; $i > $this->_pos; $i--) {
                $params[$i] = $params[$i - 1];
            }
        }

        $params[$this->_pos] = $value;

        try {
            if (!call_user_func_array($this->_callback, $params)) {
                $this->_error();
                return false;
            }
        } catch (Exception $e) {
            $this->_error();
            return false;
        }

        return true;
    }
}
