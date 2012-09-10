<?php

namespace Manual\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\Model\ViewModel;
use Zend\Dom\Query as DomQuery;
use \DomElement;

class PageController extends AbstractActionController
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
     * Manual Action
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
     * @return ViewModel
     */
    public function manualAction()
    {
        $model   = new ViewModel();
        $matches = $this->getEvent()->getRouteMatch();
        $page    = $matches->getParam('page', false);
        $version = $matches->getParam('version', false);
        $lang    = $matches->getParam('lang', false);
        if (!$page || !$version || !$lang) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }
        $name = $page;
        
        if ('1.' === substr($version, 0, 2)) {
            $page = preg_replace('/\.html/','.phtml', $page);
        }
        $docFile = $this->params[$version][$lang] . $page;

        if (!file_exists($docFile)) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }

        $content = $this->getPageContent($docFile, $version);
        if (false === $content) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }
        $css = $this->getCss($version);
        
        $model->setVariable('name', $name);
        $model->setVariable('lang', $lang);
        $model->setVariable('title', $content['title']);
        $model->setVariable('body', $content['body']);
        $model->setVariable('sidebar', $content['sidebar']);
        $model->setVariable('version', $version);
        $model->setVariable('versions', array_keys($this->params));
        $model->setVariable('css', $css);
        $model->setTemplate('manual/page-controller/manual');
        return $model;
    }

    /**
     * Learn Action
     * 
     * @return ViewModel 
     */
    public function learnAction()
    {
        $model = new ViewModel();
        $model->setTemplate('manual/page-controller/learn');
        return $model;
    }

    /**
     * training Action
     * 
     * @return ViewModel 
     */
    public function trainingAction()
    {
        $model = new ViewModel();
        $model->setTemplate('manual/page-controller/learn/training-and-certification');
        return $model;
    }

    /**
     * support Action
     * 
     * @return ViewModel 
     */
    public function supportAction()
    {
        $model = new ViewModel();
        $model->setTemplate('manual/page-controller/learn/support-and-consulting');
        return $model;
    }

    /**
     * API Action
     * 
     * @return ViewModel
     */
    public function apiAction()
    {
        $model = new ViewModel();
        $model->setTemplate('manual/page-controller/api');
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
        if ('1.' === substr($version, 0, 2)) {
            return $this->getV1PageContent($file);
        }
        return $this->getV2PageContent($file);
    }

    /**
     * Get page content from a v1 manual
     * 
     * @param  string $file 
     * @return array
     */
    protected function getV1PageContent($file)
    {
        $pageContent            = array();
        $content                = file_get_contents($file);
        $hr                     = strpos($content, '<hr />');
        $pageContent['body']    = '';
        $pageContent['sidebar'] = '';
        $pageContent['title']   = '';
        if (false !== $hr) {
            $pageContent['body'] = '<div id="manual-container" class="tundra">' . substr($content, $hr+6) . '</div>';
        }
        if (preg_match('{<ul[^>]*>(.*?)</ul>}s', $content, $matches)) {
            $pageContent['sidebar'] = $matches[0];
        }
        if (preg_match('{<h1[^>]*>([^<]*)</h1>}s', $content, $matches)) {
            $pageContent['title'] = $matches[0];
        }

        return $pageContent;
    }

    /**
     * get page content from a v2 manual
     * 
     * @param  string $file 
     * @return array
     */
    protected function getV2PageContent($file)
    {
        $pageContent = array();
        $doc         = new DomQuery(file_get_contents($file));

        // body
        $elem = $doc->queryXpath('//div[@class="body"]')->current();
        $pageContent['body'] = $elem->ownerDocument->saveXML($elem);
        $pageContent['body'] = preg_replace('/(\.\.\/)*(_static|_images)/i','/images/manual', $pageContent['body']);
        $pageContent['body'] = preg_replace('/width: [6-9][0-9]{2}/i','width: 650', $pageContent['body']);

        // navigation
        $elem = $doc->queryXpath('//div[@class="related"]/ul')->current();
        $pageContent['body'] .= '<div class="related">' . $elem->ownerDocument->saveXML($elem) . '</div>';

        // sidebar
        $elem    = $doc->queryXpath('//div[@class="sphinxsidebarwrapper"]')->current();
        $pageContent['sidebar'] = $elem->ownerDocument->saveXML($elem);
        $pageContent['sidebar'] = preg_replace('/(\.\.\/)*(_static|_images)/i','/images/manual', $pageContent['sidebar']);

        // title
        $elem = $doc->queryXpath('//title')->current();
        $pageContent['title'] = $elem->nodeValue;

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
