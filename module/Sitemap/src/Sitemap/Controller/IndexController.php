<?php

namespace Sitemap\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function xmlAction()
    {
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'text/xml');

        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);

        return $viewModel;
    }
}
