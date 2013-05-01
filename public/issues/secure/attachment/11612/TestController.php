<?php

/**
 * TestController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class TestController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// Create form
		$form = new Zend_Form();
		$form->setEnctype('multipart/form-data');

		// File element...
		$fileEle = new Zend_Form_Element_File("projectImage", array(
			'label' => 'Image File:'
		));
		$fileEle->setMultiFile(3);
		
		// Add elements + submit button
		$form->addElements(array(
			$fileEle,
			new Zend_Form_Element_Submit('projectSubmitImageUpload', array(
				'label' => 'Upload Image'
			))
		));
		
		// Show the form
		echo $form;
		
		// Check if we have a form post, and if it's valid
		if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
			// you'll never get here!
		}
	}
}
?>