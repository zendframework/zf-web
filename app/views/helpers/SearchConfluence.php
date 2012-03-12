<?php
require_once dirname(__FILE__) . '/SearchAbstract.php';

class Zend_View_Helper_SearchConfluence extends Zend_View_Helper_SearchAbstract
{
    public $allowedDocTypes = array('page', 'comment');

    /**
     * Render search results for wiki
     *
     * @param  string $title
     * @param  mixed $hits
     * @return string
     */
    public function searchConfluence($title, $hits, SearcherModel $searcher)
    {
        return $this->renderHeader($title)
             . $this->renderResults($hits, $searcher)
             . $this->renderFooter();
    }

    /**
     * Render results
     *
     * @param mixed $hits
     * @param  SearcherModel $searcher
     * @return void
     */
    public function renderResults($hits, SearcherModel $searcher)
    {
        if (0 == count($hits)) {
            return "<p>No results found.</p>\n";
        }

        $total = count($hits);
        $page  = $this->getCurrentPage($total);
        $hits  = $this->getHitsSubset($hits, $page);

        $return = '<ul class="ul">' . "\n";
        foreach ($hits as $hit) {
            $doc = $searcher->getDocument($hit);

            if (!in_array($doc['type'], $this->allowedDocTypes)) {
                continue;
            }

            $return .= '<li><a href="/wiki' . $doc['urlPath'] . '">'
                    .  ucfirst($doc['type']) . ': '
                    .  $this->view->escape($doc['title'])
                    .  "</a></li>\n";
        }
        $return .= "</ul>\n";

        $return .= $this->renderPager($total, array(
            'type'     => 'confluence',
            'query'    => $this->view->form->getValue('query'),
        ));

        return $return;
    }
}
