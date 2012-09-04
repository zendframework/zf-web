<?php

namespace Changelog\Controller;

use Downloads\Model\ReleaseModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ChangelogController extends AbstractActionController
{
    protected $data;
    protected $model;

    public function setReleasesModel(ReleaseModel $model)
    {
        $this->model = $model;
    }

    public function setChangelogData(array $data)
    {
        uksort($data, function ($a, $b) {
            return strnatcasecmp($b, $a);
        });
        $this->data = $data;
    }

    public function indexAction()
    {
        $version = $this->model->getCurrentStableVersion(1);

        if (!isset($this->data[$version])) {
            throw new \DomainException('No changelog for current stable version!');
        }

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
