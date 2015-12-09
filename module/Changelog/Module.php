<?php

namespace Changelog;

use RuntimeException;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Http\Client as HttpClient;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\XmlRpc\Client as XmlRpcClient;

class Module implements ConsoleUsageProviderInterface, ConfigProviderInterface,
                        ServiceProviderInterface, ControllerProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'Changelog\XmlRpc\Client' => function ($services) {
                $config = $services->get('Config');
                if (!isset($config['changelog'])) {
                    throw new RuntimeException(
                        'Expecting a "changelog" key in configuration; none found'
                    );
                }
                $config = $config['changelog'];
                if (!isset($config['jira']) || !is_array($config['jira'])) {
                    throw new RuntimeException(
                        'Expecting an array of JIRA credentials in "changelog" configuration; none found'
                    );
                }
                $jiraUrl = isset($config['jira']['url']) ? $config['jira']['url'] : 'http://framework.zend.com/issues/rpc/xmlrpc';
                $cxn     = new XmlRpcClient($jiraUrl);
                $client  = $cxn->getProxy('jira1');
                return $client;
            },
            'Changelog\Jira\Auth' => function ($services) {
                $config   = $services->get('Config');
                if (!isset($config['changelog'])) {
                    throw new RuntimeException(
                        'Expecting a "changelog" key in configuration; none found'
                    );
                }
                $config = $config['changelog'];
                if (!isset($config['jira']) || !is_array($config['jira'])) {
                    throw new RuntimeException(
                        'Expecting an array of JIRA credentials in "changelog" configuration; none found'
                    );
                }
                $jiraCredentials = $config['jira'];

                $client = $services->get('Changelog\XmlRpc\Client');
                $auth   = $client->login(
                    $jiraCredentials['username'], 
                    $jiraCredentials['password']
                );
                return $auth;
            },
            'Changelog\Http\Client' => function ($services) {
                $client = new HttpClient();
                $client->setOptions(array(
                    'adapter'   => 'Zend\Http\Client\Adapter\Curl',
                    'keepalive' => true,
                    'timeout'   => 10,
                ));
                return $client;
            },
        ));
    }

    public function getControllerConfig()
    {
        return array('factories' => array(
            'Changelog\Controller\Changelog' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                $config   = $services->get('Config');
                if (!isset($config['changelog'])) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; missing configuration'
                    );
                }

                $zf1data = include $config['changelog']['zf1_file'];
                if (!$zf1data || !is_array($zf1data)) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; invalid or missing ZF1 changelog data'
                    );
                }

                $zf2data = include $config['changelog']['zf2_file'];
                if (!$zf2data || !is_array($zf2data)) {
                    throw new ServiceNotCreatedException(
                        'Could not create ChangelogController; invalid or missing ZF2 changelog data'
                    );
                }

                $data = array_merge($zf1data, $zf2data);

                $controller = new Controller\ChangelogController();
                $controller->setChangelogData($data);
                return $controller;
            },
            'Changelog\Controller\Fetch' => function ($controllers) {
                $services = $controllers->getServiceLocator();
                $config   = $services->get('Config');
                if (!isset($config['changelog'])) {
                    throw new RuntimeException(
                        'Expecting a "changelog" key in configuration; none found'
                    );
                }
                $config = $config['changelog'];
                if (!isset($config['zf1_file'])) {
                    throw new RuntimeException(
                        'Expecting a "zf1_file" key in "changelog" configuration; none found'
                    );
                }
                $zf1DataFile = $config['zf1_file'];
                if (!isset($config['zf2_file'])) {
                    throw new RuntimeException(
                        'Expecting a "zf2_file" key in "changelog" configuration; none found'
                    );
                }
                $zf2DataFile = $config['zf2_file'];

                $controller = new Controller\FetchController();
                $controller->setConsole($services->get('Console'));
                $controller->setZf1DataFile($zf1DataFile);
                $controller->setZf2DataFile($zf2DataFile);
                // $controller->setXmlRpcClient($services->get('Changelog\XmlRpc\Client'));
                // $controller->setJiraAuth($services->get('Changelog\Jira\Auth'));
                $controller->setHttpClient($services->get('Changelog\Http\Client'));

                if (isset($config['github_token']) && $config['github_token']) {
                    $controller->setGithubToken($config['github_token']);
                }

                return $controller;
            },
        ));
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
            'changelog fetch --version=' => 'Retrieve and process changelog',
            array('--version=', 'The specific ZF version for which to generate a changelog'),
        );
    }
}
