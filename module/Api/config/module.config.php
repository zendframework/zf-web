<?php
return array(
    'api' => array(
        // 'cla_users_path' => '' // You'll want to set this in your global or local config
    ),
    'router' => array('routes' => array(
        'api' => array(
            'child_routes' => array(
                'cla' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/cla',
                        'defaults' => array(
                            'controller' => 'Api\UserController',
                        ),
                    ),
                    'may_terminate' => false,
                    'child_routes' => array(
                        'email' => array(
                            'type' => 'Literal',
                            'options' => array(
                                'route' => '/by-email',
                                'defaults' => array(
                                    'action' => 'byEmail',
                                ),
                            ),
                        ),
                        'username' => array(
                            'type' => 'Literal',
                            'options' => array(
                                'route' => '/by-username',
                                'defaults' => array(
                                    'action' => 'byUsername',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    )),
);
