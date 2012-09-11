<?php

namespace Search;

use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapter;

class GoogleCustomSearchPaginator implements PaginatorAdapter
{
    protected $count;
    protected $items;

    public function __construct($searchResults)
    {
        if (!is_object($searchResults)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid search results returned to %s; expects an object',
                __METHOD__
            ));
        }

        if (!isset($searchResults->queries) || !isset($searchResults->items) || empty($searchResults->items)) {
            $this->count = 0;
            $this->items = array();
            return;
        }

        $pages       = isset($searchResults->queries->nextPage) ? $searchResults->queries->nextPage : $searchResults->queries->previousPage;
        $page        = array_shift($pages);
        $total       = ($page->totalResults > 100) ? 100 : $page->totalResults;
        $this->count = $total;
        $this->items = $searchResults->items;
    }

    public function count()
    {
        return $this->count;
    }

    public function getItems($offset, $itemCountPerPage)
    {
        return $this->items;
    }
}
