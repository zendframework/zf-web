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
 * @package    Zend_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Session.php 16200 2009-06-21 18:50:06Z thomas $
 */

/**
 * Resource for setting session options
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   Zend
 * @package    Zend_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Application_Resource_Session extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Save handler to use
     *
     * @var Zend_Session_SaveHandler_Interface
     */
    protected $_saveHandler = null;

    /**
     * Save handler class
     *
     * @var array|string|Zend_Session_SaveHandler_Interface
     */
    protected $_saveHandlerClass = null;

    /**
     * Set session save handler
     *
     * @param  array|string|Zend_Session_SaveHandler_Interface $saveHandler
     * @return Zend_Application_Resource_Session
     * @throws Zend_Application_Resource_Exception When $saveHandler is no valid save handler
     */
    public function setSaveHandler($saveHandler)
    {
        $this->_saveHandlerClass = $saveHandler;

        return $this;
    }

    private function _initSaveHandler()
    {
        if (is_array($this->_saveHandlerClass)) {
            if (!array_key_exists('class', $this->_saveHandlerClass)) {
                throw new Zend_Application_Resource_Exception('Session save handler class not provided in options');
            }
            if (array_key_exists('options', $this->_saveHandlerClass)) {
                $options = $this->_saveHandlerClass['options'];
            }
            $saveHandler = $this->_saveHandlerClass['class'];
            $saveHandler = new $saveHandler($options);
        } elseif (is_string($this->_saveHandlerClass)) {
            $saveHandler = new $this->_saveHandlerClass();
        }

        if (!$saveHandler instanceof Zend_Session_SaveHandler_Interface) {
            throw new Zend_Application_Resource_Exception('Invalid session save handler');
        }

        $this->_saveHandler = $saveHandler;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return void
     */
    public function init()
    {
        $options = array_change_key_case($this->getOptions(), CASE_LOWER);
        if (isset($options['savehandler'])) {
            unset($options['savehandler']);
        }

        if (count($options) > 0) {
            Zend_Session::setOptions($options);
        }

        $this->_initSaveHandler();

        if ($this->_saveHandler !== null) {
            Zend_Session::setSaveHandler($this->_saveHandler);
        }
    }
}
