<?php
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '/path/to/zend/library');

require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

$request = new Zend_Controller_Request_Http();
$view = new Zend_View();

/**
 * Example showing usage of array notation using belongsTo
 * for shipping/billing addresses.
 */

$form = new Zend_Form();
$form->setView($view);
$form->addDecorator('Description', array('escape' => false, 'placement' => 'PREPEND'));
$form->setDescription('<h3>Using belongsTo</h3>');

$form->addElement('text', 'recipient', array(
    'label' => 'Ship to',
    'required' => true,
    'belongsTo' => 'shipping',
));

$form->addElement('text', 'address1', array(
    'label' => 'Address 1',
    'required' => true,
    'belongsTo' => 'shipping[addresses]', // Different belongsTo
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
