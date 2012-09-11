<?php

namespace Search;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    protected $search;

    public function setSearchService(GoogleCustomSearch $search)
    {
        $this->search = $search;
    }

    public function indexAction()
    {
        $model = new ViewModel();
        $model->setTemplate('search/index');
        $query = $this->params()->fromQuery('q', false);
        if (!$query) {
            // display search form only, as no query was provided
            return $model;
        }

        $page  = $this->params()->fromQuery('page', 1);
        $model->setVariables(array(
            'query'   => $query,
            'page'    => $page,
            'results' => $this->search->search($query, $page),
        ));
        return $model;
    }
}
