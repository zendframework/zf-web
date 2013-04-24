<?php

// This form won't work ...
$form = new Zend_Form(array(
    'method'    => 'post',
    'enctype'   => Zend_Form::ENCTYPE_MULTIPART,

    'elements' => array(
        'picture' => array('file', array(
            'label'         => 'Upload an image:',
            'destination'   => '../media/tmp/',
            'required'      => true,
            'validators'    => array(
                array('File_Extension', true, array('jpg,jpeg', 'messages' => array(with messages...))),
                array('File_Size', true, array('1MB', 'messages' => array(with messages...))),
                array('File_ImageSize', true, array(50, 50, 3500, 3500, 'messages' => array(with messages...))),
            ),
        )),
        'submit' => array('submit'),
    )
));

// This this will work but:
// - Syntax is different that the one used by all the others form validators
// - Can't use the breakChainOnFailure
// - Can't specify custom messages from within the array passed to Zend_Form
$form = new Zend_Form(array(
    'method'    => 'post',
    'enctype'   => Zend_Form::ENCTYPE_MULTIPART,

    'elements' => array(
        'picture' => array('file', array(
            'label'         => 'Upload an image:',
            'destination'   => '../media/tmp/',
            'required'      => true,
            'validators'    => array(
                array('validator' => 'Extension', 'jpg,jpeg'),
                array('validator' => 'Size', '1MB'),
                array('validator' => 'ImageSize', 50, 50, 3500, 3500),
            ),
        )),
        'submit' => array('submit'),
    )
));
