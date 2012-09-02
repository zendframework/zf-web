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
        
        if ('1.' === substr($version, 0, 2)) {
            $page = preg_replace('/\.html/','.phtml', $page);
        }
        $docFile = $this->params[$version][$lang] . $page;
        
        if (!file_exists($docFile)) {
            return $this->return404Page($model, $e->getResponse());
        }
        
        $content = $this->getPageContent($docFile, $version);
        if (false === $content) {
            return $this->return404Page($model, $e->getResponse());
        }
        $css = $this->getCss($version);
        
        $model->setVariable('body', $content['body']);
        $model->setVariable('sidebar', $content['sidebar']);
        $model->setVariable('version', $version);
        $model->setVariable('versions', array_keys($this->params));
        $model->setVariable('css', $css);
        $model->setTemplate('manual/page-controller/manual');
        $e->setResult($model);
        return $model;
    }
    /**
     * Get page content (body, sidebar) according to the doc version
     * 
     * @param  string $file
     * @param  string $version
     * @return boolean|array 
     */
    protected function getPageContent($file, $version)
    {
        $pageContent = array();
        if ('1.' === substr($version, 0, 2)) {
            $content = file_get_contents($file);
            $hr = strpos($content, '<hr />');
            if (false !== $hr) {
                $pageContent['body'] = substr($content, $hr+6);
            }
            if (preg_match('{<ul[^>]*>(.*?)</ul>}s', $content, $matches)) {
                $pageContent['sidebar'] = $matches[0];
            }
        } elseif ('2.0' === $version) {
            $doc  = new DomQuery(file_get_contents($file));
            // body
            $elem = $doc->queryXpath('//div[@class="body"]')->current();
            $pageContent['body'] = $elem->ownerDocument->saveXML($elem);
            $pageContent['body'] = preg_replace('/(\.\.\/)*(_static|_images)/i','/images/manual', $pageContent['body']);
            $pageContent['body'] = preg_replace('/width: [6-9][0-9]{2}/i','width: 650', $pageContent['body']);
            // sidebar
            $elem    = $doc->queryXpath('//div[@class="sphinxsidebarwrapper"]')->current();
            $pageContent['sidebar'] = $elem->ownerDocument->saveXML($elem);
            $pageContent['sidebar'] = preg_replace('/(\.\.\/)*(_static|_images)/i','/images/manual', $pageContent['sidebar']);
        }
        return $pageContent;
    }
    /**
     * Get the css for the specific version
     * 
     * @param  string $version 
     * @return array
     */
    protected function getCss($version)
    {
        $css = array();
        if ('1.' === substr($version, 0, 2)) {
            $css[] = '/css/manual/1/manual.css';
            $css[] = '/css/manual/1/styles.css';
        } elseif ('2.0' === $version) {
            $css[] = '/css/manual/2/default.css';
            $css[] = '/css/manual/2/pygments.css';
        }
        return $css;
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
