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
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Form_Element_Xhtml */
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * Textarea form element
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Textarea.php 8064 2008-02-16 10:58:39Z thomas $
 */
class Form_Element_File extends Zend_Form_Element_Xhtml
{
    /**
     * Use formFile view helper by default
     * @var string
     */
    public $helper = 'formFile';
    
    /**
     * Destination for the file, if destination is an existing folder the uploaded file
     * will be moved there, otherwise $_destination represents the full path for the
     * new file
     * @var string
     */
    protected $_destination;

    /**
     * Validate upload
     * 
     * @param  string $value 
     * @param  mixed $context 
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if (isset($_FILES[$this->getName()])) {
            $value = $_FILES[$this->getName()]['tmp_name'];
            $context = $_FILES[$this->getName()];
            $context['destination'] = $this->_destination;
        }
     
        $isValid = parent::isValid($value, $context);
     
        // If it's valid, we move the file to its final destination
        if ($isValid && isset($this->_destination)) {
            $destination = is_dir($this->_destination) ? $this->_destination . '/' . $context['name'] : $this->_destination;
            move_uploaded_file($value, $destination);
        }
        return $isValid;
    }

    /**
     * Get the upload destination
     * 
     * @return string
     */
    public function getDestination()
    {
        return $this->_destination;
    }

    /**
     * Set the upload destination
     * 
     * @param  string $path 
     * @return Zend_Form_Element_File
     */
    public function setDestination($destination)
    {
        $this->_destination = $destination;
        return $this;
    }
}
