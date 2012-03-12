<?php

class ChangelogModel
{
    protected $_issues;
    protected $_versions;

    /**
     * Class constructor
     *
     *  - Update the cache if necessary
     */
    public function __construct()
    {
        $this->_issues = include APPLICATION_PATH . '/cache/changelogData.php';
        $versions = array_keys($this->_issues);
        natsort($versions);
        $this->_versions = array_reverse($versions);
    }

    public function hasVersion($version) 
    {
        return in_array($version, $this->_versions);
    }

    public function getIssuesByVersion($version)
    {
        if ($this->hasVersion($version)) {
            return $this->_issues[$version];
        }
        return false;
    }

    public function getAllVersions()
    {
        return $this->_issues;
    }

    public function getLatestVersion()
    {
        return $this->_versions[0];
    }

    public function getVersions()
    {
        return $this->_versions;
    }
}
