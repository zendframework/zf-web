<?php
return array(
     'navigation' => array(
         'default' => array(
             array(
                 'label' => 'Home',
                 'route' => 'home',
             )
         ),
     ),

    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Sitemap\Navigation\Service\ManualNavigationFactory',
        ),
    ),

    'router' => array(
        'routes' => array(
            'xml-sitemap' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/sitemap.xml',
                    'defaults' => array(
                        'controller' => 'Sitemap\Controller\Index',
                        'action'     => 'xml',
                    ),
                ),
            ),
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Sitemap\Controller\Index' => 'Sitemap\Controller\IndexController'
        )
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'sitemap' => __DIR__ . '/../view',
        ),
    ),
);
