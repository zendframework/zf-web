<?php

// Controller
class IndexController extends Zend_Controller_Action
{ 
    public function indexAction()
    {
        $form = new Zend_Form(array(
             'method'    => 'post',
             'enctype'   => Zend_Form::ENCTYPE_MULTIPART,

             'elements' => array(
                 'picture' => array('file', array(
                     'destination'   => '../media/tmp/',
                 )),
                 'submit' => array('submit'),
             )
         ));

         if ($this->getRequest()->isPost()) {
             $form->isValid($_POST);
         }
     
         $this->view->form = $form;        
    }
}

// In the View script :
// print $this->form;

// Output after validation:
// <input type="file" name="picture" id="picture" value="../media/tmp/" />
