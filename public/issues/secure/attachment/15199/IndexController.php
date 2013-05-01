<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Form\Element;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Checkbox;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Form\Factory;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function formAction()
    {
		// Build a name element.
		$name = new Element('name');
		$name->setLabel('Your name');
		$name->setAttributes(array(
		    'type'  => 'text'
		));
		// Build a submit button element.
		$send = new Element('send');
		$send->setLabel('Send');
		$send->setAttributes(array(
		    'type'  => 'submit',
		    'value' => 'Send'
		));
		// Build a checkbox element.
		$check = new Checkbox('check');
		$check->setLabel('Checkbox example');
		// Build a multi-checkbox element.
		$multicheck = new MultiCheckbox('multicheck');
		$multicheck->setLabel('Multi checkbox example');
		$multicheck->setOptions(array(
			'value_options' => array(
				'One'	=>	'one',
				'Two'   =>  'two',
			),
		));

		// Assemble the form.
		$form = new Form('contact');
		$form->add($name);
		$form->add($check);
		$form->add($multicheck);
		$form->add($send);

		// Get the request if any.
		$request = $this->getRequest();
		$data = $request->getPost();
		$form->setData($data);
		
		// Validate the form
		if ($form->isValid()) {
		    $validatedData = $form->getData();
		    $success  = 'Form submit was successful';
		} else {
		    $success  = 'Form submit failed';
		    $messages = $form->getMessages();
		}

		// Set the method attribute for the form
		$form->setAttribute('method', 'post');

        return new ViewModel(array(
        	'form'		=> $form,
        	'success'	=> $success,
        	'messages'	=> $messages,
        	'data'		=> $data,
        ));
    }
}
