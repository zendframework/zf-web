<?php

namespace Manual\Controller;

use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\Model\ViewModel;
use Zend\Dom\Query as DomQuery;
use \DomElement;

class PageController extends AbstractController
{
    /**
     * @var array 
     */
    protected $params;
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
     * Set params of the controller
     * 
     * @param  array $params
     * @return PageController 
     */
    public function setParams(array $params)
    {
        $this->params = $params;
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
        $version = $matches->getParam('version', false);
        $lang    = $matches->getParam('lang', false);
        if (!$page || !$version || !$lang) {
            return $this->return404Page($model, $e->getResponse());
        }

        $docFile = $this->params[$version] . $page;
        if (!file_exists($docFile)) {
            return $this->return404Page($model, $e->getResponse());
        }
        
        $doc     = new DomQuery(file_get_contents($docFile));
        $body    = $this->getBody($doc, $version);
        $sidebar = $this->getSidebar($doc, $version);
        
        $model->setVariable('body', $body);
        $model->setVariable('sidebar', $sidebar);
        $model->setVariable('version', $version);
        $model->setTemplate('page-controller/manual');
        $e->setResult($model);
        return $model;
    }
    /**
     * Get the body of the document according with the ZF version
     * 
     * @param  DomQuery $doc
     * @param  string $version
     * @return string|boolean
     */
    protected function getBody(DomQuery $doc, $version)
    {
        switch ($version) {
            case '2.0' :
                // get body
                $body   = $doc->queryXpath('//div[@class="body"]')->current();
                $output = $body->ownerDocument->saveXML($body);
                $output = preg_replace('/(\.\.\/)*(_static|_images)/i','/images/manual', $output);
                $output = preg_replace('/width: [6-9][0-9]{2}/i','width: 650', $output);
                break;
            
            default : 
                return false;
        }
        return $output;
    }
    /**
     * Get the sidebar of the document according with the ZF version
     * 
     * @param  DomQuery $doc
     * @param  string $version
     * @return string|boolean
     */
    protected function getSidebar(DomQuery $doc, $version)
    {
        switch ($version) {
            case '2.0' :
                // get sidebar
                $sidebar = $doc->queryXpath('//div[@class="sphinxsidebarwrapper"]')->current();
                $output  = $sidebar->ownerDocument->saveXML($sidebar);
                $output  = preg_replace('/(\.\.\/)*(_static|_images)/i','/images/manual', $output);   
                break;
            
            default:
                return false;
        }
        return $output;
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
