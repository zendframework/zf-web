<?php
require_once 'Zend/Form.php';

class IssueDetails extends Zend_Form
{
	public function init()
	{
		$this->addElement('text', 'summary', array(
			'label' => 'Summary',
		));
		
		$this->addElement('select', 'reporter', array(
			'label' => 'Reporter',
			'belongsTo' => 'optional',
		));
		
		$this->addElement('select', 'assignee', array(
			'label' => 'Assignee',
			'belongsTo' => 'optional',
		));
		
		$this->addElement('select', 'priority', array(
			'label' => 'Priority',
			'value' => '4',
			'belongsTo' => 'optional',
		));
		
		$this->addElement('textarea', 'description', array(
			'label' => 'Description',
			'rows' => 12,
			'belongsTo' => 'optional',
		));
		
		$this->addElement('submit', 'submit', array(
			'label' => 'Save',
			'ignore' => true,
		));
	}
}
