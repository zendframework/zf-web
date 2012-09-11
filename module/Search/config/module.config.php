<?php
return array(
    'search' => array(
        'apikey'                   => 'API KEY GOES HERE',
        'custom_search_identifier' => 'CUSTOM SEARCH ENGINE IDENTIFIER GOES HERE',
        'items_per_page'           => 10,
        'query_options'            => array(), // if you have any custom query options you want to use

        // 'http_client_service' => 'service name', // if you want to specify a shared HTTP client
    ),

    'router' => array('routes' => array(
        'search' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/search[/]',
                'defaults' => array(
                    'controller' => 'Search\SearchController',
                    'action'     => 'index',
                ),
            ),
        ),
    )),

    'view_manager' => array(
        'template_path_stack' => array(
            'search' => __DIR__ . '/../view',
        ),
    ),
);
