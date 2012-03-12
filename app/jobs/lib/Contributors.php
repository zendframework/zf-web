<?php
namespace ZF\Jobs;

use Zend_View as View;

class Contributors
{
    protected $view;

    public function dispatch()
    {
        $hostname = trim(shell_exec('hostname'));
        if ($hostname != 'fw02') {
            echo "    This script only works on fw02; current host is '$hostname'\n";
            exit(2);
        }

        $view = $this->getView();
        $view->contributors = array();

        echo "    Retrieving contributor names from SVN access list... ";
        if (!$access = fopen('/usr/local/svn/access.conf', 'r')) {
            echo "    FAILED opening access.conf!\n";
            exit(2);
        }
        while (!feof($access)) {
            $line = fgets($access);
            if (!preg_match('/^#/', $line)) {
                break;
            }
            if (!preg_match('/^#\s+\S+\s+(?P<name>[^<]+)<(?P<email>[^>]+)>/', $line, $matches)) {
                continue;
            }
            $view->contributors[] = array(
                'name'  => trim($matches['name']),
                'email' => trim($matches['email']),
            );
        }
        fclose($access);
        echo "    [DONE]\n";

        echo "    Preparing view partial... ";
        $html = $view->render('_contributors.phtml');
        $html = str_replace('src=', 'data-defer-src=', $html);
        echo "    [DONE]\n";

        $file = $this->getAppPath() . '/views/scripts/community/_contributors.phtml';
        file_put_contents($file, $html);
        echo "    Contributors file updated and written to $file\n";
    }

    protected function getView()
    {
        if (null !== $this->view) {
            return $this->view;
        }

        $view = new View();
        $view->addScriptPath($this->getAppPath() . '/views/scripts');
        $view->doctype('XHTML1_STRICT');

        $this->view = $view;
        return $this->view;
    }

    protected function getAppPath()
    {
        return __DIR__ . '/../..';
    }
}
