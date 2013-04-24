<?php

/**
 * Draws the form as a table, using sub-forms for each table row.
 * The header row will be generated from the elements of the first sub-form, and any other sub-forms must match the elements in this sub-form.
 *
 * Options:
 * - doNotSetDecorators : if set to true, this decorator will not set decorators for sub-form elements
 *     the default action for this decorator is to set ViewHelper as the only decorator for elements in order to generate proper table markup
 */
class Zend_Form_Decorator_Table extends Zend_Form_Decorator_Abstract
{
	protected $_tableHeadersBuilt = false;
	protected $_tableHeaders = array();
	protected $_tableCell = array();
	protected $_rowIndex = 0;
	public function render($content)
	{
		$form = $this->getElement();
		if (!$form instanceof Zend_Form)
		{
			throw new Exception('Element passed to ' . __METHOD__ . ' is not an instance of Zend_Form');
		}
		if ($form instanceof Zend_Form_SubForm)
		{
			throw new Exception(sprintf('Element passed to ' . __METHOD__ . ' is an instance of Zend_Form_SubForm - with this decorator (%s) you may only render regular complete forms, not sub-forms by themselves.', __CLASS__));
		}
		
		//Render fields before subform.
		$output = '';
		$subform = $form->getSubForms();
		if(empty($subform)){
			throw new Exception('No subform contained to render by Zend_Form_Decorator_Table');
		}
		$subform = current($subform);
		$subformOrder = $subform->getOrder();
		$formElements = $form->getElements();
		foreach ($formElements as $e)
		{
			/* @var $e Zend_Form_Element */
			if($e->getOrder() < $subformOrder){
				$output .= $e->render();
			}		
		}		
		
		//Sort in at correct position.
		$output .= $this->_buildTable($form);		
		
		//Render other fields after subform.
		foreach ($formElements as $e)
		{
			/* @var $e Zend_Form_Element */
			if($e->getOrder() > $subformOrder){
				$output .= $e->render();
			}		
		}
		
		return $output;
	}
	protected function _buildTable (Zend_Form $form)
	{
		# generate table header
		if ($this->_tableHeadersBuilt !== true)
		{
			if ($form instanceof Zend_Form_SubForm)
			{
				foreach ($form->getElements() as $eName => $e)
				{
					if (isset($this->_tableHeaders[$e->getName()]))
					{
						throw new Exception(sprintf('Invalid sub-form (table row configuration) "%s" for table layout - table header already set for this column "%s"', $form->getName(), $eName));
					}
					$this->_tableHeaders[$eName] = $e->getLabel();
				}
				foreach ($form->getSubForms() as $formName => $subForm)
				{
					$this->_buildTable($subForm);
				}
				return false;
			}
			# get headers from first sub-form
			$subform = $form->getSubForms();
			$subform = current($subform);
			$this->_buildTable($subform);
			$this->_tableHeadersBuilt = true;
		}
		if ($form instanceof Zend_Form_SubForm)
		{
			foreach ($form->getElements() as $eName => $e)
			{
				if ($this->getOption('doNotSetDecorators') !== true)
				{
					$e->setDecorators(array(
							'ViewHelper'
					));
				}
				if (isset($this->_tableCell[$eName]))
				{
					throw new Exception(sprintf('Invalid sub-form (table row configuration) "%s" for table layout - table cell "%s" already set for row %d', $form->getName(), $eName, $this->_rowIndex));
				}
				$this->_tableCell[$eName] = $e;
			}

			foreach ($form->getSubForms() as $formName => $subForm)
			{
				$this->_buildTable($subForm);
			}
			return;
		}
		# $form is not instance of Zend_Form_SubForm
		$result = array();
		foreach ($form->getSubForms() as $formName => $subForm)
		{
			$this->_tableCell = array();
			$this->_rowIndex++;
			$this->_buildTable($subForm);
			if (empty($this->_tableCell))
			{
				# no data generated from current subform
				continue;
			}
			$result[$this->_rowIndex] = $this->_tableCell; 
		}
		
		if (empty($result))
		{
			return '';
		}
		$tableRows = array();
		foreach ($result as $rowIndex => $row)
		{
			$elements = array();
			foreach ($this->_tableHeaders as $eName => $label)
			{
				if (!isset($row[$eName]))
				{
					throw new Exception(sprintf('Invalid form "%s" for table layout - missing table cell "%s" for row %d', $form->getName(), $eName, $rowIndex));
				}
				# display messages in the same table cell as the input field
				$row[$eName]->addDecorators(array('Errors'));
				$elements[] = $row[$eName]->render();
			}
			$tableRows[] = '<tr><td>' . implode('</td><td>', $elements) . '</td></tr>' . "\n";
		}
		return '<table>' .$this->_getTableHeadersString(). "\n" . implode('', $tableRows) . '</table>';
	}
	
	private function _getTableHeadersString(){
		$result = '<tr>';
		$th = $this->_tableHeaders;
		if(!empty($th)){
			foreach($th as $name => $currentTh){
				$result .= '<th class="'.$name.'">';				
				$result .= $currentTh;
				$result .= '</th>';
			}
		}
		$result .= '</tr>';
		
		return $result;
	}
}