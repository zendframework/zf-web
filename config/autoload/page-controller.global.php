<?php
return array(
    'router' => array(
        'routes' => array(
            'participate' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/community',
                    'defaults' => array(
                        '__NAMESPACE__' => 'PageController\Controller',
                        'controller'    => 'Page',
                        'page'          => 'participate',
                    ),
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    /* other child routes forthcoming */
                ),
            ),
        ),
    ),
);
