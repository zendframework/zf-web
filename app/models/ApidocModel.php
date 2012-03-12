<?php
class ApidocModel
{
    protected $_apidocPath;
    protected $_docs;

    public function __construct()
    {
        $this->_apidocPath = realpath(dirname(__FILE__) . '/../../document_root/manual/apidoc');
        $this->_scanApidocPath();
    }

    public function getVersions()
    {
        return array_keys($this->_docs);
    }

    public function getDocsByVersion($version)
    {
        if (array_key_exists($version, $this->_docs)) {
            return $this->_docs[$version];
        }
        return null;
    }

    public function getAllDocs()
    {
        return $this->_docs;
    }

    protected function _scanApidocPath()
    {
        $di   = new DirectoryIterator($this->_apidocPath);
        $docs = array();
        foreach ($di as $file) {
            if ($file->isDir() || $file->isDot()) {
                continue;
            }

            $fileName = $file->getFilename();
            if (!preg_match('/^ZendFramework-((\d+)\.(\d+))\.\d+-apidoc\.(zip|tar\.gz)$/', $fileName, $matches)) {
                continue;
            }
            $minRev = $matches[1];
            $suffix = pathinfo($fileName, PATHINFO_EXTENSION);
            $suffix = ('gz' == $suffix) ? 'tar.gz' : $suffix;
            if (!array_key_exists($minRev, $docs)) {
                $docs[$minRev] = array($suffix => '/manual/apidoc/' . $fileName);
            } else {
                $docs[$minRev][$suffix] = '/manual/apidoc/' . $fileName;
            }
        }
        ksort($docs);
        $this->_docs = $docs;
    }
}
