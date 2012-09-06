<?php
return array(
    'router' => array(
        'routes' => array(
            'manual' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/manual/:version/:lang/:page',
                    'constraints' => array(
                        'lang'    => '[a-z]{2}',
                        'version' => '[0-9\.]+',
                        'page'    => '.+'
                    ),
                    'defaults' => array(
                        'controller' => 'Manual/Controller/Page',
                        'action'     => 'manual',
                        'lang'       => 'en',
                        'version'    => '2.0',
                        'page'       => 'index.html',
                    ),
                ),
            ),
            'learn' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/learn[/]',
                    'defaults' => array(
                        'controller' => 'Manual/Controller/Page',
                        'action'     => 'learn'
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'training-and-certification' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => 'training-and-certification[/]',
                            'defaults' => array(
                                'action' => 'training',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'support-and-consulting' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => 'support-and-consulting[/]',
                            'defaults' => array(
                                'action' => 'support',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'docs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/docs[/]',
                    'defaults' => array(
                        'controller' => 'Manual/Controller/Page',
                        'action'     => 'learn'
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'api' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => 'api[/]',
                            'defaults' => array(
                                'controller' => 'Manual/Controller/Page',
                                'action'     => 'api'
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                 ),
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'manualurl' => 'Manual\View\Helper\ManualUrl',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'manual' => __DIR__ . '/../view',
        ),
    ),
    // Feed this with pairs of version => (lang => path) sets
    'zf_document_path' => array()
);
