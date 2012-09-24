<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Archives\Archives' => 'Archives\ArchivesController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'archives' => __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'archives' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/archives[/]',
                    'defaults' => array(
                        'controller' => 'Archives\Archives',
                        'action' => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'lists' => array(
                        'type' => 'Regex',
                        'options' => array(
                            'regex' => '(?P<id>announcements|auth|core|contributors|database|documentation|general|gdata|i18n|formats|mvc|server|web-services)/?',
                            'defaults' => array(
                                'id' => 'all',
                            ),
                            'spec' => '%id%',
                        ),
                    ),
                    'subscribe' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => 'subscribe[/]',
                            'defaults' => array(
                                'action' => 'subscribe',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);

