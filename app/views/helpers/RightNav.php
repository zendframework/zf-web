<?php
/**
 * Placeholder class for right navigation
 * 
 * @uses    Zend _View_Helper_Placeholder_Container_Standalone
 * @version $Id: $
 */
class Zend_View_Helper_RightNav extends Zend_View_Helper_Placeholder_Container_Standalone
{
    /**
     * Placeholder registry key
     * @var string
     */
    protected $_regKey = 'right-nav';

    /**#@+
     * Capture type and/or attributes (used for hinting during capture)
     * @var string
     */
    protected $_captureLock;
    protected $_captureTitle = '';
    protected $_captureClass = 'block-in';
    protected $_captureType;
    /**#@-*/

    /**
     * Constructor: setup prefix and postfix
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setPrefix('<div class="right-nav">')
             ->setPostfix('</div>');
    }

    /**
     * rightNav() helper
     * 
     * @return Zend_View_Helper_RightNav
     */
    public function rightNav()
    {
        return $this;
    }

    /**
     * Append a block to the navigation
     * 
     * @param  string $title 
     * @param  string $content 
     * @param  string $contentClass 
     * @return Zend_View_Helper_RightNav
     */
    public function appendBlock($title, $content, $contentClass = 'block-in')
    {
        return $this->append($this->_buildBlock($title, $content, $contentClass));
    }

    /**
     * Prepend a block to the right nav
     * 
     * @param  string $title 
     * @param  string $content 
     * @param  string $contentClass 
     * @return Zend_View_Helper_RightNav
     */
    public function prependBlock($title, $content, $contentClass = 'block-in')
    {
        return $this->prepend($this->_buildBlock($title, $content, $contentClass));
    }

    /**
     * Set right nav to a single block
     * 
     * @param  string $title 
     * @param  string $content 
     * @param  string $contentClass 
     * @return Zend_View_Helper_RightNav
     */
    public function setBlock($title, $content, $contentClass = 'block-in')
    {
        return $this->set($this->_buildBlock($title, $content, $contentClass));
    }

    /**
     * Begin capturing content for right nav block
     *
     * @param  string $captureType Type of capture -- append, prepend, set
     * @param  string $title Title of block captured
     * @return void
     */
    public function captureStart(
        $captureType = Zend_View_Helper_Placeholder_Container_Abstract::APPEND,
        $title = '',
        $contentClass = 'block-in'
    ) {
        if ($this->_captureLock) {
            require_once 'Zend/View/Helper/Placeholder/Container/Exception.php';
            throw new Zend_View_Helper_Placeholder_Container_Exception('Cannot nest rightNav captures');
        }

        $this->_captureLock = true;
        $this->_captureType  = $captureType;
        $this->_captureTitle = $title;
        $this->_captureClass = $contentClass;
        ob_start();
    }

    /**
     * Finish capturing content for a right-nav block
     * 
     * @return void
     */
    public function captureEnd()
    {
        $content             = ob_get_clean();
        $title               = $this->_captureTitle;
        $contentClass        = $this->_captureClass;
        $this->_captureTitle = null;
        $this->_captureClass = null;
        $this->_captureLock  = false;

        switch ($this->_captureType) {
            case Zend_View_Helper_Placeholder_Container_Abstract::SET:
            case Zend_View_Helper_Placeholder_Container_Abstract::PREPEND:
            case Zend_View_Helper_Placeholder_Container_Abstract::APPEND:
                $action = strtolower($this->_captureType) . 'Block';
                break;
            default:
                $action = 'appendBlock';
                break;
        }
        $this->$action($title, $content, $contentClass);
    }

    /**
     * Build a block for the right nav
     * 
     * @param  string $title 
     * @param  string $content 
     * @param  string $contentClass 
     * @return string
     */
    protected function _buildBlock($title, $content, $contentClass)
    {
        if (!empty($title)) {
            $title = '    <h2>' . $this->view->escape($title) . "</h2>\n";
        }
        return '<div class="block">' . "\n"
               . $title
               . '    <div class="' . $this->view->escape($contentClass) . '">' . "\n"
               . $content
               . "    </div>\n"
               . "</div>\n";
    }
}
