<?php
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/path/to/zend/library');

require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

$request = new Zend_Controller_Request_Http();
$view = new Zend_View();

/**
 * Example showing usage of array notation using setElementsBelongTo
 * on the form level, for shipping/billing addresses.
 */

$form = new Zend_Form();
$form->setView($view);
$form->addDecorator('Description', array('escape' => false, 'placement' => 'PREPEND'));
$form->setDescription('<h3>Using setElementsBelongTo</h3>');

$form->setElementsBelongTo('shipping');

$form->addElement('text', 'recipient', array(
    'label' => 'Ship to',
    'required' => true,
));

$form->addElement('text', 'address', array(
    'label' => 'Address',
    'required' => true,
));

$form->addElement('submit', 'submit', array(
    'label' => 'Submit',
));

if ($request->isPost()) {
    if ($form->isValid($request->getPost())) {
        echo 'Order placed, Thank you!';
    }
    else {
        echo 'You have errors in your form, please check';
    }
}

echo $form->render();
