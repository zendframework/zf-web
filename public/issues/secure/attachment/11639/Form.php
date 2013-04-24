<?php
/**
 * GiRaFfe
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @version $Id: Form.php 464 2008-11-12 11:12:33Z DASPRiD $
 */

/**
 * App_Form
 *
 * Set basic form options
 */
class App_Form extends Zend_Form
{
    public function __construct($options)
    {
        $this->addPrefixPath('App_Form', 'App/Form');
        $this->addElementPrefixPath('App_Validate', 'App/Validate', 'validate');
        $this->addElementPrefixPath('App_Filter', 'App/Filter', 'filter');
        $this->setAttrib('accept-charset', 'utf-8');

        parent::__construct($options);
    }

    public function render(Zend_View_Interface $view = null)
    {
        // Set rendering for the form itself
        $this->setDisplayGroupDecorators(array('FormElements', 'Fieldset'));
        $this->addDecorator('HtmlTag', array('tag' => 'ul', 'class' => 'zend_form'));
               
        // Check all elements
        foreach ($this->_elements as $element) {           
            // Set decorators for all elements
            if (($element instanceof Zend_Form_Element_Submit) === false) {
                $outerClasses = array();
                
                $label = $element->getLabel();
                if (empty($label) === true and
                    ($element instanceof App_Form_Element_Headline) === false and
                    ($element instanceof App_Form_Element_Info) === false) {
                    $outerClasses[] = 'indent';
                }

                if (($element instanceof App_Form_Element_Checkbox) === true or
                    ($element instanceof Zend_Form_Element_MultiCheckbox) === true or
                    ($element instanceof Zend_Form_Element_Radio) === true) {
                    $outerClasses[] = 'checkbox-radio';   
                } else {
                    $labelOptions = array();
                }
                
                if ($element instanceof Zend_Form_Element_MultiCheckbox and
                    $element->getName() === 'tags') {
                    $outerClasses[] = 'tags';        
                }
                
                if (count($outerClasses) > 0) {
                    $outerOptions = array('tag' => 'li', 'class' => implode(' ', $outerClasses));   
                } else {
                    $outerOptions = array('tag' => 'li');
                }
                
                $decorators = array(
                    'ViewHelper',
                    array('decorator' => array('Floater'   => 'HtmlTag'),
                          'options'   => array('tag'       => 'div',
                                               'class'     => 'input-container')),
                    array('Label', array('requiredSuffix' => ':',
                                         'optionalSuffix' => ':')),
                    array('decorator' => array('FloatClear' => 'HtmlTag'),
                          'options'   => array('tag'       => 'div',
                                               'class'     => 'clear',
                                               'placement' => Zend_Form_Decorator_HtmlTag::APPEND)),
                    'Errors',
                    array('HtmlTag', $outerOptions),
                );
            } else {
                $decorators = array(
                    'ViewHelper',
                    array('HtmlTag', array('tag' => 'li', 'class' => 'submit'))
                );                
            }
            
            $element->setDecorators($decorators);

            // Set seperator for multi elements
            if (($element instanceof Zend_Form_Element_Multi) === true) {
                $element->setSeparator(' ');
            }
        }

        return parent::render($view);
    }
}
