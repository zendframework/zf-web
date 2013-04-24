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
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Zend_Form Interface
 * 
 * @category   Zend
 * @package    Zend_Form
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: $
 */
interface Zend_Form_Interface
{
    /**
     * Allow configuration via array options
     * 
     * @param  array $options 
     * @return Zend_Form_Interface
     */
    public function setOptions(array $options);

    /**
     * Allow configuration via Zend_Config objects
     * 
     * @param  Zend_Config $config 
     * @return Zend_Form_Interface
     */
    public function setConfig(Zend_Config $config);

    /**
     * Retrieve metadata attributes
     * 
     * @return array
     */
    public function getAttribs();

    /**
     * Retrieve form action
     * 
     * @return string
     */
    public function getAction();

    /**
     * Retrieve form method
     * 
     * @return string
     */
    public function getMethod();

    /**
     * Retrieve form name
     * 
     * @return string
     */
    public function getName();

    /**
     * Retrieve form legend
     * 
     * @return string
     */
    public function getLegend();

    /**
     * Retrieve form description
     * 
     * @return string
     */
    public function getDescription();

    /**
     * Retrieve form order
     * 
     * @return int
     */
    public function getOrder();

    /**
     * Add element to form
     * 
     * @param  string|Zend_Form_Element_Interface $element 
     * @param  string $name 
     * @param  array|Zend_Config|null $options 
     * @return Zend_Form_Interface
     */
    public function addElement($element, $name = null, $options = null);

    /**
     * Create an element
     * 
     * @param  string $type 
     * @param  string $name 
     * @param  array|Zend_Config|null $options 
     * @return Zend_Form_Element_Interface
     */
    public function createElement($type, $name, $options = null);

    /**
     * Add multiple elements at once
     * 
     * @param  array $elements 
     * @return Zend_Form_Interface
     */
    public function addElements(array $elements);

    /**
     * Add multiple elements at once (overwrite)
     * 
     * @param  array $elements 
     * @return Zend_Form_Interface
     */
    public function setElements(array $elements);

    /**
     * Retrieve a single element
     * 
     * @param  string $name 
     * @return Zend_Form_Element_Interface
     */
    public function getElement($name);

    /**
     * Retrieve all elements
     * 
     * @return array
     */
    public function getElements();

    /**
     * Remove a single element
     * 
     * @param  string $name 
     * @return bool
     */
    public function removeElement($name);

    /**
     * Clear all elements
     * 
     * @return Zend_Form_Interface
     */
    public function clearElements();

    /**
     * Set element default values 
     * 
     * @param  array $defaults 
     * @return Zend_Form_Interface
     */
    public function setDefaults(array $defaults);

    /**
     * Get filtered element values 
     * 
     * @param  bool $suppressArrayNotation 
     * @return array
     */
    public function getValues($suppressArrayNotation = false);

    /**
     * Get unfiltered element values
     * 
     * @return array
     */
    public function getUnfilteredValues();

    /**
     * Add sub form
     * 
     * @param  Zend_Form_SubForm_Interface $form 
     * @param  string $name 
     * @param  int|null $order 
     * @return Zend_Form_Interface
     */
    public function addSubForm(Zend_Form_SubForm_Interface $form, $name, $order = null);

    /**
     * Add many sub forms
     * 
     * @param  array $subForms 
     * @return Zend_Form_Interface
     */
    public function addSubForms(array $subForms);

    /**
     * Add many sub forms (overwrite)
     * 
     * @param  array $subForms 
     * @return Zend_Form_Interface
     */
    public function setSubForms(array $subForms);

    /**
     * Retrieve a sub form
     * 
     * @param  string $name 
     * @return Zend_Form_SubForm_Interface
     */
    public function getSubForm($name);

    /**
     * Get all sub forms
     * 
     * @return array
     */
    public function getSubForms();

    /**
     * Remove a single sub form
     * 
     * @param  string $name 
     * @return bool
     */
    public function removeSubForm($name);

    /**
     * Remove all sub forms
     * 
     * @return Zend_Form_Interface
     */
    public function clearSubForms();

    /**
     * Add a display group
     * 
     * @param  array $elements 
     * @param  string $name 
     * @param  array|Zend_Config|null $options 
     * @return Zend_Form_Interface
     */
    public function addDisplayGroup(array $elements, $name, $options = null);

    /**
     * Add many display groups at once
     * 
     * @param  array $groups 
     * @return Zend_Form_Interface
     */
    public function addDisplayGroups(array $groups);

    /**
     * Add many display groups at once (overwrite)
     * 
     * @param  array $groups 
     * @return Zend_Form_Interface
     */
    public function setDisplayGroups(array $groups);

    /**
     * Retrieve a single display group
     * 
     * @param  string $name 
     * @return Zend_Form_DisplayGroup_Interface
     */
    public function getDisplayGroup($name);

    /**
     * Retrieve all display groups
     * 
     * @return array
     */
    public function getDisplayGroups();

    /**
     * Remove a single display group
     * 
     * @param  string $name 
     * @return bool
     */
    public function removeDisplayGroup($name);

    /**
     * Clear all display groups 
     * 
     * @return Zend_Form_Interface
     */
    public function clearDisplayGroups();

    /**
     * Is the form valid?
     * 
     * @param  array $data 
     * @return bool
     */
    public function isValid(array $data);

    /**
     * Are the items submitted valid?
     * 
     * @param  array $data 
     * @return bool
     */
    public function isValidPartial(array $data);

    /**
     * Get all validation error codes, or error codes by individual elements or 
     * sub forms
     * 
     * @param  string|null $name 
     * @return array
     */
    public function getErrors($name = null);

    /**
     * Get all validation error messages, or by individual elements or sub 
     * forms 
     * 
     * @param  string $name 
     * @return array
     */
    public function getMessages($name = null);

    /**
     * Set view object
     * 
     * @param  Zend_View_Interface $view 
     * @return Zend_Form_Interface
     */
    public function setView(Zend_View_Interface $view = null);

    /**
     * Retrieve view instance
     * 
     * @return Zend_View_Interface
     */
    public function getView();

    /**
     * Set decorators
     * 
     * @param  array $decorators 
     * @return Zend_Form_Interface
     */
    public function setDecorators(array $decorators);

    /**
     * Get decorators
     * 
     * @return array
     */
    public function getDecorators();

    /**
     * Render form
     * 
     * @param  Zend_View_Interface|null $view 
     * @return string
     */
    public function render(Zend_View_Interface $view = null);

    /**
     * Set translation object
     * 
     * @param  Zend_Translate|Zend_Translate_Adapter|null $translator 
     * @return Zend_Form_Interface
     */
    public function setTranslator($translator = null);

    /**
     * Get translation object
     * 
     * @return null|Zend_Translate_Adapter
     */
    public function getTranslator();

    /**
     * Iteration: current item
     * 
     * @return mixed
     */
    public function current();

    /**
     * Iteration: current key
     * 
     * @return string|int
     */
    public function key();

    /**
     * Iteration: move pointer to next item
     * 
     * @return void
     */
    public function next();

    /**
     * Iteration: move pointer to first item
     * 
     * @return void
     */
    public function rewind();

    /**
     * Iteration: item is valid
     * 
     * @return bool
     */
    public function valid();
}
