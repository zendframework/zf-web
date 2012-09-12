<?php
return array(
    'changelog' => array(
        'jira' => array(
            'username' => '',
            'password' => '',
        ),
        'zf1_file' => 'data/zf1-changelog.php',
        'zf2_file' => 'data/zf2-changelog.php',
    ),
    'router' => array(
        'routes' => array(
            'changelog' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/changelog[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Changelog\Controller',
                        'controller'    => 'Changelog',
                        'action'        => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'release' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => ':version[/]',
                            'constraints' => array(
                                'version' => '\d+\.\d{1,2}.\d+((?:a|alpha|b|beta|dev(?:el)?|pr|pl|rc)(?:\d+[a-z]?)?)?',
                            ),
                            'defaults' => array(
                                'action' => 'release'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'changelog-fetch' => array(
                    'type'    => 'Simple',
                    'options' => array(
                        'route' => 'changelog fetch (zf1|zf2):version',
                        'defaults' => array(
                            'controller' => 'Changelog\Controller\Fetch',
                            'action'     => 'changelog',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'changelog' => __DIR__ . '/../view',
        ),
    ),
);
