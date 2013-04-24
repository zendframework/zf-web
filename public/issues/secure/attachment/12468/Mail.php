<?php

/**
 * resources.mail.type = smtp
 * resources.mail.hostname = "smtp.myServer.com"
 * resources.mail.params.auth = login
 * resources.mail.params.username = myUsername
 * resources.mail.params.password = myPassword
 * resources.mail.isDefaultMailTransport = true
 */

/**
 * Resource for initializing mail transport
 * @author dcaunt http://gist.github.com/251583
 */
class My_Application_Resource_Mail extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Transport type
     *
     * @var string
     */
    protected $_type = null;

    /**
     * SMTP server hostname
     *
     * @var string
     */
    protected $_hostname = null;

    /**
     * Whether to register the created transport as default mail transport
     *
     * @var boolean
     */
    protected $_isDefaultMailTransport = true;

    /**
     * @var Zend_Mail_Transport_Abstract
     */
    protected $_transport;

    /**
     * Parameters to use
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Set the adapter
     *
     * @param  $hostname string
     * @return My_Application_Resource_Mail
     */
    public function setHostname($hostname)
    {
        $this->_hostname = $hostname;
        return $this;
    }

    /**
     * SMTP hostname
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->_hostname;
    }

    /**
     * Set the transport params
     *
     * @param  $params array
     * @return My_Application_Resource_Mail
     */
    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    /**
     * Transport type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set the transport type
     *
     * @param  $adapter string
     * @return My_Application_Resource_Mail
     */
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * Transport parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Set whether to use this as default table adapter
     *
     * @param  boolean $isDefaultMailTransport
     * @return My_Application_Resource_Mail
     */
    public function setIsDefaultMailTransport($isDefaultMailTransport)
    {
        $this->_isDefaultMailTransport = $isDefaultMailTransport;
        return $this;
    }

    /**
     * Is this transport the default mail transport
     *
     * @return void
     */
    public function isDefaultMailTransport()
    {
        return $this->_isDefaultMailTransport;
    }

    /**
     *
     * @return Zend_Mail_Transport_Abstract|null
     */
    protected function _createTransport()
    {
        $type = $this->getType();
        if ('sendmail' == $type){
            $transport = new Zend_Mail_Transport_Sendmail();
        }else if ('smtp' == $type){
            $transport = new Zend_Mail_Transport_Smtp($this->getHostname(),
                $this->getParams());
        }

        return $transport;
    }

    /**
     *
     * @return Zend_Mail_Transport_Abstract|null
     */
    public function init()
    {

        if (null !== ($transport = $this->_createTransport())) {
            if ($this->isDefaultMailTransport()) {
                Zend_Mail::setDefaultTransport($transport);
            }
            return $transport;
        }

    }
}