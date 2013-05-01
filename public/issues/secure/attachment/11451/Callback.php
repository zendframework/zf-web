<?php
class Zend_Filter_Callback implements Zend_Filter_Interface
{
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
     * Calls the callback function/method.
     *
     * @param   mixed   $value  The value to filter.
     *
     * @return  mixed   The filtered value.
     */
    public function filter($value)
    {
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

        return call_user_func_array($this->_callback, $params);
    }

    /**
     * Sets the callback function/method.
     *
     * @param   callback    $callback   A callback function/method.
     *
     * @throws  Zend_Filter_Exception   If the callback is invalid.
     *
     * @return  Zend_Filter_Callback
     */
    public function setCallback($callback)
    {
        if (!is_callable($callback)) {
            /**
             * @see Zend_Filter_Exception
             */
            require_once('Zend/Filter/Exception.php');
            throw new Zend_Filter_Exception('Invalid callback');
        }

        $this->_callback = $callback;

        return $this;
    }

    /**
     * Returns the callback function/method.
     *
     * @return  mixed   The callback function/method.
     */
    public function getCallback()
    {
        return $this->_callback;
    }
}
