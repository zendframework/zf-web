<?php

namespace Search;

use Zend\Http\Client as HttpClient;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class Module implements ConfigProviderInterface, ServiceProviderInterface,
                        ControllerProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'Search\GoogleCustomSearch' => function ($services) {
                $config = $services->get('Config');
                if (!isset($config['search'])) {
                    throw new ServiceNotFoundException(sprintf(
                        'Unable to create %s; missing configuration key "search", with subkeys "apikey" and "custom_search_identifier"',
                        __NAMESPACE__ . '\GoogleCustomSearch'
                    ));
                }
                $config = $config['search'];
                if (!isset($config['apikey']) || !is_string($config['apikey'])) {
                    throw new ServiceNotFoundException(sprintf(
                        'Unable to create %s; missing subkey "apikey"',
                        __NAMESPACE__ . '\GoogleCustomSearch'
                    ));
                }
                if (!isset($config['custom_search_identifier']) || !is_string($config['custom_search_identifier'])) {
                    throw new ServiceNotFoundException(sprintf(
                        'Unable to create %s; missing subkey "custom_search_identifier"',
                        __NAMESPACE__ . '\GoogleCustomSearch'
                    ));
                }

                $queryOptions = isset($config['query_options']) && is_array($config['query_options']) 
                              ? $config['query_options'] 
                              : array();

                $httpClientService = isset($config['http_client_service']) && is_string($config['http_client_service'])
                                   ? $config['http_client_service'] 
                                   : false;
                if ($httpClientService && $services->has($httpClientService)) {
                    $httpClient = $services->get($httpClientService);
                } else {
                    $httpClient = new HttpClient();
                    $httpClient->setOptions(array(
                        'adapter'   => 'Zend\Http\Client\Adapter\Curl',
                        'keepalive' => true,
                        'timeout'   => 10,
                    ));
                }

                $search = new GoogleCustomSearch($httpClient, $config['apikey'], $config['custom_search_identifier'], $queryOptions);

                if (isset($config['items_per_page'])) {
                    $search->setItemsPerPage($config['items_per_page']);
                }

                return $search;
            },
        ));
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Search\SearchController' => function ($controllers) {
                $services   = $controllers->getServiceLocator();
                $search     = $services->get('Search\GoogleCustomSearch');
                $controller = new SearchController();
                $controller->setSearchService($search);
                return $controller;
            },
        ));
    }
}
