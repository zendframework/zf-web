<?php
class Zfstatus_Service_Git
{
    /**
     * _repositories 
     * 
     * @var array
     */
    protected $_repositories;

    protected $_cache;

    public function __construct($path, $cache = false)
    {
        $this->getRepositories(new DirectoryIterator(realpath($path)));
        if ($cache) {
            $this->setCache($cache);
        }
    }

    /**
     * getRepositories 
     * 
     * @param DirectoryIterator $directoryIterator 
     * @return array
     */
    public function getRepositories($directoryIterator = false)
    {
        if ($this->_repositories === NULL) {
            $this->_repositories = array();
            foreach ($directoryIterator as $fileInfo) {
                if (!$fileInfo->isDot() && $fileInfo->isDir()) {
                    $dirName = $fileInfo->getFilename();
                    try {
                        $this->_repositories[$dirName] = new Git_Repo(new Git_Parser($fileInfo->getPathname()), $this->getCache());
                    } catch (Exception $e) {
                        unset($this->_repositories[$dirName]);
                    }
                }
            }
        }
        return $this->_repositories;
    }
 
    /**
     * Get cache.
     *
     * @return cache
     */
    public function getCache()
    {
        return $this->_cache;
    }
 
    /**
     * Set cache.
     *
     * @param $cache the value to be set
     */
    public function setCache($cache)
    {
        $this->_cache = $cache;
        return $this;
    }
}
