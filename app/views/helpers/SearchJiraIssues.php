<?php
require_once dirname(__FILE__) . '/SearchAbstract.php';

class Zend_View_Helper_SearchJiraIssues extends Zend_View_Helper_SearchAbstract
{
    /**
     * Render search results for JIRA issues
     *
     * @param  string $title
     * @param  mixed $hits
     * @return string
     */
    public function searchJiraIssues($title, $hits, SearcherModel $searcher)
    {
        $this->_searcher = $searcher;

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

            $return .= '<li><a href="/issues/browse/' . $doc['key'] . '">'
                    .  $doc['key'] . ': '
                    .  $this->view->escape($doc['summary'])
                    .  "</a></li>\n";
        }
        $return .= "</ul>\n";

        $return .= $this->renderPager($total, array(
            'type'     => 'jira_issues',
            'query'    => $this->view->form->getValue('query'),
        ));

        return $return;
    }
}
