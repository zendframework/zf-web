<?php

namespace Changelog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ChangelogController extends AbstractActionController
{
    protected $data;

    public function setChangelogData(array $data)
    {
        uksort($data, function ($a, $b) {
            return version_compare($b, $a);
        });
        $this->data = $data;
    }

    public function indexAction()
    {
        $version = key($this->data);
        $model = new ViewModel(array(
            'version'  => $version,
            'issues'   => $this->data[$version],
            'versions' => array_keys($this->data),
        ));
        $model->setTemplate('changelog/release');
        return $model;
    }

    public function releaseAction()
    {
        $version = $this->params('version', false);
        if (!$version) {
            return $this->redirect()->toRoute('changelog');
        }

        if (!isset($this->data[$version])) {
            return $this->redirect()->toRoute('changelog');
        }

        $model = new ViewModel(array(
            'version'  => $version,
            'issues'   => $this->data[$version],
            'versions' => array_keys($this->data),
        ));
        $model->setTemplate('changelog/release');
        return $model;
    }
}
