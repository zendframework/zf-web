<?php
return array(
    'changelog' => array(
        'jira' => array(
            'username' => '',
            'password' => '',
        ),
        'file' => 'data/zf1-changelog.php',
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
    'view_manager' => array(
        'template_path_stack' => array(
            'changelog' => __DIR__ . '/../view',
        ),
    ),
);
