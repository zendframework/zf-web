<?php
/**
 * Compile the zf-web site in static HTML files
 *
 * Note: this is an experimental script, use at your own risk!
 *
 * @author Enrico Zimuel 
 */
chdir(dirname(__DIR__ . '/../public'));

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Mvc\Application;
use Zend\Http\Request;

include 'vendor/autoload.php';

$configuration = include 'config/application.config.php';

$app    = Application::init($configuration);
$config = $app->getConfig();
$urls   = filterUrls(getUrlFromRoutes($config['router']['routes']));

// Dispatch all the URLs with the ZF2 Application
foreach ($urls as $url) {
    printf("Dispatching \"%s\"...", $url);
    $smConfig = isset($configuration['service_manager']) ? $configuration['service_manager'] : [];
    $serviceManager = new ServiceManager(new ServiceManagerConfig($smConfig));

    $request = new Request();
    $request->setMethod('GET');
    $request->setUri($url);

    var_dump((string) $request);
    $serviceManager->setService('ApplicationConfig', $configuration);
    $serviceManager->get('ModuleManager')->loadModules();

    $serviceManager->setAllowOverride(true);
    $serviceManager->setService('Request', $request);
    $serviceManager->setAllowOverride(false);

    $listeners = isset($configuration['listeners']) ? $configuration['listeners'] : array();

    $app = new Application($configuration, $serviceManager);
    $app->bootstrap($listeners)->run();
    printf("done\n");

    $response = $app->getResponse();
    if ($response->getStatusCode() == 200) {
        printf("Rendering \"%s\" to file...", $url);
        file_put_contents('data/html/' . $url, $response->getBody());
        printf("done\n");
    } else {
        printf("Error on %s with HTTP status code %d\n", $url, $response->getStatusCode());
    }
}


/**
 * Returns the list of URL related to the ZF2 routes
 *
 * @param array $routes
 * @return array
 */
function getUrlFromRoutes(array $routes) {
    $result = [];
    foreach($routes as $route) {
        if (isset($route['options']['route'])) {
            $result[] = $route['options']['route'];
        }
        if (isset($route['child_routes'])) {
            $prefix = isset($route['options']['route']) ? str_replace('[/]','/', $route['options']['route']) : '';
            $result = array_merge(
                array_map(function ($value) use ($prefix) {
                    return $prefix . $value;
                }, getUrlFromRoutes($route['child_routes'])),
                $result
            );
        }
    }
    return $result;
}

/**
 * Filter the URLs removing optional route
 * and omit the /application routes
 *
 * @param array $routes
 * @return array
 */
function filterUrls(array $routes) {
    $num    = count($routes);
    $result = [];
    for ($i = 0; $i < $num; $i++) {
        if (substr($routes[$i], 0, 12) !== '/application') {
            $result[] = str_replace('[/]', '', $routes[$i]);
        }
    }
    return $result;
}
