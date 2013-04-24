<?php

namespace Issues;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\ArrayAdapter as ArrayPaginator;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class IssuesController extends AbstractActionController
{
    protected $issues;

    public function setIssues(array $issues)
    {
        $this->issues = $issues;
    }

    public function indexAction()
    {
        $model = new ViewModel();
        $model->setTemplate('issues/index');
        return $model;
    }

    public function browseAction()
    {
        $version = $this->params()->fromRoute('version', 'ZF1');
        $page    = $this->params()->fromQuery('page', 1);
        $model   = new ViewModel(array(
            'version' => $version,
            'page'    => $page,
            'issues'  => new Paginator(new ArrayPaginator($this->issues[$version])),
        ));
        $model->setTemplate('issues/browse');
        return $model;
    }

    public function issueAction()
    {
        $model = new ViewModel(array(
            'issue' => $this->params()->fromRoute('issue'),
        ));
        $model->setTemplate('issues/issue');
        return $model;
    }
}
