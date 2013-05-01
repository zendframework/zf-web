<?php

class Form_Default extends Zend_Form {
	
	private $_defaultFieldsetname = 'default';
	private $_fieldset = array();
	

	public function addFieldset ($element, $fieldsetName = null) {
		$this->addElement($element);
		if ($fieldsetName === null)
			$this->_fieldset[$this->_defaultFieldsetname][] = $element;
		else
			$this->_fieldset[$fieldsetName][] = $element;
	}

	public function renderFieldsets () {
		foreach ($this->_fieldset as $key => $elements) {
			$elementsToFieldset = array();
			
			foreach ($elements as $element) {
				$elementsToFieldset[] = $element->getName();
			}
			
			$options = array();
			if ($key !== $this->_defaultFieldsetname)
				$options['legend'] = $key;
			
			$this->addDisplayGroup($elementsToFieldset, $key, $options);
		}
		$this->setDisplayGroupDecorators(array('FormElements', 'Fieldset'));
	}
}

class Logbook_Form_Entry extends Form_Default {

	public function setForm (...) {

		....
		
		$lenDateFrom = new Form_Element_Text('lenDateFrom');
		$lenDateFrom->setValue($dateFrom);
		$lenDateFrom->setRequired();
		$this->addFieldset($lenDateFrom, 'mainData');
		
		....

		$this->renderFieldsets();
	}
}