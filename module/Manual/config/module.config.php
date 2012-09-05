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
                        'version' => '[0-9\.a-z]+',
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
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/learn',
                    'defaults' => array(
                        'controller' => 'Manual/Controller/Page',
                        'action'     => 'learn'
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
