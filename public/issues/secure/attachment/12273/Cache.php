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
 * @version    
 */

/**
 * @see Zend_Application_Resource_ResourceAbstract
 */
require_once 'Zend/Application/Resource/ResourceAbstract.php';

/**
 * cache resource
 *
 * @category   Zend
 * @package    Zend_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{
    const OPTION_CLASS = 'class';
    const OPTION_PARAMS = 'params';
    /**
     * options for frontend
     * 
     * @var array
     */
    protected $_frontendOptions;
    
    /**
     * options for backend
     * 
     * @var array
     */
    protected $_backendOptions;
    
    /**
     * Wether to share the cache object
     * to all Zend objects that accept one statically
     * 
     * @var bool
     */
    protected $_shareToZendObjects = false;
    
    /**
     * cache object
     * @var Zend_Cache_Core
     */
    protected $_cache;
    
    /**
     * set the options for frontend
     * 
     * @param array $params
     * @return Zend_Application_Resource_Cache
     */
    public function setFrontEnd(array $params)
    {
        if(!isset($params[self::OPTION_CLASS])) {
            throw new Zend_Application_Resource_Exception("frontend class has not been defined");
        }
        $this->_frontendOptions = $params;
        return $this;
    }
    
    /**
     * set the options for backend
     * 
     * @param array $params
     * @return Zend_Application_Resource_Cache
     */
    public function setBackend(array $params)
    {
        if(!isset($params[self::OPTION_CLASS])) {
            throw new Zend_Application_Resource_Exception("backend class has not been defined");
        }
        $this->_backendOptions = $params; 
    }
    
    /**
     * retrieve the class name used as frontend
     * 
     * @return string
     */
    public function getFrontendClassName()
    {
        return $this->_frontendOptions[self::OPTION_CLASS];
    }
    
    /**
     * retrieve the class name used as backend
     * 
     * @return string
     */
    public function getBackendClassName()
    {
        return $this->_backendOptions[self::OPTION_CLASS];
    }
    
    /**
     * Defined by Zend_Application_Resource_Resource
     * 
     * @return Zend_Cache_Core
     */
    public function init()
    {
        if ($this->_frontendOptions == null || $this->_backendOptions == null) {
            throw new Zend_Application_Resource_Exception("frontend and backend class names must be defined");
        }
        
        $frontendOptions = $backendOptions = array();
        if (isset($this->_frontendOptions[self::OPTION_PARAMS]) && is_array($this->_frontendOptions[self::OPTION_PARAMS])) {
            $frontendOptions = $this->_frontendOptions[self::OPTION_PARAMS];
        }
        if (isset($this->_backendOptions[self::OPTION_PARAMS]) && is_array($this->_backendOptions[self::OPTION_PARAMS])) {
            $backendOptions = $this->_backendOptions[self::OPTION_PARAMS];
        }
        
        $this->_cache = Zend_Cache::factory($this->getFrontendClassName(),
                                            $this->getBackendClassName(),
                                            $frontendOptions,
                                            $backendOptions);
        if ($this->_shareToZendObjects) {
            $this->_shareToZendObjects();
        }
        return $this->_cache;
    }
    
    /**
     * Returns the cache object
     * 
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        return $this->_cache;
    }
    
    /**
     * Wether to share the cache object
     * to all Zend objects that accept one statically
     * 
     * @return Zend_Application_Resource_Cache
     */
    public function setShareToZendObjects()
    {
        $this->_shareToZendObjects = true;
        return $this;
    }
    
    /**
     * Shares the cache instance
     * to all Zend objects that accept one statically
     * 
     * @return Zend_Application_Resource_Cache
     */
    protected function _shareToZendObjects()
    {
        Zend_Paginator::setCache($this->_cache);
        Zend_Db_Table::setDefaultMetadataCache($this->_cache);
        Zend_Date::setOptions(array('cache'=>$this->_cache));
        Zend_Translate::setCache($this->_cache);
        Zend_Locale::setCache($this->_cache);
        
        return $this;
    }
}
