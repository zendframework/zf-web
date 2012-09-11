<?php

namespace Search;

use Zend\Http\Client as HttpClient;
use Zend\Paginator\Paginator;

class GoogleCustomSearch
{
    const URI = 'https://www.googleapis.com/customsearch/v1?key=%s&cx=%s%s';

    protected $defaultQueryOptions = array(
        'prettyPrint' => 'true',
        'fields'      => 'queries(nextPage,previousPage),items(htmlTitle,link,htmlSnippet)'
    );
    protected $httpClient;
    protected $itemsPerPage = 10;
    protected $uri;

    public function __construct(HttpClient $client, $apiKey, $customSearchIdentifier, array $queryOptions = array())
    {
        $this->httpClient = $client;
        $queryOptions = array_merge($this->defaultQueryOptions, $queryOptions);
        $options      = '';
        foreach ($queryOptions as $key => $value) {
            $options .= sprintf('&%s=%s', urlencode($key), urlencode($value));
        }
        $this->uri = sprintf(self::URI, $apiKey, $customSearchIdentifier, $options);
    }

    public function setItemsPerPage($count)
    {
        $this->itemsPerPage = (int) $count;
        return $this;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * Search
     * 
     * @param  string $query 
     * @param  int $page 
     * @return GoogleCustomSearchPaginator
     */
    public function search($query, $page = 1)
    {
        $startIndex = $this->getStartIndexFromPage($page);
        $uri        = $this->uri . sprintf('&q=%s&start=%s', urlencode($query), $startIndex);
        $this->httpClient->setUri($uri);

        $response   = $this->httpClient->send();
        $json       = $response->getBody();
        $results    = json_decode($json);

        $paginator  = new Paginator(new GoogleCustomSearchPaginator($results));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->getItemsPerPage());
        return $paginator;
    }

    protected function getStartIndexFromPage($page)
    {
        $startIndex = 1 + (($page - 1) * $this->itemsPerPage);
        return $startIndex;
    }
}
