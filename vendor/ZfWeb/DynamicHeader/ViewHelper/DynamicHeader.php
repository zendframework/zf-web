<?php

class ZfWeb_DynamicHeader_ViewHelper_DynamicHeader
{
    /**
     * @var ZfWeb_DynamicHeader
     */
    protected static $_mvcManager;

    /**
     * @var Zend_View
     */
    public $view;

    public static function setMvcManager(ZfWeb_DynamicHeader $mvcManager)
    {
        self::$_mvcManager = $mvcManager;
    }
    
    public function dynamicHeader($group, $text, $headerTag = 'h2')
    {
        if (self::$_mvcManager == null) {
            throw new Exception('You must first start the ZfWeb_DynamicHeader component from your bootstrap file.');
        }
        
        $name = self::$_mvcManager->normalizeDynamicHeader($group, $text);
                
        $output = '<div class="' . $name . '"><' . $headerTag . '>' . $text . '</' . $headerTag . '></div>';
        
        return $output;
    }

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }
}
