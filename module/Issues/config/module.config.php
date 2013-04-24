<?php
return array(
    'issues' => include(__DIR__ . '/issues.php'),
    'router' => array('routes' => array(
        'issues' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/issues[/]',
                'defaults' => array(
                    'controller' => 'Issues\IssuesController',
                    'action'     => 'index',
                ),
            ),
            'may_terminate' => true,
            'child_routes' => array(
                'browse' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => ':version',
                        'defaults' => array(
                            'action' => 'browse',
                        ),
                        'constraints' => array(
                            'version' => 'ZF(1|2)',
                        ),
                    ),
                ),
                'issue' => array(
                    'type' => 'Segment',
                    'options' => array(
                        'route' => 'browse/:issue',
                        'defaults' => array(
                            'action'     => 'issue',
                        ),
                        'constraints' => array(
                            'issue' => 'ZF\d?-\d{1,5}',
                        ),
                    ),
                ),
            ),
        ),
    )),
    'view_manager' => array(
        'template_map' => array(
            'issues/index'  => __DIR__ . '/../view/issues/index.phtml',
            'issues/browse' => __DIR__ . '/../view/issues/browse.phtml',
            'issues/issue'  => __DIR__ . '/../view/issues/issue.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
