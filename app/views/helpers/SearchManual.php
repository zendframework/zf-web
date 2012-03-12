<?php
require_once dirname(__FILE__) . '/SearchAbstract.php';

class Zend_View_Helper_SearchManual extends Zend_View_Helper_SearchAbstract
{
    /**
     * Render search results for manual
     *
     * @param  string $title
     * @param  mixed $hits
     * @return string
     */
    public function searchManual($title, $hits, SearcherModel $searcher)
    {
        $html = '';
        if (!empty($title)) {
            $html .= $this->renderHeader($title);
        }

        $html .= $this->renderResults($hits, $searcher);

        if (!empty($title)) {
            $html .= $this->renderFooter();
        }

        return $html;
    }

    /**
     * Render results
     *
     * @param  mixed $hits
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

            $return .= '<li><a href="' . $doc['url'] . '">'
                    .  $this->view->escape($doc['title'])
                    .  ' [' . $doc['lang'] . ']'
                    .  "</a></li>\n";
        }
        $return .= "</ul>\n";

        $return .= $this->renderPager($total, array(
            'type'     => 'manual',
            'query'    => $this->view->form->getValue('query'),
            'language' => $this->view->form->getValue('language'),
        ));

        return $return;
    }
}
