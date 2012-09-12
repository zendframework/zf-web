<?php

namespace Changelog\Controller;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\XmlRpc\Client\ServerProxy as XmlRpcClient;

class FetchController extends AbstractActionController
{
    protected $console;
    protected $jiraAuth;
    protected $xmlRpc;
    protected $zf1DataFile;

    public function setConsole(Console $console)
    {
        $this->console = $console;
    }

    public function setJiraAuth($auth)
    {
        $this->jiraAuth = $auth;
    }

    public function setXmlRpcClient(XmlRpcClient $xmlRpc)
    {
        $this->xmlRpc = $xmlRpc;
    }

    public function setZf1DataFile($path)
    {
        $this->zf1DataFile = $path;
    }

    public function changelogAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from the console!');
        }
        if ($request->getParam('zf1')) {
            return $this->fetchZf1();
        }
        return $this->fetchZf2();
    }

    public function fetchZf1()
    {
        $filters = $this->xmlRpc->getFavouriteFilters($this->jiraAuth);
        
        $versions = array();
        foreach ($filters as $filter) {
            if (preg_match('/fix.*?(\d+\.\d+\.\d+)/i', $filter['name'], $m)) {
                $versions[$m[1]] = $filter['id'];
            }
        }
        
        $issues = array();
        foreach ($versions as $version => $filterId) {
            $issues[$version] = $this->xmlRpc->getIssuesFromFilter($this->jiraAuth, $filterId);
        }
        
        $fileContent = "<?php\n\$issues = " 
                     . var_export($issues, 1) 
                     . ";\nreturn \$issues;";
        
        $this->console->writeLine("Writing to {$this->zf1DataFile}");
        file_put_contents($this->zf1DataFile, $fileContent);
        
        $this->console->writeLine("Removing duplicates");
        unset($issues, $fileContent);
        $allIssues = include $this->zf1DataFile;
        foreach ($allIssues as $version => $versionIssues) {
            $keys = array();
            foreach ($versionIssues as $index => $issue) {
                if (!array_key_exists('key', $issue)) {
                    continue;
                }
                $key = $issue['key'];
                if (in_array($key, $keys)) {
                    unset($versionIssues[$index]);
                    continue;
                }
                $keys[] = $key;
            }
            $allIssues[$version] = $versionIssues;
        }
        $fileContent = "<?php\n\$issues = " 
                     . var_export($allIssues, 1) 
                     . ";\nreturn \$issues;";
        
        $this->console->writeLine("Re-writing to {$this->zf1DataFile}");
        file_put_contents($this->zf1DataFile, $fileContent);
        $this->console->writeLine('[DONE]', Color::GREEN);
    }

    public function fetchZf2()
    {
    }
}
