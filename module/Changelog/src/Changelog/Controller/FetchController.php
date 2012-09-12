<?php

namespace Changelog\Controller;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Client as HttpClient;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\XmlRpc\Client\ServerProxy as XmlRpcClient;

class FetchController extends AbstractActionController
{
    const GITHUB_ZF2_TAGS = 'https://api.github.com/repos/zendframework/zf2/tags';
    const GITHUB_ZF2_REFS = 'https://api.github.com/repos/zendframework/zf2/git/refs/tags/';

    protected $console;
    protected $httpClient;
    protected $jiraAuth;
    protected $xmlRpc;
    protected $zf1DataFile;
    protected $zf2DataFile;

    public function setConsole(Console $console)
    {
        $this->console = $console;
    }

    public function setHttpClient(HttpClient $client)
    {
        $this->httpClient = $client;
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

    public function setZf2DataFile($path)
    {
        $this->zf2DataFile = $path;
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
        $data = array();
        $filter = function ($string) {
            return preg_replace("/\n\-+(?:BEGIN PGP SIGNATURE).*/s", '', $string);
        };
        
        $this->console->writeLine("Fetching list of all tags");
        $this->httpClient->setUri(self::GITHUB_ZF2_TAGS);
        $response = $this->httpClient->send();
        $tags     = json_decode($response->getBody());
        foreach ($tags as $info) {
            $tag = $info->name;
            if (preg_match('/dev(?:el)?(?:\d+(?:[a-z]+)?)?$/', $tag)) {
                // skip development tags
                continue;
            }
        
            $this->console->writeLine("    Fetching ref info for tag '$tag'");
            $this->httpClient->setUri(self::GITHUB_ZF2_REFS . $tag);
            $response = $this->httpClient->send();
            $refInfo  = json_decode($response->getBody());
            $tagUrl   = $refInfo->object->url;
        
            $this->console->writeLine("    Fetching tag metadata for tag '$tag'");
            $this->httpClient->setUri($tagUrl);
            $response = $this->httpClient->send();
            $tagInfo  = json_decode($response->getBody());
        
            $tag = str_replace('release-', '', $tag);
            $data[$tag] = $filter($tagInfo->message);
        }
        
        $fileContent = "<?php\n\$tags = " 
                     . var_export($data, 1) 
                     . ";\nreturn \$tags;";
        
        $this->console->writeLine("Writing to {$this->zf2DataFile}");
        file_put_contents($this->zf2DataFile, $fileContent);

        $this->console->writeLine('[DONE]', Color::GREEN);
    }
}
