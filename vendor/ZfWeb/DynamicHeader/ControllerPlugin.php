<?php

class ZfWeb_DynamicHeader_ControllerPlugin extends Zend_Controller_Plugin_Abstract
{
    
    protected static $_instance = null;
    
    /**
     * @var ZfWeb_DynamicHeader
     */
    protected $_dynamicHeader = null;
    
    protected $_liveStyleUrl = null;
    protected $_styleUrl = null;
    
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    protected function __construct()
    {
        $this->_dynamicHeader = ZfWeb_DynamicHeader::getInstance();
    }
    
    /**
     * routeStartup - if we are attempting to see a style, check here
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->_liveStyleUrl = $this->_dynamicHeader->getUrl() . 'live-styles.css';
        $this->_styleUrl = ($this->_dynamicHeader->isLearning()) ? 
            $this->_liveStyleUrl : 
            $this->_dynamicHeader->getUrl() . 'styles.css';
        
        // short circuit the style request
        if ($request->getRequestUri() === $this->_liveStyleUrl) {
            $this->_dynamicHeader->generateStylesheet();
            $frontController = Zend_Controller_Front::getInstance();
            $response = $frontController->getResponse();
            $response->setHeader('Content-type:', 'text/css');
            $response->setBody(file_get_contents($this->_dynamicHeader->getDirectory() . 'styles.css'));
            $response->sendResponse();
            exit();
        }
    }
    
    /**
     * dispatchLoopStartup()
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        // append the stylesheet for the images 
        // @todo (could be smarter) this should be done in postDispatch if there are headers (must be done once only)
        $view = $this->_dynamicHeader->getViewHelper()->getView();
        $view->headLink()->appendStylesheet($this->_styleUrl);
    }

    /**
     * dispatchLoopShutdown()
     * 
     * if in learning mode, create any nessessary headers.
     *
     */
    public function dispatchLoopShutdown()
    {
        if ($this->_dynamicHeader->isLearning()) {
            
            $allHeaders = array();
            foreach (new DirectoryIterator($this->_dynamicHeader->getDirectory()) as $file) {
                if (!$file->isDot()) {
                    $allHeaders[] = $file->getFilename();                    
                }
            }

            foreach ($this->_dynamicHeader->getCurrentDynamicHeaders() as $currentHeaderName => $currentHeaderData) {
                if (!in_array($currentHeaderName . '.png', $allHeaders)) {
                    $this->_dynamicHeader->generateImage($currentHeaderName);
                    $this->_dynamicHeader->generateStylesheet();
                }
            }

        }
        
    }
    
}

