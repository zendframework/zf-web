<?php

namespace Security\Controller;

use Security\Model\AdvisoryModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdvisoryController extends AbstractActionController
{
    protected $model;

    public function setAdvisoryModel(AdvisoryModel $model)
    {
        $this->model = $model;
    }

    public function indexAction()
    {
    }

    public function advisoriesAction()
    {
        return array(
            'advisories' => $this->model->getAdvisories(),
            'page'       => $this->params()->fromQuery('page', 1),
        );
    }

    public function feedAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('security/advisory/feed');
        $viewModel->setVariables(array(
            'advisories' => $this->model->getAdvisories(),
            'response'   => $this->getResponse()
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function advisoryAction()
    {
        $advisory = $this->params('advisory', false);
        if (!$advisory) {
            return $this->redirect()->toRoute('security/advisories');
        }
        if (!$this->model->advisoryExists($advisory)) {
            $this->getResponse()->setStatusCode(404);
            $viewModel = new ViewModel();
            $viewModel->setTemplate('security/404');
            return $viewModel;
        }
        return array(
            'advisory' => $this->model->getAdvisoryInfo($advisory),
        );
    }
}
