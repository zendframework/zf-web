<?php

namespace PageController\Controller;

use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\Model\ViewModel;

class PageController extends AbstractController
{
    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * Set the resolver
     * 
     * @param  ResolverInterface $resolver 
     * @return PageController
     */
    public function setResolver(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * Listen to dispatch event
     *
     * Retrieves "page" parameter from route matches. If none found, assumes
     * a 404 status code and page.
     *
     * Checks to see if the retrieved page can be resolved by the resolver. If
     * not, assumes a 404 code and page.
     *
     * Otherwise, returns a view model with a template matching the page from
     * this module.
     * 
     * @param  MvcEvent $e 
     * @return ViewModel
     */
    public function onDispatch(MvcEvent $e)
    {
        $model   = new ViewModel();
        $matches = $e->getRouteMatch();
        $page    = $matches->getParam('page', false);

        if (!$page) {
            return $this->return404Page($model, $e->getResponse());
        }

        $page = 'page-controller/' . $page;
        if (!$this->resolver->resolve($page)) {
            return $this->return404Page($model, $e->getResponse());
        }

        $model->setTemplate($page);
        $e->setResult($model);
        return $model;
    }

    /**
     * Return a 404 page and status
     * 
     * @param  ViewModel $model 
     * @param  \Zend\Http\Response $response 
     * @return ViewModel
     */
    protected function return404Page(ViewModel $model, $response)
    {
        $model->setTemplate('application/error/404');
        $response->setStatusCode(404);
        return $model;
    }
}
