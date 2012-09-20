<?php
return array(
    'security' => array(
        'page_size' => 15,
        'advisories' => array(
            'ZF2012-03' => array(
                'title' => 'ZF2012-03: Potential XSS Vectors in Multiple Zend Framework 2 Components',
                'date'  => 'Thu, 20 September 2012 16:00:00 -0500',
            ),
            'ZF2012-02' => array(
                'title' => 'ZF2012-02: Denial of Service vector via XEE injection',
                'date'  => 'Mon, 20 August 2012 14:00:00 -0500',
            ),
            'ZF2012-01' => array(
                'title' => 'ZF2012-01: Local file disclosure via XXE injection in Zend_XmlRpc',
                'date'  => 'Fri, 22 June 2012 15:00:00 -0500',
            ),
            'ZF2011-02' => array(
                'title' => 'ZF2011-02: Potential SQL Injection Vector When Using PDO_MySql',
                'date'  => 'Fri, 06 May 2011 18:00:00 -0500',
            ),
            'ZF2011-01' => array(
                'title' => 'ZF2011-01: Potential XSS in Development Environment Error View Script',
                'date'  => 'Thu, 03 Mar 2011 12:00:00 -0500',
            ),
            'ZF2010-07' => array(
                'title' => 'ZF2010-07: Potential Security Issues in Bundled Dojo Library',
                'date'  => 'Thu, 01 Apr 2010 18:00:00 -0500',
            ),
            'ZF2010-06' => array(
                'title' => 'ZF2010-06: Potential XSS or HTML Injection vector in Zend_Json',
                'date'  => 'Mon, 11 Jan 2010 16:00:00 -0500',
            ),
            'ZF2010-05' => array(
                'title' => 'ZF2010-05: Potential XSS vector in Zend_Service_ReCaptcha_MailHide',
                'date'  => 'Mon, 11 Jan 2010 16:00:00 -0500',
            ),
            'ZF2010-04' => array(
                'title' => 'ZF2010-04: Potential MIME-type Injection in Zend_File_Transfer',
                'date'  => 'Mon, 11 Jan 2010 16:00:00 -0500',
            ),
            'ZF2010-03' => array(
                'title' => 'ZF2010-03: Potential XSS vector in Zend_Filter_StripTags when comments allowed',
                'date'  => 'Mon, 11 Jan 2010 16:00:00 -0500',
            ),
            'ZF2010-02' => array(
                'title' => 'ZF2010-02: Potential XSS vector in Zend_Dojo_View_Helper_Editor',
                'date'  => 'Mon, 11 Jan 2010 16:00:00 -0500',
            ),
            'ZF2010-01' => array(
                'title' => 'ZF2010-01: Potential XSS vectors due to inconsistent encodings',
                'date'  => 'Mon, 11 Jan 2010 16:00:00 -0500',
            ),
            'ZF2009-02' => array(
                'title' => 'ZF2009-02: XSS vector in Zend_Filter_StripTags',
                'date'  => 'Mon, 02 Mar 2009 09:00:00 -0500',
            ),
            'ZF2009-01' => array(
                'title' => 'ZF2009-01: LFI vector in Zend_View::setScriptPath() and render()',
                'date'  => 'Tue, 17 Feb 2009 09:00:00 -0500',
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'security' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/security[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Security\Controller',
                        'controller'    => 'Advisory',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => ':action[/]',
                            'constraints' => array(
                                'action' => '(advisories|feed)',
                            ),
                        ),
                    ),
                    'advisory' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => 'advisory/:advisory',
                            'defaults' => array(
                                'action'   => 'advisory',
                            ),
                            'constraints' => array(
                                'advisory' => 'ZF20\d{2}-\d{2}',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'security' => __DIR__ . '/../view',
        ),
    ),
);
