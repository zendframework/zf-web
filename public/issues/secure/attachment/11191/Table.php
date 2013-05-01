<?php

require_once 'Zend/Form/Decorator/Abstract.php';

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
		$output = '';
		foreach ($form->getElements() as $e)
		{
			$output .= $e->render();
		}
		# TODO: use elements order for determining element placement instead of $this->getPlacement()? (which method is more appropriate?)
		# should we append or prepend regular (ie. not sub-forms) form elements to the table?
		switch ($this->getPlacement())
		{
			case self::PREPEND:
				return $output . $this->_buildTable($form);
			case self::APPEND:
				default:
				return $this->_buildTable($form) . $output;
		}
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
					if (isset($this->_tableHeaders[$eName]))
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
				$elements[] = implode('<br />', array_merge($row[$eName]->getMessages(), array($row[$eName]->render())));
			}
			$tableRows[] = '<tr><td>' . implode('</td><td>', $elements) . '</td></tr>' . "\n";
		}
		return '<table><tr><th>' . implode('</th><th>', $this->_tableHeaders) . '</th></tr>' . "\n" . implode('', $tableRows) . '</table>';
	}
}
