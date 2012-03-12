<?php
require_once dirname(__FILE__) . '/SearchAbstract.php';

class Zend_View_Helper_SearchJiraComments extends Zend_View_Helper_SearchAbstract
{
    /**
     * Issues searcher object
     *
     * @var SearcherModel
     */
    protected $_issuesSearcher;

    /**
     * Render search results for JIRA comments
     *
     * @param  string $title
     * @param  mixed $hits
     * @return string
     */
    public function searchJiraComments($title, $hits, SearcherModel $searcher, SearcherModel $issueSearcher)
    {
        $this->_issuesSearcher = $issueSearcher;

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
        if (0 == count($hits) || empty($hits)) {
            return "<p>No results found.</p>\n";
        }

        $total = count($hits);
        $page  = $this->getCurrentPage($total);
        $hits  = $this->getHitsSubset($hits, $page);

        $return = '<ul class="ul">' . "\n";
        foreach ($hits as $hit) {
            $doc = $searcher->getDocument($hit);

            $escapedIssueId = '\\' . implode('\\', str_split($doc['issue_id']));
            $issues = $this->_issuesSearcher->search('issue_id:' . $escapedIssueId);

            $issue    = current($issues);
            $issueDoc = $this->_issuesSearcher->getDocument($issue);
            $return .= '<li><a href="/issues/browse/' . $issueDoc['key'] . '#action_' . $doc['id'] . '">'
                    .  $issueDoc['key'] . ': '
                    .  "</a>\n"
                    .  $this->view->escape($this->summary($doc['body']))
                    .  "</li>\n";
        }
        $return .= '</ul>';

        $return .= $this->renderPager($total, array(
            'type'     => 'jira_comments',
            'query'    => $this->view->form->getValue('query'),
        ));

        return $return;
    }

    /**
     * Produce a summary of a comment
     *
     * @param  string $text
     * @param  int $limit
     * @return string
     */
    function summary($text, $limit = 30)
    {
        if (strlen($text) > $limit) {
            $words = str_word_count($text, 2);
            $pos   = array_keys($words);
            $last  = count($pos) - 1;
            if ($limit > $last) {
                $limit = $last;
            }
            $text  = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }
}
