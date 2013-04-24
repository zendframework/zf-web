<?php

/**
 * This reproduction script shall accompany the issue reported at
 * http://framework.zend.com/issues/browse/ZF-143
 *
 * Assumptions:
 *   ZF2 beta 2
 *
 * Result:
 *   This script should exit without throwing an exception or output
 */

// Ensure ZF is on the include path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(__DIR__ . '/../vendor/Zend/library'),
    get_include_path(),
)));

// Setup autoloader
require_once 'Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
	'Zend\Loader\StandardAutoloader' => array(
		'fallback_autoloader' => true
	)
));

// Create a TreeRouteStack with a parent & child route
$router = new Zend\Mvc\Router\Http\TreeRouteStack();
$router->addRoutes(array(
	'core' => array(
		'type' => 'segment',
		'options' => array(
			'route'	=> '/root/:param1',
			'defaults' => array(
				'controller' => 'Contents\Controller\ContentsController',
				'schemas'    => array(
					'contents/1' => 'Contents\View\Contents1'
				)
			)
		),
		'may_terminate' => true,
		'child_routes' => array(
			'optional-segment' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/child[/:param2]',
					'defaults' => array(
						'controller' => 'MyModule\Controllers\MyControllerController',
						'action' => 'index'
					),
				),
			),
		)
	)
));

// Setup parameters with one param defined and the other set to NULL (does not exist)
$routeParams = array(
	'param1' => 'unique-identifier',
	'param2' => NULL
);

// Attempt to assemble a path that would match the child route without the optional parameter
$assembledRoute = $router->assemble($routeParams, array('name' => 'core/optional-segment')) . "\n";

// Expected: $assembledRoute = '/root/unique-identifier/child'
