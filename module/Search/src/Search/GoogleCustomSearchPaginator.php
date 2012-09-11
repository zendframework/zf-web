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

        $this->count = $searchResults->queries->nextPage->totalResults;
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
