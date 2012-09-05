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
    'zf_document_path' => array(
        '2.0' => array (
            'en' => 'path to /zf2-documentation/docs/_build/html/'
        ),
        '1.12' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.12/en/',
        ),
        '1.11' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.11/en/',
        ),
        '1.10' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.10/en/',
        ),
        '1.9' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.9/en/',
        ),
        '1.8' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.8/en/',
        ),
        '1.7' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.7/en/',
        ),
        '1.6' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.6/en/',
        ),
        '1.5' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.5/en/',
        ),
        '1.0' => array (
            'en' => 'path to /zfweb-manual/views/manual/1.0/en/',
        ),
    ),
);
