<?php

/**
 * Search through Zend_Search_Lucene index
 */
class SearcherModel
{
    /**
     * Index to search through
     *
     * @var Zend_Search_Lucene
     */
    protected $_index;

    /**
     * Cache object
     *
     * @var Zend_Cache
     */
    protected $_cache;

    /**
     * Index path.
     * Used to generate unique document id.
     *
     * @var string
     */
    protected $_path;

    /**
     * Index generation.
     * Is used to check if cache entry refers to the right document retrieved by ID
     *
     * @var integer
     */
    protected $_indexGeneration;

    /**
     * Searcher constructor
     *
     * @param string $path
     */
    public function __construct($path, Zend_Cache_Core $cache)
    {
        $this->_path = $path;

        // Open the index.
        // We can do this here since Zend_Search_Lucene uses lazy loading for index resources
        $this->_index = Zend_Search_Lucene::open($path);

        /**
         * @todo $this->_index->getActualGeneration() has to be replaced with retrieving
         * current generation when it is implemented. See [ZF-9526].
         */
        $this->_indexGeneration = $this->_index->getActualGeneration($this->_index->getDirectory());

        $this->_cache = $cache;
    }

    /**
     * Search through the index.
     * Returns array of document IDs instead of array of QueryHit objects
     *
     * That can be much more effective since it forces documents lazy loading usage
     *
     * @param string $query
     * @param Zend_Search_Lucene_Analysis_Analyzer $analyzer
     * @return array
     */
    public function search($query, Zend_Search_Lucene_Analysis_Analyzer $analyzer = null)
    {
        $cacheId = md5($this->_path . '-' . $this->_indexGeneration . '-query-' . $query);

        if (!$resultSet = $this->_cache->load($cacheId)) {
            if ($analyzer === null) {
                $analyzer = new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive();
            }
            Zend_Search_Lucene_Analysis_Analyzer::setDefault($analyzer);

            $hits = $this->_index->find($query);

            $resultSet = array();
            foreach ($hits as $hit) {
                $resultSet[] = $hit->id;
            }

            $this->_cache->save($resultSet, $cacheId);
        }

        return $resultSet;
    }

    /**
     * Get document by id
     *
     * @param integer $id
     */
    public function getDocument($id)
    {
        $cacheId = md5($this->_path . '-' . $this->_indexGeneration . '-doc-' . $id);

        if (!$doc = $this->_cache->load($cacheId)) {
            $indexDoc = $this->_index->getDocument($id);

            $doc = array();
            foreach ($indexDoc->getFieldNames() as $fieldName) {
                $doc[$fieldName] = $indexDoc->getFieldValue($fieldName);
            }

            $this->_cache->save($doc, $cacheId);
        }

        return $doc;
    }
}
