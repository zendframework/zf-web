<?php

namespace Manual\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Resolver\ResolverInterface;
use Zend\View\Model\ViewModel;
use Zend\Dom\Query as DomQuery;

class PageController extends AbstractActionController
{
    /**
     * @var array
     */
    protected $apiDocVersions;

    /**
     * @var string
     */
    protected $latestVersion;

    /**
     * @var string
     */
    protected $latestZf1Version;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var ResolverInterface
     */
    protected $resolver;

    /**
     * @param string $latestVersion
     */
    public function __construct($latestVersion, $latestZf1Version)
    {
        $this->latestVersion    = $latestVersion;
        $this->latestZf1Version = $latestZf1Version;
    }

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
     * Set API documentation version map
     *
     * @param  array $versions
     * @return self
     */
    public function setApiDocVersions(array $versions)
    {
        $this->apiDocVersions = $versions;
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

        // Check URL params
        if (!$page || false === $version || !$lang) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }

        // Redirect latest version to "current"
        if ($version === $this->latestVersion) {
            return $this->redirect()->toRoute(
                'manual',
                [ 'version' => 'current', 'lang' => $lang, 'page' => $page ]
            );
        }

        $name = $page;

        // Create doc filename
        if ('current' === $version) {
            $version = $this->latestVersion;
        }
        $docFile = $this->params[$version][$lang] . $page;

        // Check file
        if (!file_exists($docFile)) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }

        // Get page content
        $content = $this->getPageContent($docFile, $version);

        // Check content
        if (false === $content) {
            return $this->return404Page($model, $this->getEvent()->getResponse());
        }

        // Get content list for select element
        $contentList = $this->getContentList(
            $this->params[$version][$lang],
            $version
        );

        // Get current page link for select element with content list
        $getLinks = function ($pages) use (&$getLinks) {
            $links = array();
            foreach ($pages as $key => $value) {
                $links[] = $key;
                if (is_array($value)) {
                    $links = array_merge($links, $getLinks($value));
                }
            }
            return $links;
        };

        if (in_array($page, $getLinks($contentList))) {
            $currentPage = $page;
        } else {
            $currentPage = $this->getSelectedPage(
                $page,
                $this->params[$version][$lang],
                $version
            );
        }

        // Get current page title
        $currentPageTitle = $this->getSelectedPage(
            $page,
            $this->params[$version][$lang],
            $version,
            false
        );

        // Set variables on view model
        $model->setVariable('name', $name);
        $model->setVariable('lang', $lang);
        $model->setVariable('page', $page);
        $model->setVariable('title', $content['title']);
        $model->setVariable('body', $content['body']);
        $model->setVariable('sidebar', $content['sidebar']);
        $model->setVariable('version', $version);
        $model->setVariable('latestVersion', $this->latestVersion);
        $model->setVariable('latestZf1Version', $this->latestZf1Version);
        $model->setVariable('contentList', $contentList);
        $model->setVariable('currentPage', $currentPage);
        $model->setVariable('currentPageTitle', $currentPageTitle);
        $model->setTemplate('manual/page-controller/manual');

        // Sort version numbers
        $versions = array_keys($this->params);
        rsort($versions, SORT_NATURAL);
        $model->setVariable('versions', $versions);

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
        $model->setVariable('versions', $this->apiDocVersions);
        return $model;
    }

    /**
     * Handles switching from one version to another in the docs, will attempt
     * to keep the user on the same page where possible
     *
     * @return Zend\Http\PhpEnvironment\Response
     */
    public function versionSwitchAction()
    {
        $newVersion = $this->params()->fromQuery('new', false);
        $lang       = $this->params()->fromQuery('lang', false);
        $page       = $this->params()->fromQuery('page', false);

        if ($newVersion === 'current') {
            $newVersion = $this->latestVersion;
        }

        if (false === $newVersion
            || false === $page
            || false === $lang
            || ! isset($this->params[$newVersion])
        ) {
            return $this->return404Page(new ViewModel(), $this->getEvent()->getResponse());
        }

        $docFile = $this->params[$newVersion][$lang] . $page;

        if (file_exists($docFile)) {
            // there's an equivalent page in $newVersion, so redirect them to it
            return $this->redirect()->toRoute('manual', array(
                'version' => ($newVersion === $this->latestVersion) ? 'current' : $newVersion,
                'lang'    => $lang,
                'page'    => $page,
            ));
        }

        // no equivalent page in $newVersion to just redirect to the index
        return $this->redirect()->toRoute('manual', array(
            'version' => ($newVersion === $this->latestVersion) ? 'current' : $newVersion,
            'lang'    => $lang,
        ));
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
        if ('1.1' === substr($version, 0, 3)) {
            return $this->getV1PageContent($file);
        }
        if ('1.' === substr($version, 0, 2)) {
            return $this->getOldV1PageContent($file);
        }
        return $this->getV2PageContent($file);
    }

    /**
     * Get page content for version < 1.11
     *
     * @param  string $file
     * @return array
     */
    protected function getOldV1PageContent($file)
    {
        $pageContent = array();

        $doc                    = new DomQuery(file_get_contents($file));
        $pageContent['body']    = '';
        $pageContent['sidebar'] = '';
        $pageContent['title']   = '';

        $sidebar = true;
        // Body
        $content = $doc->queryXpath('//div[@class="book"]');
        if (count($content)) {
            $pageContent['body'] = $content->current()->ownerDocument->saveXML(
                $content->current()
            );
            $sidebar = false;
        }

        $content = $doc->queryXpath('//div[@class="chapter"]/div[@class="sect1"]');
        if (count($content)) {
            $xpath = new \DOMXpath($content->getDocument());

            // Replace A link tag without text with a space
            $nodelist = $xpath->query(
                '//a[@name]'
            );

            foreach ($nodelist as $node) {
                $newElement = $content->getDocument()->createElement(
                    'a',
                    ' '
                );
                $newElement->setAttribute('name', $node->getAttribute('name'));
                $node->parentNode->replaceChild($newElement, $node);
            }

            $pageContent['body'] = $content->current()->ownerDocument->saveXML(
                $content->current()
            );
        }

        if (empty($pageContent['body'])) {
            $content = $doc->queryXpath('//div[@class="sect1"]');
            if (count($content)) {
                $xpath = new \DOMXpath($content->getDocument());
                // Replace A link tag without text with a space
                $nodelist = $xpath->query(
                    '//a[@name]'
                );

                foreach ($nodelist as $node) {
                    $newElement = $content->getDocument()->createElement(
                        'a',
                        ' '
                    );
                    $node->parentNode->replaceChild($newElement, $node);
                }
                $pageContent['body']= $content->current()->ownerDocument->saveXML(
                    $content->current()
                );
            }
        }

        // Sidebar
        $headline= $doc->queryXpath('//div[@class="toc"]');
        if ($sidebar && count($headline)) {
            $pageContent['sidebar'] = $headline->current()->ownerDocument->saveXML(
                $headline->current()
            );
        }

        // Previous topic
        $prevTopic  = $doc->queryXpath('//div[@class="navheader"]//a[@accesskey="p"]')->current();

        if (count($prevTopic)) {
            $pageContent['sidebar'] .= '<h1>Previous topic</h1>';
            $pageContent['sidebar'] .= sprintf(
                '<p class="topless"><a href="%s" title="previous chapter">%s</a></p>',
                $prevTopic->getAttribute('href'),
                $prevTopic->nodeValue
            );
        }

        // Next topic
        $nextTopic  = $doc->queryXpath('//div[@class="navheader"]//a[@accesskey="n"]')->current();

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
        $prevLink  = $doc->queryXpath('//div[@class="navfooter"]//a[@accesskey="p"]')->current();
        if (count($prevLink)) {
            $navigation .= sprintf(
                '<li class="prev"><a href="%s">%s</a>',
                $prevLink->getAttribute('href'),
                $prevLink->nodeValue
            );
        }

        // Next link
        $nextLink  = $doc->queryXpath('//div[@class="navfooter"]//a[@accesskey="n"]')->current();
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
     * Get page content from a v1.11+
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
                    'h4',
                    $node->nodeValue
                );
                $node->parentNode->replaceChild($newElement, $node);
            }

            // Replace headlines (h1 => h3)
            $nodelist = $xpath->query(
                '//div/div[@class = "section"]/div[@class = "section"]/div/h1[@class = "title"]'
            );

            foreach ($nodelist as $node) {
                $newElement = $content->getDocument()->createElement(
                    'h3',
                    $node->nodeValue
                );
                $node->parentNode->replaceChild($newElement, $node);
            }

            // Replace headlines (h1 => h2)
            $nodelist = $xpath->query(
                '//div/div[@class = "section"]/div/h1[@class = "title"]'
            );

            foreach ($nodelist as $node) {
                $newElement = $content->getDocument()->createElement(
                    'h2',
                    $node->nodeValue
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
            } else {
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
        $elements = $doc->queryXpath('//div[@class="sphinxsidebarwrapper"]/*');
        $pageContent['sidebar'] = '';

        /** @var \DOMNode $node */
        foreach ($elements as $node) {
            // Get TOC headline
            if ($node->nodeValue == 'Table Of Contents') {
                // Add headline to sidebar
                $pageContent['sidebar'] .= '<section id="toc">';
                $pageContent['sidebar'] .= $node->ownerDocument->saveXML($node);

                // Get TOC list
                if ('ul' == $node->nextSibling->nextSibling->nodeName) {
                    // Add list to sidebar
                    $pageContent['sidebar'] .= $node->ownerDocument->saveXML(
                        $node->nextSibling->nextSibling
                    );
                }
                // Add closing tag to sidebar
                $pageContent['sidebar'] .= '</section>';
            }

            // Get "This Page" headline
            if ($node->nodeValue == 'This Page') {
                // Add headline to sidebar
                $pageContent['sidebar'] .= '<section id="this-page-menu">';
                $pageContent['sidebar'] .= $node->ownerDocument->saveXML($node);

                // Get "This Page" menu
                $menu = $node->nextSibling->nextSibling;
                if ($menu && $menu->hasAttribute('class')
                    && 'this-page-menu' == $menu->getAttribute('class')
                ) {
                    // Add menu to sidebar
                    $pageContent['sidebar'] .= $node->ownerDocument->saveXML(
                        $menu
                    );
                }

                // Get note
                if ($menu
                    && false !== strpos(
                        trim($menu->nextSibling->nodeValue),
                        'Note:'
                    )
                ) {
                    // Get content with its descendants ("<p><a></a></p>")
                    $innerHTML = '';
                    foreach ($menu->nextSibling->childNodes as $child) {
                        $innerHTML .= $node->ownerDocument->saveHTML($child);
                    }

                    // Add note to sidebar
                    $pageContent['sidebar'] .= sprintf(
                        '<p class="note">%s</p>',
                        trim($innerHTML)
                    );
                }

                // Add closing tag to sidebar
                $pageContent['sidebar'] .= '</section>';
            }
        }

        // Replace empty links
        $pageContent['sidebar'] = str_replace(
            '<h3><a href="#">Table Of Contents</a></h3>',
            '<h1>Table Of Contents</h1>',
            $pageContent['sidebar']
        );

        // Change headlines
        $pageContent['sidebar'] = str_replace('<h4>', '<h1>', $pageContent['sidebar']);
        $pageContent['sidebar'] = str_replace('</h4>', '</h1>', $pageContent['sidebar']);

        $pageContent['sidebar'] = str_replace('<h3>', '<h1>', $pageContent['sidebar']);
        $pageContent['sidebar'] = str_replace('</h3>', '</h1>', $pageContent['sidebar']);

        // Title
        $elem = $doc->queryXpath('//title')->current();
        $pageContent['title'] = $elem->nodeValue;

        return $pageContent;
    }

    /**
     * @param string $path
     * @param string $version
     *
     * @return array
     */
    protected function getContentList($path, $version)
    {
        if ('1.1' === substr($version, 0, 3)) {
            return array();
        }
        if ('1.' === substr($version, 0, 2)) {
            return array();
        }
        return $this->getV2ContentList($path);
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getV2ContentList($path)
    {
        // Create list
        $list = array(
            'index.html' => 'Programmer’s Reference Guide',
        );
        $doc  = new DomQuery(file_get_contents($path . 'index.html'));

        // Anonymous function for string replacement
        $replace = function ($string) {
            return str_replace('¶', '', $string);
        };

        // Fetch sections
        $sections = $doc->queryXpath(
            '//div[@class="body"]/div[@class="section"]/div[@class="section"]'
        );

        // Check sections
        if (count($sections)) {
            $xpath = new \DOMXpath($sections->getDocument());

            foreach ($sections as $section) {
                // Get label for optgroup
                $group = $replace($section->childNodes->item(1)->nodeValue);
                if (empty($group)) {
                    $group = $replace($section->childNodes->item(2)->nodeValue);
                }

                // Create optgroup
                $list[$group] = array();

                // Fetch subsections
                $subSections = $xpath->query(
                    'div[@class="section"]',
                    $section
                );

                // Check subsections
                if ($subSections->length) {
                    foreach ($subSections as $subSection) {
                        // Fetch component headlines
                        $headlines = $xpath->query('h3', $subSection);

                        // Check component headlines
                        if ($headlines->length) {
                            // Fetch first link
                            $links = $xpath->query(
                                'blockquote/div/ul/li[1]/a',
                                $subSection
                            );

                            if (! $links->length) {
                                continue;
                            }

                            // Add to list
                            foreach ($headlines as $headline) {
                                $list[$group][$links->item(0)->getAttribute('href')] =
                                    $replace($headline->nodeValue);
                            }
                        }
                    }
                } else {
                    // Fetch page headlines
                    $headlines = $xpath->query(
                        'blockquote/div/ul/li/a',
                        $section
                    );

                    // Check page headlines
                    if ($headlines->length) {
                        // Add to list
                        foreach ($headlines as $headline) {
                            $list[$group][$headline->getAttribute('href')] =
                                $replace($headline->nodeValue);
                        }
                    }
                }
            }
        }

        return $list;
    }

    /**
     * @param string $currentPage
     * @param string $path
     * @param string $version
     * @param bool   $getHref
     *
     * @return string|null
     */
    protected function getSelectedPage($currentPage, $path, $version, $getHref = true)
    {
        if ('1.1' === substr($version, 0, 3)) {
            return null;
        }
        if ('1.' === substr($version, 0, 2)) {
            return null;
        }
        return $this->getV2SelectedPage($currentPage, $path, $getHref);
    }

    /**
     * @param string $currentPage
     * @param string $path
     * @param bool   $getHref
     *
     * @return string|null
     */
    protected function getV2SelectedPage($currentPage, $path, $getHref = true)
    {
        $doc = new DomQuery(file_get_contents($path . 'index.html'));

        if (true === $getHref) {
            // Fetch first link
            $links = $doc->queryXpath(
                sprintf(
                    '//a[@href = "%s"]/parent::li/parent::ul/li[1]/a',
                    $currentPage
                )
            );

            // Check link
            if ($links->count() && $links->current()->hasAttribute('href')) {
                return $links->current()->getAttribute('href');
            }
        } else {
            // Fetch headline (component name)
            $headline = $doc->queryXpath(
                sprintf(
                    '//a[@href = "%s"]/parent::li/parent::ul/parent::div/parent::blockquote/parent::div/h3',
                    $currentPage
                )
            );

            // Check headline
            if ($headline->count()) {
                return str_replace('¶', '', $headline->current()->nodeValue);
            } else {
                // Fetch headline
                $headline = $doc->queryXpath(
                    sprintf(
                        '//a[@href = "%s"]/parent::li/parent::ul/parent::div/parent::blockquote/parent::div/h2',
                        $currentPage
                    )
                );

                // Check headline
                if ($headline->count()) {
                    return str_replace('¶', '', $headline->current()->nodeValue);
                }
            }
        }

        return null;
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
