<?php
namespace Application\Controller;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Client as HttpClient;
use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController
{
    protected $config = array();
    protected $console;

    public function setConsole(Console $console)
    {
        $this->console = $console;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    protected function verifyConsole()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw RuntimeException(sprintf(
                '%s can only be run via the Console',
                __METHOD__
            ));
        }
    }

    protected function reportError($width, $length, $message, $e = null)
    {
        if (($length + 9) > $width) {
            $this->console->writeLine('');
            $length = 0;
        }
        $spaces = $width - $length - 9;
        $this->console->writeLine(str_repeat('.', $spaces) . '[ ERROR ]', Color::RED);
        $this->console->writeLine($message);
        if ($e) {
            $this->console->writeLine($e->getTraceAsString());
        }
    }

    protected function reportSuccess($width, $length)
    {
        if (($length + 8) > $width) {
            $this->console->writeLine('');
            $length = 0;
        }
        $spaces = $width - $length - 8;
        $this->console->writeLine(str_repeat('.', $spaces) . '[ DONE ]', Color::GREEN);
    }

    public function githubContributorsAction()
    {
        $this->verifyConsole();

        $width = $this->console->getWidth();
        $this->console->writeLine('Fetching GitHub Contributors', Color::GREEN);

        $client = new HttpClient();
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');
        $client->setUri('https://api.github.com/repos/zendframework/zf2/contributors');
        $response = $client->send();
        if (!$response->isSuccess()) {
            // report failure
            $message = $response->getStatusCode() . ': ' . $response->getStatusMessage();
            $this->reportError($width, 0, $message);
            return;
        }
        $body         = $response->getBody();
        $contributors = json_decode($body, true);
        $total        = count($contributors);

        foreach ($contributors as $i => $contributor) {
            $message = sprintf('    Processing %d/%d', $i, $total);
            $this->console->write($message);
            $client->setUri("https://api.github.com/users/{$contributor['login']}");
            $response = $client->send();
            if (!$response->isSuccess()) {
                // report failure
                $error = $response->getStatusCode() . ': ' . $response->getStatusMessage();
                $this->reportError($width, strlen($message), $error);
            }
            $body     = $response->getBody();
            $userInfo = json_decode($body, 1);
            $contributors[$i]['user_info'] = $userInfo;
            $this->reportSuccess($width, strlen($message));
        }

        $this->console->writeLine(str_repeat('-', $width));
        $message = 'Writing file';
        $this->console->write($message, Color::BLUE);
        // file_put_contents(__DIR__ . '/../../../data/contributors/contributors.pson', serialize($contributors));
        $path = $this->config['github-contributors']['output_file'];
        file_put_contents($path, serialize($contributors));
        $this->reportSuccess($width, strlen($message));
        $this->console->writeLine(sprintf('File written to %s', $path));
    }
}
