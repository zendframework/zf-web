<?php
/**
 * Render search results
 *
 * @version $Id$
 */
abstract class Zend_View_Helper_SearchAbstract
{
    /**
     * Base URL
     * @var string
     */
    public $baseUrl = '/search?';

    /**
     * Max number of results to show per page
     * @var int
     */
    public $maxPerPage = 15;

    /**
     * Size of pager
     * @var int
     */
    public $pagerWindow = 10;

    /**
     * @var Zend_View_Interface
     */
    public $view;

    /**
     * Set view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_SearchAbstract
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Header
     *
     * @param  string $header
     * @return string
     */
    public function renderHeader($header)
    {
        return "<h3>$header</h3>\n";
    }

    /**
     * Footer
     *
     * @return string
     */
    public function renderFooter()
    {
        return '<div class="dotted-line"></div>' . "\n";
    }

    /**
     * Render results
     *
     * @param  mixed $hits
     * @param  SearcherModel $searcher
     * @return string
     */
    abstract public function renderResults($hits, SearcherModel $searcher);

    /**
     * Get current page
     *
     * @param  int $numHits
     * @return int
     */
    public function getCurrentPage($numHits)
    {
        $page     = (int) $this->view->page;
        $numPages = $this->getNumPages($numHits);
        if ($page > $numPages) {
            return $numPages;
        }

        if ($page < 1) {
            return 1;
        }

        return $page;
    }

    /**
     * Get subset of hits to display
     *
     * @param  array $hits
     * @param  int $page
     * @return array
     */
    public function getHitsSubset(array $hits, $page)
    {
        return array_slice(
            $hits,                             // all hits
            (($page - 1) * $this->maxPerPage), // offset
            $this->maxPerPage                  // total to include
        );
    }

    /**
     * Get the number of pages for the result set
     *
     * @param  int $numHits
     * @return int
     */
    public function getNumPages($numHits)
    {
        return ceil($numHits / $this->maxPerPage);
    }

    /**
     * Render the pager
     *
     * @param  int $numHits
     * @param  array $criteria
     * @return string
     */
    public function renderPager($numHits, array $criteria)
    {
        $numPages = $this->getNumPages($numHits);
        if (2 > $numPages) {
            return '';
        }

        $curPage   = $this->getCurrentPage($numHits);
        $startPage = 1;
        $endPage   = $numPages;

        // Create base URL for search
        $queryString = array();
        foreach ($criteria as $key => $value) {
            $queryString[] = urlencode($key) . '=' . urlencode($this->view->escape($value));
        }
        $queryString[] = 'page=';
        $baseUrl = $this->baseUrl . implode('&', $queryString);

        if ($numPages > $this->pagerWindow) {
            $midWay    = $this->pagerWindow / 2;
            $startPage = ($curPage < $midWay) ? 1 : $curPage - $midWay + 1;
            $endPage   = ($curPage + $midWay > $numPages) ? $numPages : $curPage + $this->pagerWindow - 1;
            if (($startPage < $midWay + 1) && ($endPage < $numPages)) {
                $endPage = $startPage + ($this->pagerWindow - 1);
            } elseif (($endPage == $numPages) && ($endPage - $startPage < $this->pagerWindow)) {
                $startPage = $endPage - $this->pagerWindow + 1;
            }
        }

        $html = '<ul class="pager">';
        if (1 < $curPage) {
            $html .= '<li><a href="' . $baseUrl . '1" title="first page">&lt;&lt;</a></li>';
        }
        if ($curPage > 1) {
            $html .= '<li><a href="' . $baseUrl . ($curPage - 1) . '" title="previous page">prev</a></li>';
        }
        for ($i = $startPage; $i <= $endPage; ++$i) {
            $page = $i;
            if ($curPage == $page) {
                $html .= '<li class="current">' . $page . '</li>';
            } else {
                $html .= '<li><a href="' . $baseUrl . $page . '" title="page ' . $page . '">' . $page . '</a></li>';
            }
        }
        if ($curPage < $numPages) {
            $html .= '<li><a href="' . $baseUrl . ($curPage + 1) . '" title="next page">next</a></li>';
        }
        if ($numPages > $curPage) {
            $html .= '<li><a href="' . $baseUrl . $numPages . '" title="last page">&gt;&gt;</a></li>';
        }
        $html .= '</ul>';
        $html .= '<div class="clearer"></div>';
        $html .= '<div style="margin-top: 1em;">&nbsp;</div>';

        return $html;
    }
}
