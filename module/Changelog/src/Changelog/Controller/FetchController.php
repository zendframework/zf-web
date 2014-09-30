<?php

namespace Changelog\Controller;

use DateTime;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Client as HttpClient;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\XmlRpc\Client\ServerProxy as XmlRpcClient;

class FetchController extends AbstractActionController
{
    const GITHUB_RELEASE  = 'https://api.github.com/repos/zendframework/%s/releases/%s';
    const GITHUB_RELEASES = 'https://api.github.com/repos/zendframework/%s/releases';
    const GITHUB_TAGS     = 'https://api.github.com/repos/zendframework/%s/tags';

    protected $console;
    protected $githubToken;
    protected $httpClient;
    protected $jiraAuth;
    protected $xmlRpc;
    protected $zf1DataFile;
    protected $zf2DataFile;

    public function setConsole(Console $console)
    {
        $this->console = $console;
    }

    public function setGithubToken($token)
    {
        $this->githubToken = $token;
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

        $version = $this->params()->fromRoute('version', false);

        if ('1.' == substr($version, 0, 2)) {
            return $this->fetchZf1($version);
        }

        if ('2.' == substr($version, 0, 2)) {
            return $this->fetchZf2($version);
        }

        $this->emitError('You must provide a valid version string in order to fetch a changelog.');
    }

    public function fetchZf1($version)
    {
        if ('1.' != substr($version, 0, 2)) {
            $this->emitError('Invalid Zend Framework 1 version string; must begin with "1.".');
            return;
        }

        if (version_compare('1.12.4', $version, 'gt')) {
            return $this->fetchZf1ChangelogFromJira($version);
        }

        return $this->fetchZf1ChangelogFromGithub($version);
    }

    public function fetchZf2($version)
    {
        $tagData = $this->fetchGithubChangelog('zf2', 'release-' . $version);

        $data = include($this->zf2DataFile);
        $data[$version] = $tagData;

        $fileContent = "<" . "?php\n\$tags = " 
                     . var_export($data, 1) 
                     . ";\nreturn \$tags;";
        
        $this->console->writeLine("Writing to {$this->zf2DataFile}");
        file_put_contents($this->zf2DataFile, $fileContent);

        $this->console->writeLine('[DONE]', Color::GREEN);
    }

    /**
     * For legacy reasons only currently
     *
     * Create a route to this method in order to re-generate the entire 
     * changelog at once.
     */
    public function fetchAllZf1Changelogs()
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
            $issues[$version] = $this->fetchZf1ChangelogByJiraFilter($version, $filterId);
        }
        
        $this->console->writeLine("Writing to {$this->zf1DataFile}");
        $fileContent = "<" . "?php\n\$tags = " 
                     . var_export($issues, 1) 
                     . ";\nreturn \$tags;";
        file_put_contents($this->zf1DataFile, $fileContent);
        
        $this->console->writeLine('[DONE]', Color::GREEN);
    }

    /**
     * For legacy reasons only currently
     *
     * Create a route to this method in order to re-generate the entire 
     * changelog at once.
     */
    public function fetchAllZf2Changelogs()
    {
        $data = array();
        $this->console->writeLine("Fetching list of all tags");

        if ($this->githubToken) {
            $httpRequest = $this->httpClient->getRequest();
            $httpRequest->getHeaders()->addHeaderLine('Authorization', 'token ' . $this->githubToken);
        }

        $uri = sprintf(self::GITHUB_TAGS, 'zf2');
        $this->httpClient->setUri($uri);

        $response = $this->httpClient->send();

        if (!$response->isOk()) {
            $this->emitError(sprintf('Received response code %d with body %s', $response->getStatusCode(), $response->getBody()));
            return;
        }

        $tags = json_decode($response->getBody());
        foreach ($tags as $info) {
            $tag = $info->name;
            if (preg_match('/dev(?:el)?(?:\d+(?:[a-z]+)?)?$/', $tag)) {
                // skip development tags
                continue;
            }
        
            $tagData = $this->fetchGithubChangelog('zf2', $tag);

            if (!$tagData) {
                return;
            }

            $tag     = str_replace('release-', '', $tag);
            $data[$tag] = $tagData;
        }
        
        $fileContent = "<" . "?php\n\$tags = " 
                     . var_export($data, 1) 
                     . ";\nreturn \$tags;";
        
        $this->console->writeLine("Writing to {$this->zf2DataFile}");
        file_put_contents($this->zf2DataFile, $fileContent);

        $this->console->writeLine('[DONE]', Color::GREEN);
    }

    protected function fetchZf1ChangelogFromJira($version)
    {
        $this->console->writeLine("Fetching JIRA filter for version $version");
        $filters = $this->xmlRpc->getFavouriteFilters($this->jiraAuth);
        
        $filterId = false;
        $versions = array();
        $compare  = preg_quote($version);
        foreach ($filters as $filter) {
            if (preg_match('/fix.*?' . $compare . '/i', $filter['name'], $m)) {
                $filterId = $filter['id'];
            }
        }

        if (!$filterId) {
            $this->emitError(sprintf('Received response code %d with body %s', $response->getStatusCode(), $response->getBody()));
            return;
        }
        
        $this->console->writeLine("Fetching JIRA issues for version $version");
        $issues = $this->fetchZf1ChangelogByJiraFilter($version, $filterId);

        $this->console->writeLine("Writing to {$this->zf1DataFile}");
        $changelog = include($this->zf1DataFile);
        $changelog[$version] = $issues;
        $fileContent = "<" . "?php\n\$tags = " 
                     . var_export($issues, 1) 
                     . ";\nreturn \$tags;";
        file_put_contents($this->zf1DataFile, $fileContent);
        
        $this->console->writeLine('[DONE]', Color::GREEN);
    }

    protected function fetchZf1ChangelogByJiraFilter($version, $filterId)
    {
        $this->console->writeLine("Fetching JIRA issues for version $version");
        $issues = $this->xmlRpc->getIssuesFromFilter($this->jiraAuth, $filterId);

        $this->console->writeLine("Removing duplicates");
        $keys = array();
        foreach ($issues as $index => $issue) {
            if (!array_key_exists('key', $issue)) {
                continue;
            }
            $key = $issue['key'];
            if (in_array($key, $keys)) {
                unset($issues[$index]);
                continue;
            }
            $keys[] = $key;
        }
        return $issues;
    }

    protected function fetchZf1ChangelogFromGithub($version)
    {
        $tagData = $this->fetchGithubChangelog('zf1', 'release-' . $version);

        $data = include($this->zf1DataFile);
        $data[$version] = $tagData;

        $fileContent = "<" . "?php\n\$tags = " 
                     . var_export($data, 1) 
                     . ";\nreturn \$tags;";
        
        $this->console->writeLine("Writing to {$this->zf1DataFile}");
        file_put_contents($this->zf1DataFile, $fileContent);

        $this->console->writeLine('[DONE]', Color::GREEN);
    }

    protected function fetchGithubChangelog($zfVersion, $version)
    {
        $filter = function ($string, DateTime $date) use ($version) {
            $dateString = $date->format('Y-m-d');

            $string = preg_replace("/\r/", '', $string);
            $string = preg_replace("/\n\-+(?:BEGIN PGP SIGNATURE).*/s", '', $string);
            $string = preg_replace('/^(Zend Framework \d+\.\d+\.\d+[^\n]*)/s', '$1 (' . $dateString .')', $string);

            if (! preg_match('/^Zend Framework \d+\.\d+\.\d+/s', $string)) {
                $string = sprintf("Zend Framework %s (%s)\n\n%s", $version, $dateString, $string);
            }

            return $string;
        };
        
        $this->console->writeLine("    Fetching ref info for tag '$version'");
        $uri = sprintf(self::GITHUB_RELEASES, $zfVersion);
        $this->httpClient->setUri($uri);
        $response = $this->httpClient->send();

        if (!$response->isOk()) {
            $this->emitError(sprintf('Received response code %d with body %s', $response->getStatusCode(), $response->getBody()));
            return;
        }

        $releases  = json_decode($response->getBody());
        $releaseId = false;
        foreach ($releases as $release) {
            if ($release->tag_name === $version) {
                $releaseId = $release->id;
                break;
            }
        }

        if (!$releaseId) {
            $this->emitError(sprintf('Could not find release entry for version %s', $version));
            return;
        }

        $uri = sprintf(self::GITHUB_RELEASE, $zfVersion, $releaseId);
        $this->httpClient->setUri($uri);
        $response = $this->httpClient->send();

        if (!$response->isOk()) {
            $this->emitError(sprintf('Received response code %d with body %s', $response->getStatusCode(), $response->getBody()));
            return;
        }

        $tagInfo  = json_decode($response->getBody());
        $tagDate  = new DateTime($tagInfo->created_at);
    
        $this->console->writeLine(    '[DONE]', Color::GREEN);

        return $filter($tagInfo->body, $tagDate);
    }

    protected function emitError($message)
    {
        $this->getResponse()->setErrorLevel(1);
        $this->console->writeLine("[FAILED]", Color::RED);
        $this->console->writeLine($message);
    }
}
