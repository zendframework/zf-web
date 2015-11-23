<?php // @codingStandardsIgnoreFile
return array(
    'security' => array(
        'page_size' => 15,
        'advisories' => array(
            'ZF2015-10' => array(
                'title' => 'ZF2015-10: Potential Information Disclosure in Zend\Crypt\PublicKey\Rsa\PublicKey',
                'date'  => 'Mon, 23 November 2015 14:30:00 -0500',
            ),
            'ZF2015-09' => array(
                'title' => 'ZF2015-09: Potential Information Disclosure and Insufficient Entropy vulnerability in Zend\Captcha\Word',
                'date'  => 'Mon, 23 November 2015 14:30:00 -0500',
            ),
            'ZF2015-08' => array(
                'title' => 'ZF2015-08: Potential SQL injection vector using null byte for PDO (MsSql, SQLite)',
                'date'  => 'Tue, 15 September 2015 14:15:00 -0500',
            ),
            'ZF2015-07' => array(
                'title' => 'ZF2015-07: Filesystem Permissions Issues in Multiple Components',
                'date'  => 'Tue, 15 September 2015 14:15:00 -0500',
            ),
            'ZF2015-06' => array(
                'title' => 'ZF2015-06: XXE/XEE vector when using ZendXml on multibyte payloads',
                'date'  => 'Mon, 03 August 2015 14:15:00 -0500',
            ),
            'AG2015-01' => array(
                'title' => 'AG2015-01: Potential Authenticated User Spoofing in zf-oauth2',
                'date'  => 'Thu, 23 July 2015 12:00:00 -0500',
            ),
            'ZF2015-05' => array(
                'title' => 'ZF2015-05: Potential XSS and Open Redirect vectors in zend-diactoros',
                'date'  => 'Thu, 23 June 2015 10:50:00 -0500',
            ),
            'ZF2015-04' => array(
                'title' => 'ZF2015-04: Potential CRLF injection attacks in mail and HTTP headers',
                'date'  => 'Thu, 07 May 2015 12:13:00 -0500',
            ),
            'ZF2015-03' => array(
                'title' => 'ZF2015-03: Invalid CSRF validation of null or incorrectly formatted token identifiers',
                'date'  => 'Thu, 12 March 2015 10:00:00 -0500',
            ),
            'ZF2015-02' => array(
                'title' => 'ZF2015-02: Potential SQL injection in PostgreSQL Zend\Db adapter',
                'date'  => 'Wed, 18 February 2015 15:00:00 -0500',
            ),
            'ZF2015-01' => array(
                'title' => 'ZF2015-01: Session validation vulnerability',
                'date'  => 'Wed, 14 January 2015 13:00:00 -0500',
            ),
            'ZF2014-06' => array(
                'title' => 'ZF2014-06: SQL injection vector when manually quoting values for sqlsrv extension, using null byte',
                'date'  => 'Thu, 17 September 2014 10:30:00 -0500',
            ),
            'ZF2014-05' => array(
                'title' => 'ZF2014-05: Anonymous authentication in ldap_bind() function of PHP, using null byte',
                'date'  => 'Thu, 17 September 2014 10:30:00 -0500',
            ),
            'ZF2014-04' => array(
                'title' => 'ZF2014-04: Potential SQL injection in the ORDER implementation of Zend_Db_Select',
                'date'  => 'Thu, 12 June 2014 15:00:00 -0500',
            ),
            'AG2014-01' => array(
                'title' => 'AG2014-01: Potential Database Injection Vector in DB-Connected REST Services',
                'date'  => 'Thu, 05 June 2014 11:30:00 -0500',
            ),
            'ZF2014-03' => array(
                'title' => 'ZF2014-03: Potential XSS vector in multiple view helpers',
                'date'  => 'Tue, 15 April 2014 15:05:00 -0500',
            ),
            'ZF2014-02' => array(
                'title' => 'ZF2014-02: Potential security issue in login mechanism of ZendOpenId and Zend_OpenId consumer',
                'date'  => 'Thu, 06 March 2014 17:00:00 -0500',
            ),
            'ZF2014-01' => array(
                'title' => 'ZF2014-01: Potential XXE/XEE attacks using PHP functions: simplexml_load_*, DOMDocument::loadXML, and xml_parse',
                'date'  => 'Thu, 06 March 2014 17:00:00 -0500',
            ),
            'ZF2013-04' => array(
                'title' => 'ZF2013-04: Potential Remote Address Spoofing Vector in Zend\Http\PhpEnvironment\RemoteAddress',
                'date'  => 'Thu, 31 October 2013 14:00:00 -0500',
            ),
            'ZF2013-03' => array(
                'title' => 'ZF2013-03: Potential SQL injection due to execution of platform-specific SQL containing interpolations',
                'date'  => 'Thu, 14 March 2013 10:00:00 -0500',
            ),
            'ZF2013-02' => array(
                'title' => 'ZF2013-02: Potential Information Disclosure and Insufficient Entropy vulnerabilities in Zend\Math\Rand and Zend\Validate\Csrf Components',
                'date'  => 'Thu, 14 March 2013 10:00:00 -0500',
            ),
            'ZF2013-01' => array(
                'title' => 'ZF2013-01: Route Parameter Injection Via Query String in Zend\Mvc',
                'date'  => 'Thu, 14 March 2013 10:00:00 -0500',
            ),
            'ZF2012-05' => array(
                'title' => 'ZF2012-05: Potential XML eXternal Entity injection vectors in Zend Framework 1 Zend_Feed component',
                'date'  => 'Tue, 18 December 2012 13:00:00 -0500',
            ),
            'ZF2012-04' => array(
                'title' => 'ZF2012-04: Potential Proxy Injection Vulnerabilities in Multiple Zend Framework 2 Components',
                'date'  => 'Thu, 29 November 2012 16:00:00 +0100',
            ),
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
                                'advisory' => '(ZF|AG)20\d{2}-\d{2}',
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
