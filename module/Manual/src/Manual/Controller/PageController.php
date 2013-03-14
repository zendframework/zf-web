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
        
        $docFile = $this->params[$version][$lang] . $page;

        if (!file_exists($docFile)) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }

        $content = $this->getPageContent($docFile, $version);
        if (false === $content) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }

        $model->setVariable('name', $name);
        $model->setVariable('lang', $lang);
        $model->setVariable('title', $content['title']);
        $model->setVariable('body', $content['body']);
        $model->setVariable('sidebar', $content['sidebar']);
        $model->setVariable('version', $version);
        $model->setVariable('versions', array_keys($this->params));
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
        $model->setVariable(
            'versions',
            array(
                 1 => array(
                     '1.12.3',
                     '1.11.13',
                     '1.10.9',
                     '1.9.8',
                     '1.8.5',
                     '1.7.9',
                     '1.6.2',
                     '1.5.3',
                     '1.0.3',
                 ),
                 2 => array(
                     '2.1.4',
                     '2.0.7',
                 ),
            )
        );
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
        $doc                    = new DomQuery(file_get_contents($file));
        $pageContent['body']    = '';
        $pageContent['sidebar'] = '';
        $pageContent['title']   = '';

        // Body (standard)
        $content = $doc->queryXpath('//div[@class="section"]');
        if (count($content)) {
            $xpath = new \DOMXpath($content->getDocument());

            // Replace headlines (h1 => h4)
            $nodelist = $xpath->query(
                '//div/div[@class = "section"]/div[@class = "section"]/div[@class = "section"]/div/h1[@class = "title"]'
            );

            foreach ($nodelist as $node) {
                $newElement = $content->getDocument()->createElement(
                    'h4', $node->nodeValue
                );
                $node->parentNode->replaceChild($newElement, $node);
            }

            // Replace headlines (h1 => h3)
            $nodelist = $xpath->query(
                '//div/div[@class = "section"]/div[@class = "section"]/div/h1[@class = "title"]'
            );

            foreach ($nodelist as $node) {
                $newElement = $content->getDocument()->createElement(
                    'h3', $node->nodeValue
                );
                $node->parentNode->replaceChild($newElement, $node);
            }

            // Replace headlines (h1 => h2)
            $nodelist = $xpath->query(
                '//div/div[@class = "section"]/div/h1[@class = "title"]'
            );

            foreach ($nodelist as $node) {
                $newElement = $content->getDocument()->createElement(
                    'h2', $node->nodeValue
                );
                $node->parentNode->replaceChild($newElement, $node);
            }

            $pageContent['body'] = $content->current()->ownerDocument->saveXML(
                $content->current()
            );
        }

        // Body (table of contents)
        $headline    = $doc->queryXpath('//div[@id="the.index"]/strong/text()')->current();
        $contentList = $doc->queryXpath('//div[@id="the.index"]/ul')->current();
        if (count($headline) && count($contentList)) {
            $pageContent['body'] = '<h1>'
                                 . $headline->ownerDocument->saveXML($headline)
                                 . '</h1>'
                                 . $contentList->ownerDocument->saveXML($contentList);
        }

        // Body (part)
        $part = $doc->queryXpath('//div[@class="part"]')->current();
        if (count($part)) {
            $h1          = $doc->queryXpath('//div[@class="part"]/h1')->current();
            $h2          = $doc->queryXpath('//div[@class="part"]/strong/text()')->current();
            $contentList = $doc->queryXpath('//div[@class="part"]/ul')->current();

            if (count($h1) && count($h2) && count($contentList)) {
                $pageContent['body'] = $h1->ownerDocument->saveXML($h1)
                                     . '<h2>'
                                     . $h2->ownerDocument->saveXML($h2)
                                     . '</h2>'
                                     . $contentList->ownerDocument->saveXML($contentList);
            }
        }
        // Body (chapter and appendix)
        $body = $doc->queryXpath('//div[@class="chapter" or @class="appendix"]')->current();
        if (count($body)) {
            $h1          = $doc->queryXpath('//div[@class="chapter" or @class="appendix"]//h1')->current();
            $h2          = $doc->queryXpath('//div[@class="chapter" or @class="appendix"]//strong/text()')->current();
            $contentList = $doc->queryXpath('//div[@class="chapter" or @class="appendix"]//ul')->current();

            if (count($h1) && count($h2) && count($contentList)) {
                $pageContent['body'] = $h1->ownerDocument->saveXML($h1)
                                     . '<h2>'
                                     . $h2->ownerDocument->saveXML($h2)
                                     . '</h2>'
                                     . $contentList->ownerDocument->saveXML($contentList);
            } else  {
                $pageContent['body'] = $body->ownerDocument->saveXML($body);
            }
        }

        // Sidebar

        // Headline (table of contents)
        $headline = $doc->queryXpath('//ul[@class="toc"]/li[@class = "header home"]/a');
        if (count($headline)) {
            $pageContent['sidebar'] = sprintf(
                '<h1><a href="%s">Table Of Contents</a></h1>',
                $headline->current()->getAttribute('href')
            );
        }

        $pageContent['sidebar'] .= "<ul>\n";

        // First list item (section)
        $firstItem = $doc->queryXpath('//ul[@class="toc"]/li[@class = "header up"][last()]')->current();
        if (count($firstItem)) {
            $pageContent['sidebar'] .= $firstItem->ownerDocument->saveXML($firstItem);
        }

        // Content list items
        $elements = $doc->queryXpath('//body/table/tr/td/div/div[@class="section" and @name and @id]');
        if (count($elements)) {
            // Active page
            $active = $doc->queryXpath('//ul[@class="toc"]/li[@class = "active"]/a')->current();

            $xpath = new \DOMXpath($elements->getDocument());

            // Content list
            $pageContent['sidebar'] .= "<ul>\n";
            foreach ($elements as $element) {
                $pageContent['sidebar'] .= sprintf(
                    '<li><a href="%s">%s</a>',
                    $active->getAttribute('href') . '#' . $element->getAttribute('id'),
                    $element->childNodes->item(0)->nodeValue
                );

                // Sub elements
                $nodelist = $xpath->query(
                    'div[@class="section" and @name and @id]',
                    $element
                );

                if ($nodelist->length) {
                    $pageContent['sidebar'] .= '<ul>';

                    foreach ($nodelist as $node) {
                        $pageContent['sidebar'] .= sprintf(
                            '<li><a href="%s">%s</a></li>',
                            $active->getAttribute('href') . '#' . $node->getAttribute('id'),
                            $node->childNodes->item(0)->nodeValue
                        );
                    }

                    $pageContent['sidebar'] .= '</ul>';
                }

                $pageContent['sidebar'] .= '</li>';
            }
            $pageContent['sidebar'] .= "</ul>\n";
        }
        $pageContent['sidebar'] .= "</ul>\n";

        // Previous topic
        $prevTopic  = $doc->queryXpath('//div[@class="next"]/parent::td/preceding-sibling::td/a')->current();

        if (count($prevTopic)) {
            $pageContent['sidebar'] .= '<h1>Previous topic</h1>';
            $pageContent['sidebar'] .= sprintf(
                '<p class="topless"><a href="%s" title="previous chapter">%s</a></p>',
                $prevTopic->getAttribute('href'),
                $prevTopic->nodeValue
            );
        }

        // Next topic
        $nextTopic  = $doc->queryXpath('//div[@class="next"]/a')->current();

        if (count($nextTopic)) {
            $pageContent['sidebar'] .= '<h1>Next topic</h1>';
            $pageContent['sidebar'] .= sprintf(
                '<p class="topless"><a href="%s" title="next chapter">%s</a></p>',
                $nextTopic->getAttribute('href'),
                $nextTopic->nodeValue
            );
        }

        // Head title
        $elem = $doc->queryXpath('//ul[@class="toc"]/li[@class = "active"]/a/text()')->current();
        if (count($elem)) {
            $pageContent['title'] = $elem->ownerDocument->saveXML($elem);
        }

        $elem = $doc->queryXpath('//ul[@class="toc"]/li[@class="header up"][last()]/a/text()')->current();
        if (count($elem)) {
            $pageContent['title'] .= ' - ' . $elem->ownerDocument->saveXML($elem);
        }

        // Navigation
        $navigation = '';

        // Previous link
        $prevLink  = $doc->queryXpath('//div[@class="next"]/parent::td/preceding-sibling::td/a')->current();
        if (count($prevLink)) {
            $navigation .= sprintf(
                '<li class="prev"><a href="%s">%s</a>',
                $prevLink->getAttribute('href'),
                $prevLink->nodeValue
            );
        }

        // Next link
        $nextLink  = $doc->queryXpath('//div[@class="next"]/a')->current();
        if (count($nextLink)) {
            $navigation .= sprintf(
                '<li class="next"><a href="%s">%s</a>',
                $nextLink->getAttribute('href'),
                $nextLink->nodeValue
            );
        }

        if (!empty($navigation)) {
            $navigation = sprintf(
                '<div class="related hide-on-print"><ul>%s</ul></div>',
                $navigation
            );
            $pageContent['body'] = $navigation . $pageContent['body'] . $navigation;
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
        $pageContent['body'] = preg_replace(
            '/(\.\.\/)*(_static|_images)/i',
            '/images/manual',
            $pageContent['body']
        );
        $pageContent['body'] = preg_replace(
            '/width: [6-9][0-9]{2}/i',
            'width: 650',
            $pageContent['body']
        );

        // Navigation
        $navigation = '';

        // Previous link
        $prevLink  = $doc->queryXpath('//link[@rel="prev"]')->current();
        if (count($prevLink)) {
            $navigation .= sprintf(
                '<li class="prev"><a href="%s">%s</a>',
                $prevLink->getAttribute('href'),
                $prevLink->getAttribute('title')
            );
        }

        // Next link
        $nextLink  = $doc->queryXpath('//link[@rel="next"]')->current();
        if (count($nextLink)) {
            $navigation .= sprintf(
                '<li class="next"><a href="%s">%s</a>',
                $nextLink->getAttribute('href'),
                $nextLink->getAttribute('title')
            );
        }

        if (!empty($navigation)) {
            $navigation = sprintf(
                '<div class="related hide-on-print"><ul>%s</ul></div>',
                $navigation
            );
            $pageContent['body'] = $navigation . $pageContent['body'] . $navigation;
        }

        // Sidebar
        $elem    = $doc->queryXpath('//div[@class="sphinxsidebarwrapper"]')->current();
        $pageContent['sidebar'] = $elem->ownerDocument->saveXML($elem);

        // Remove logo
        $pageContent['sidebar'] = substr(
            $pageContent['sidebar'],
            strpos($pageContent['sidebar'], '</p>')
        );

        $pageContent['sidebar'] = substr($pageContent['sidebar'], 0, -6);

        // Replace empty links
        $pageContent['sidebar'] = str_replace(
            '<h3><a href="#">Table Of Contents</a></h3>',
            '<h1>Table Of Contents</h1>',
            $pageContent['sidebar']
        );

        // Change headlines
        $pageContent['sidebar'] = str_replace('<h4>','<h1>', $pageContent['sidebar']);
        $pageContent['sidebar'] = str_replace('</h4>','</h1>', $pageContent['sidebar']);
        
        $pageContent['sidebar'] = str_replace('<h3>','<h1>', $pageContent['sidebar']);
        $pageContent['sidebar'] = str_replace('</h3>','</h1>', $pageContent['sidebar']);

        // Add CSS class for notes
        $pageContent['sidebar'] = str_replace(
            '<p style="font-size: 12px">',
            '<p class="note">',
            $pageContent['sidebar']
        );

        // Title
        $elem = $doc->queryXpath('//title')->current();
        $pageContent['title'] = $elem->nodeValue;

        return $pageContent;
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
