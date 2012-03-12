<?php

/**
 * @see 
 */
require_once 'ZfWeb/DynamicHeader/ControllerPlugin.php';

/**
 * ZfWeb_DynamicHeader component
 * 
 * @author Ralph Schindler <ralph.schindler@zend.com>
 */
class ZfWeb_DynamicHeader
{
    protected static $_instance;
    protected $_controllerPlugin;
    protected $_viewHelper;
    
    protected $_isLearning = false;
    protected $_url;
    protected $_directory;
    
    protected $_defaults = array();
    protected $_sections = array();

    protected $_currentDynamicHeaders = array();
    
    /**
     * Call this method from your bootstrap
     *
     * @param array $config
     * @return unknown
     */
    public static function startMvc(Array $config = array())
    {
        if (self::$_instance !== null) {
            throw new Exception('ZfWeb_DynamicHeader has already been started.');
        }
        
        $dynamicHeader = self::getInstance();
        
        if ($config['options']) {
            $dynamicHeader->setOptions($config['options']);
        }
        
        if ($config['defaults']) {
            $dynamicHeader->setDefaults($config['defaults']);
        }
        
        if ($config['sections']) {
            $dynamicHeader->setSections($config['sections']);
        }
        
        return $dynamicHeader;
    }
    
    /**
     * singleton impelementation
     *
     * @return ZfWeb_DynamicHeader
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
            self::$_instance->getControllerPlugin();
            self::$_instance->getViewHelper();
        }

        return self::$_instance;
    }
    
    protected function __construct()
    {
    }
    
    /**
     * ControllerPlugin - lazy load the controller plugin
     *
     * @return unknown
     */
    public function getControllerPlugin()
    {
        if ($this->_controllerPlugin == null) {
            $this->_controllerPlugin = ZfWeb_DynamicHeader_ControllerPlugin::getInstance();
             Zend_Controller_Front::getInstance()->registerPlugin($this->_controllerPlugin);
        }
        
        return $this->_controllerPlugin;
    }
    
    /**
     * ViewHelper - lazy load the view helper
     *
     * @return unknown
     */
    public function getViewHelper()
    {
        if ($this->_viewHelper === null) {
            $view = Zend_Layout::getMvcInstance()->getView();
            $view->addHelperPath(dirname(__FILE__) . '/DynamicHeader/ViewHelper/', 'ZfWeb_DynamicHeader_ViewHelper_');
            ZfWeb_DynamicHeader_ViewHelper_DynamicHeader::setMvcManager($this);
            $this->_viewHelper = $view->getHelper('dynamicHeader');
        }
        
        return $this->_viewHelper;
    }
    
    public function setOptions($options)
    {
        foreach ($options as $optionName => $optionValue) {
            $setMethod = 'set' . $optionName;
            if (is_callable(array($this, $setMethod))) {
                $this->{$setMethod}($optionValue);
            }
        }
    }
    
    public function setLearning($learning)
    {
        $this->_isLearning = (bool) $learning;
        return $this;
    }
    
    public function isLearning()
    {
        if (isset($_GET['dynamic-header-learning']) || isset($_GET['dhl']) || isset($_COOKIE['dhl'])) {
            
            if (isset($_GET['session-on'])) {
                setcookie('dhl', 'on');
            } elseif ($_GET['session-off']) {
                setcookie('dhl', null);
            }
            
            return true;
        }
        
        return $this->_isLearning;
    }
    
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }
    
    public function getUrl()
    {
        return $this->_url;
    }
    
    public function setDirectory($directory)
    {
        $this->_directory = $directory;
        return $this;
    }
    
    public function getDirectory()
    {
        return $this->_directory;
    }
    
    public function setSections($sections)
    {
        foreach ($sections as $sectionName => $sectionValues) {
            $this->setSection($sectionName, $sectionValues);
        }
    }
    
    public function setSection($name, Array $settings)
    {
        $this->_sections[$name] = $settings;
    }

    public function setDefaults(Array $defaults)
    {
        foreach ($defaults as $defaultName => $defaultValue) {
            $this->setDefault($defaultName, $defaultValue);
        }
    }
    
    public function setDefault($name, $value)
    {
        // check name here.
        $this->_defaults[$name] = $value;
        return $this;
    }
    
    public function getSectionParameters($sectionName)
    {
        $values = $this->_defaults;
        
        if (isset($this->_sections[$sectionName])) {
            $values = array_merge($values, $this->_sections[$sectionName]);
        }
        
        return $values;
    }
    
    public function getCurrentDynamicHeaders()
    {
        return $this->_currentDynamicHeaders;
    }
    
    public function normalizeDynamicHeader($group, $text)
    {
        $groupFilter = new Zend_Filter_Word_CamelCaseToDash();
        
        $textFilter = new Zend_Filter();
        $textFilter->addFilter(new Zend_Filter_Alnum(true))
            ->addFilter(new Zend_Filter_Word_CamelCaseToDash())
            ->addFilter(new Zend_Filter_Word_SeparatorToDash());

        $nodeName = strtolower($groupFilter->filter($group) . '-' . $textFilter->filter($text));
        
        $this->_currentDynamicHeaders[$nodeName] = array('group' => $group, 'text' => $text);
        return $nodeName;
    }

    public function generateImage($dynamicHeaderName)
    {
        $imageGenerator = new ZfWeb_DynamicHeader_ImageGenerator();
        
        $headerInfo = $this->_currentDynamicHeaders[$dynamicHeaderName];
        
        $imageParams = $this->getSectionParameters($headerInfo['group']);
        
        foreach ($imageParams as $imageParamName => $imageParamValue) {
            $method = 'set' . $imageParamName;
            $imageGenerator->{$method}($imageParamValue);
        }
        
        $imageGenerator->setText($headerInfo['text']);
        
        $filePath = $this->getDirectory() . $dynamicHeaderName . '.png';
        
        $output = $imageGenerator->generate('text.png');
        
        file_put_contents($filePath, $output);
    }
    
    /**
     * EDIT this for formatting the stylesheet
     *
     */
    public function generateStylesheet()
    {
        $stylesheetContents = null; //'/* Created dynamically by ' . basename(__FILE__) . ' on ' . date('Y-m-d H:i:s') . '*/' . PHP_EOL . PHP_EOL;
        
        $di = new DirectoryIterator($this->getDirectory());
        foreach ($di as $diItem) {
            if ($diItem->isDot()) continue;
            
            $filename = $diItem->getFilename();
            
            if (substr($filename, -4) == '.png') {
                $section = substr($filename, 0, -4);
                
                $imagePath = $diItem->getPath() . '/' . $filename;
                $imageinfo = getimagesize($imagePath);
                
                $stylesheetContents .= '.' . $section . PHP_EOL . '{' . PHP_EOL
                                     . '    background-image: url(' . $this->getUrl() . $filename . ');' . PHP_EOL
                                     . '    background-repeat: no-repeat;' . PHP_EOL
                                     . '    height: ' . $imageinfo[1] . 'px;' . PHP_EOL
                                     . '    width: ' . $imageinfo[0] . 'px;' . PHP_EOL
                                     . '    margin-bottom: 5px;' . PHP_EOL
                                     . '    padding-bottom: 0.5em;' . PHP_EOL
                                     . '    clear: both;' . PHP_EOL
                                     . '}' . PHP_EOL;
                
                $headers = array();
                foreach (array('h1', 'h2', 'h3', 'h4') as $header) {
                    $headers[] = '.' . $section . ' ' . $header;
                }
                $stylesheetContents .= implode(', ', $headers) . PHP_EOL . '{' . PHP_EOL
                                     . '    display: none' . PHP_EOL
                                     . '}' . PHP_EOL;
                
            }
        }
        
        file_put_contents($this->getDirectory() . 'styles.css', $stylesheetContents);
    }
}
