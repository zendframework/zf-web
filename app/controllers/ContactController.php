<?php
class ContactController extends Zend_Controller_Action
{
    protected $_form;

    /*
    *  Partner Inquiries => ZFMarketing@zend.com
    * Marketing Questions / Feedback => ZFMarketing@zend.com
    * Access to ZF Issue Tracker => cla@zend.com
    * ZF Contributor Questions => cla@zend.com
    * ZF Technical Questions / Feedback to Team => fw-devteam@zend.com
    * Webmaster / Site Problems => fw-devteam@zend.com, ZFMarketing@zend.com
     */

    public $recipientSelectOptions = array(
        'partner'     => 'Partner Inquiries',
        'marketing'   => 'Marketing Questions and Feedback',
        'issues'      => 'Access to the ZF Tools, including the Issue Tracker and Wiki',
        'contributor' => 'ZF Contributor Questions',
        'technical'   => 'Feedback for the ZF Team',
        'webmaster'   => 'Site Issues',
    );

    public $recipientMap = array(
        'partner'     => array('ZFMarketing@zend.com'),
        'marketing'   => array('ZFMarketing@zend.com'),
        'issues'      => array('cla@zend.com'),
        'contributor' => array('cla@zend.com'),
        'technical'   => array('fw-devteam@zend.com'),
        'webmaster'   => array('fw-devteam@zend.com', 'ZFMarketing@zend.com'),
    );

    public function preDispatch()
    {
        // Determine which mail transport to use
        $config = Zend_Registry::get('config');
        $transportClass = $config->contact->mail->transport;
        Zend_Mail::setDefaultTransport(new $transportClass); 
    }

    public function indexAction()
    {
        $this->view->form = $this->getForm();
    }


    public function processAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()
            || (!$data = $request->getPost('contact', false))
            || !is_array($data)
        ) {
            return $this->_helper->redirector('index');
        }
        
        $form = $this->getForm();
        if (!$form->isValid($data)) {
            $this->view->form = $form;
            return $this->render('index');
        }

        $data = $form->getValues();
        
        $this->send($data['contact']);
        $this->_helper->redirector('thank-you');
    }

    public function thankYouAction()
    {
    }

    /**
     * Create contact form
     * 
     * @return Zend_Form
     */
    public function getForm()
    {
        $config = Zend_Registry::get('config');
        if (null === $this->_form) {
            $form = new Zend_Form(array(
                'name'    => 'contact',
                'isArray' => true,
                'method'  => 'post',
                'action'  => '/contact/process',
            ));

            $email = $form->createElement('text', 'email');
            $email->setRequired(true)
                  ->addValidator('EmailAddress')
                  ->setLabel('Your email address:');

            $recipientOptions = array_merge(
                array('none' => 'Please select...'),
                $this->recipientSelectOptions
            );

            $recipient = $form->createElement('select', 'recipient');
            $recipient->setMultiOptions($recipientOptions)
                      ->setRequired(true)
                      ->addValidator(
                          'InArray', 
                          false, 
                          array(
                              array_keys($this->recipientSelectOptions),
                              'messages' => 'Please select a value',
                          ))
                      ->setLabel('Type of Contact:')
                      ->setValue('none');

            $content = $form->createElement('textarea', 'content');
            $content->setAttrib('rows', 10)
                    ->setAttrib('cols', 35)
                    ->setRequired(true)
                    ->setLabel('Your Message:');

            if (isset($config->recaptcha)) {
                $captchaOptions = $config->recaptcha->toArray();
                $captchaOptions['captcha'] = 'ReCaptcha';
            } else {
                $captchaOptions = array('captcha' => 'Figlet', 'wordLen' => 5, 'timeout' => 300);
            }
                    
            $captcha = $form->createElement('captcha', 'captcha', array(
                'label'   => 'Please fill in the CAPTCHA below.',
                'captcha' => $captchaOptions
                ));
                    
            $send = $form->createElement('submit', 'send');
            $send->setRequired(false)
                 ->setIgnore(true)
                 ->setLabel('Send!')
                 ->setAttrib('class', 'btn_submit');

            $form->addElements(array(
                $recipient,
                $email,
                $content,
                $captcha,
                $send
            ));

            $this->_form = $form;
        }
        return $this->_form;
    }

    /**
     * Send email
     * 
     * @param  array $data 
     * @return void
     */
    public function send(array $data)
    {
        $mail = new Zend_Mail('utf-8');

        $mail->setFrom($data['email'], $data['email'])
             ->setBodyText($data['content'])
             ->setSubject($this->recipientSelectOptions[$data['recipient']]);

        $recipients = $this->recipientMap[$data['recipient']];
        foreach ($recipients as $recipient) {
            $mail->addTo($recipient);
        }

        $mail->send();
    }
}
