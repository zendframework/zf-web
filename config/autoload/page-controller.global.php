<?php
return array(
    'router' => array(
        'routes' => array(
            'participate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/participate[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'participate',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'contribute' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'contributor-guide[/]',
                            'defaults' => array(
                                'page'          => 'participate/contributor-guide',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'contribute-v1' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'contributor-guide-v1[/]',
                            'defaults' => array(
                                'page'          => 'participate/contributor-guide-v1',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'conferences' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'conferences[/]',
                            'defaults' => array(
                                'page'          => 'participate/conferences',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'user-groups' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'user-groups[/]',
                            'defaults' => array(
                                'page'          => 'participate/user-groups',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'blogs' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'blogs[/]',
                            'defaults' => array(
                                'page'          => 'participate/blogs',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'contributors' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'contributors[/]',
                            'defaults' => array(
                                'page'          => 'participate/contributors',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
        ),
    ),
);
