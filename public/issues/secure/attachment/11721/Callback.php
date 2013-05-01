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
    protected $_position = 0;

    /**
     * Instantiates the callback filter.
     *
     * @param   $callback   The callback function/method.
     * @param   $params     Additional parameters to be sent to the callback function/method.
     * @param   $position   The position of the value in the parameter list.
     */
    public function __construct($callback, array $params = array(), $position = 0)
    {
        $this->setCallback($callback)
             ->setParams($params)
             ->setPosition($position);
    }

    /**
     * Validates the value using the callback function/method.
     *
     * @param   string  $value  The value to validate.
     *
     * @return  bool    If the value is valid.
     */
    public function isValid($value)
    {
        $this->_setValue((string) $value);

        $params     = $this->getParams();
        $numParams  = count($params);

        if ($numParams <= $this->getPosition()) {
            for ($i = $numParams; $i < $this->getPosition(); $i++) {
                $params[$i] = null;
            }
        } else {
            for ($i = $numParams; $i > $this->getPosition(); $i--) {
                $params[$i] = $params[$i - 1];
            }
        }

        $params[$this->getPosition()] = $value;

        try {
            if (!call_user_func_array($this->getCallback(), $params)) {
                $this->_error();

                return false;
            }
        } catch (Exception $e) {
            $this->_error();

            return false;
        }

        return true;
    }

    /**
     * Sets the callback function/method.
     *
     * @param   callback    $callback   A callback function/method.
     *
     * @throws  Zend_Validate_Exception If the callback is invalid.
     *
     * @return  Zend_Validate_Callback
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback)) {
            /**
             * @see Zend_Validate_Exception
             */
            require_once('Zend/Validate/Exception.php');
            throw new Zend_Validate_Exception('Invalid callback');
        }

        $this->_callback = $callback;

        return $this;
    }

    /**
     * Returns the callback function/method.
     *
     * @return  mixed   A callback function/method.
     */
    public function getCallback()
    {
        return $this->_callback;
    }

    /**
     * Sets the additional parameters to send to the callback function/method.
     *
     * @param   array   $params
     *
     * @return  Zend_Validate_Callback
     */
    public function setParams(array $params)
    {
        $this->_params = array_values($params);

        return $this;
    }

    /**
     * Returns the additional parameters to send to the callback function/method.
     *
     * @return  array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Sets the position of the value in the parameter list.
     *
     * @param   int $position
     *
     * @return  Zend_Validate_Callback
     */
    public function setPosition($position)
    {
        $this->_position = (int) $position;

        return $this;
    }

    /**
     * Returns the position of the value in the parameter list.
     *
     * @return  int
     */
    public function getPosition()
    {
        return $this->_position;
    }
}
