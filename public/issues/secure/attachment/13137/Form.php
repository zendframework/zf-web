<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PC_Form
 *
 * @author Scott Morken <scott.morken@pcmail.maricopa.edu>
 */
class PC_Form extends Zend_Form
{
     /**
     * array of parent forms/subforms
     * @var array
     */
    protected $_parent = array();

    /**
     * Set parent
     *
     * @param  parent
     * @return Zend_Form_SubForm
     */
    public function setParent($parent, array $parentParent = array())
    {
       $this->_parent = $parentParent;
       array_push($this->_parent, $parent);
        return $this;
    }

    /**
     * Return array of parents
     *
     * @return string
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Return parent string
     *
     * @return string
     */
    public function getParentString()
    {
        if (!empty($this->_parent)) {
            $parents = $this->_parent;
            $string = array_shift($parents);
            if (count($parents) > 0) {
                $string .= '[' . join('][', $parents) . ']';
            }
            return $string;
        }
        return false;
    }
    
    /**
     * Add a form group/subform
     *
     * @param  Zend_Form $form
     * @param  string $name
     * @param  int $order
     * @return Zend_Form
     */
    public function addSubForm(Zend_Form $form, $name, $order = null)
    {
        $name = (string) $name;
        foreach ($this->_loaders as $type => $loader) {
            $loaderPaths = $loader->getPaths();
            foreach ($loaderPaths as $prefix => $paths) {
                foreach ($paths as $path) {
                    $form->addPrefixPath($prefix, $path, $type);
                }
            }
        }

        if (!empty($this->_elementPrefixPaths)) {
            foreach ($this->_elementPrefixPaths as $spec) {
                list($prefix, $path, $type) = array_values($spec);
                $form->addElementPrefixPath($prefix, $path, $type);
            }
        }

        if (!empty($this->_displayGroupPrefixPaths)) {
            foreach ($this->_displayGroupPrefixPaths as $spec) {
                list($prefix, $path) = array_values($spec);
                $form->addDisplayGroupPrefixPath($prefix, $path);
            }
        }

        if (null !== $order) {
            $form->setOrder($order);
        }

        if (($oldName = $form->getName()) &&
            $oldName !== $name &&
            $oldName === $form->getElementsBelongTo()) {
            $form->setElementsBelongTo($name);
        }

        $form->setName($name);
        $form->setParent($name, $this->getParent());
        $this->_subForms[$name] = $form;
        $this->_order[$name]    = $order;
        $this->_orderUpdated    = true;
        return $this;
    }

     /**
     * Set array to which elements belong
     *
     * @param  string $name Element name
     * @return void
     */
    protected function _setElementsBelongTo($name = null)
    {
        $parents = $this->getParentString();
        $array = $this->getElementsBelongTo();

        if (null === $array && false === $parents) {
            return;
        }

        if (null === $name && false === $parents) {
            foreach ($this->getElements() as $element) {
                $element->setBelongsTo($array);
            }
        } else {
            if (null !== ($element = $this->getElement($name))) {
                $element->setBelongsTo($parents !== false ? $parents : $array);
            }
        }
    }
}
