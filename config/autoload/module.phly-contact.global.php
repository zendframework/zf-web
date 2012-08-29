<?php
return array(
    'phly_contact' => array(
        'captcha' => array(
            'class'   => 'recaptcha',
            'options' => array(
                'pubkey'  => 'RECAPTCHA_PUBKEY_HERE',
                'privkey' => 'RECAPTCHA_PRIVKEY_HERE',
                'theme'   => 'clean',
            ),
        ),

        'message' => array(
            // These can be either a string, or an array of email => name pairs
            /*
            'to'     => 'contact@your.tld',
            'sender' => 'contact@your.tld',
             */
        ),

        'mail_transport' => array(
            'class'   => 'Zend\Mail\Transport\Sendmail',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'phly-contact/contact/index'     => __DIR__ . '/../../module/Application/view/contact/index.phtml',
            'phly-contact/contact/thank-you' => __DIR__ . '/../../module/Application/view/contact/thank-you.phtml',
        ),
    ),
);
