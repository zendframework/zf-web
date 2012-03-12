<?php

class Zf2_BlogController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $page = $this->getRequest()->getParam('page', 1);
        $script = 'list-p' . $page;
        if (!$this->view->getScriptPath('blog/' . $script . '.phtml')) {
            $page = 1;
        }
        $this->getHelper('viewRenderer')->setScriptAction('list-p' . $page);
    }

    public function entryAction()
    {
        $entry = $this->getRequest()->getParam('entry', false);
        if (!$entry) {
            $this->view->script = 'entry was not in request';
            $this->getHelper('viewRenderer')->setScriptAction('post-not-found');
            return;
        }

        $stub   = $this->stub($entry);
        $script = 'blog/' . $stub . '.phtml';
        if (!$this->view->getScriptPath($script)) {
            $this->view->script = $script;
            $this->getHelper('viewRenderer')->setScriptAction('post-not-found');
            return;
        }

        $this->getHelper('viewRenderer')->setScriptAction($stub);
    }

    public function feedAction()
    {
        $this->getHelper('viewRenderer')->setNoRender();
        $this->getHelper('layout')->disableLayout();
        $response = $this->getResponse();
        $response->setBody(
            file_get_contents(__DIR__ . '/../views/scripts/blog/feed.xml')
        );
        $response->setHeader('Content-Type', 'application/rss+xml; charset=utf-8');
    }

    public function stub($title)
    {
        $stub = str_replace(array(' ', '+', '.'), '-', $title);
        $stub = preg_replace('/[^a-z0-9_-]/i', '', $stub);
        $stub = strtolower($stub);
        return $stub;
    }
}
