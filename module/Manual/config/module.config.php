<?php
return array(
    'router' => array(
        'routes' => array(
            'learn' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/manual[/:lang[/:version[/:page]]]',
                    'constraints' => array(
                        'lang'    => '[a-z]{2}',
                        'version' => '[0-9\.a-z]+',
                        'page'    => '.+'
                    ),
                    'defaults' => array(
                        'controller' => 'Manual/Controller/Page',
                        'lang'       => 'en',
                        'version'    => '2.0',
                        'page'       => 'index.html',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'page-controller' => __DIR__ . '/../view',
        ),
    ),
    'zf_document_path' => array(
        '2.0' => 'path to zf2-documentation/docs/_build/html/'
    ),
);
