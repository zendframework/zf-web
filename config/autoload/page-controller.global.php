<?php
return array(
    'router' => array(
        'routes' => array(
            'participate'     => array(
                'type'          => 'Segment',
                'options'       => array(
                    'route'    => '/participate[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'participate',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'contribute'    => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'contributor-guide[/]',
                            'defaults' => array(
                                'page' => 'participate/contributor-guide',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'contribute-v1' => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'contributor-guide-v1[/]',
                            'defaults' => array(
                                'page' => 'participate/contributor-guide-v1',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'conferences'   => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'conferences[/]',
                            'defaults' => array(
                                'page' => 'participate/conferences',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'user-groups'   => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'user-groups[/]',
                            'defaults' => array(
                                'page' => 'participate/user-groups',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'blogs'         => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'blogs[/]',
                            'defaults' => array(
                                'page' => 'participate/blogs',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'contributors'  => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'contributors[/]',
                            'defaults' => array(
                                'page' => 'participate/contributors',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'logos'         => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'logos[/]',
                            'defaults' => array(
                                'page' => 'participate/logos',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'irc'             => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/irc[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'contact/irc',
                    ),
                ),
            ),
            'about'           => array(
                'type'          => 'Segment',
                'options'       => array(
                    'route'    => '/about[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'about',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'faq'    => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'faq[/]',
                            'defaults' => array(
                                'page' => 'about/faq',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'faq-v1' => array(
                        'type'          => 'Segment',
                        'options'       => array(
                            'route'    => 'faq-v1[/]',
                            'defaults' => array(
                                'page' => 'about/faq-v1',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'license'         => array(
                'type'          => 'Segment',
                'options'       => array(
                    'route'    => '/license[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'license',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'new-bsd' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route' => 'new-bsd',
                        ),
                    ),
                ),
            ),
            'long-term-support'         => array(
                'type'          => 'Segment',
                'options'       => array(
                    'route'    => '/long-term-support[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'long-term-support',
                    ),
                ),
            ),
            'expressive' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/expressive[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'expressive',
                    ),
                ),
            ),
            'zend-config-xml' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/xml/zend-config-xml/1.0[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'zend-config-xml-1-0',
                    ),
                ),
            ),
        ),
    ),
);
