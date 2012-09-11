<?php

namespace Search;

use Zend\Http\Client as HttpClient;
use Zend\Paginator\Paginator;

class GoogleCustomSearch
{
    const URI = 'https://www.googleapis.com/customsearch/v1?key=%s&cx=%s%s';

    protected $defaultQueryOptions = array(
        'prettyPrint' => 'true',
        'fields'      => 'queries(nextPage,previousPage),items(htmlTitle,link,htmlFormattedUrl,htmlSnippet)'
    );
    protected $httpClient;
    protected $itemsPerPage = 10;
    protected $lastResult;
    protected $lastUri;
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
        if ($page > 10) {
            $page = 10;
        }

        $startIndex = $this->getStartIndexFromPage($page);
        $uri        = $this->uri . sprintf('&q=%s&start=%s', urlencode($query), $startIndex);
        $this->httpClient->setUri($uri);

        $response         = $this->httpClient->send();
        $json             = $response->getBody();
        $this->lastResult = $json;
        $this->lastUri    = $uri;
        $results          = json_decode($json);
        if (null === $results) {
            return false;
        }

        $paginator  = new Paginator(new GoogleCustomSearchPaginator($results));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($this->getItemsPerPage());
        return $paginator;
    }

    public function getLastResult()
    {
        return $this->lastResult;
    }

    public function getLastUri()
    {
        return $this->lastUri;
    }

    protected function getStartIndexFromPage($page)
    {
        $startIndex = 1 + (($page - 1) * $this->itemsPerPage);
        return $startIndex;
    }
}
