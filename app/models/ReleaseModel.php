<?php

/**
 * Returns information about the latest Zend Framework
 * release download.
 */
class ReleaseModel
{

    protected $_releaseDirectory;
    protected $_productName;
    protected $_version;
    protected $_fileList;
    protected $_data;

    /**
     * @todo discover dates from SVN?
     */
    protected $_dates = array(
        '1.12.0rc2' => '2012-06-22',
        '1.12.0rc1' => '2012-06-18',
        '1.11.12'   => '2012-06-22',
        '1.11.11'   => '2011-09-29',
        '1.11.10'   => '2011-08-03',
        '1.11.9'    => '2011-07-14',
        '1.11.8'    => '2011-07-07',
        '1.11.7'    => '2011-06-02',
        '1.11.6'    => '2011-05-05',
        '1.11.5'    => '2011-04-07',
        '1.11.4'    => '2011-03-03',
        '1.11.3'    => '2011-02-01',
        '1.11.2'    => '2010-12-30',
        '1.11.1'    => '2010-11-30',
        '1.11.0'    => '2010-11-02',
        '1.11.0rc1' => '2010-10-21',
        '1.11.0b1'  => '2010-10-14',
        '1.10.9'    => '2011-05-05',
        '1.10.8'    => '2010-08-25',
        '1.10.7'    => '2010-07-28',
        '1.10.6'    => '2010-06-22',
        '1.10.5'    => '2010-05-26',
        '1.10.4'    => '2010-04-28',
        '1.10.3'    => '2010-04-01',
        '1.10.2'    => '2010-02-24',
        '1.10.1'    => '2010-02-09',
        '1.10.0'    => '2010-01-27',
        '1.10.0rc1'    => '2010-01-21',
        '1.10.0beta1'  => '2010-01-15',
        '1.10.0alpha1' => '2009-12-21',
        '1.9.8'     => '2010-04-01',
        '1.9.7'     => '2010-01-11',
        '1.8.5'     => '2010-01-11',
        '1.7.9'     => '2010-01-11',
        '1.9.6'     => '2009-11-24',
        '1.9.5'     => '2009-10-27',
        '1.9.4'     => '2009-10-13',
        '1.9.3PL1'  => '2009-09-22',
        '1.9.3'     => '2009-09-22',
        '1.9.2'     => '2009-08-25',
        '1.9.1'     => '2009-08-11',
        '1.9.0'     => '2009-07-31',
        '1.9.0rc1'  => '2009-07-28',
        '1.9.0b1'   => '2009-07-23',
        '1.9.0a1'   => '2009-07-17',
        '1.8.4PL1'  => '2009-07-02',
        '1.8.4'     => '2009-06-23',
        '1.8.3'     => '2009-06-09',
        '1.8.2'     => '2009-05-27',
        '1.8.1'     => '2009-05-12',
        '1.8.0'     => '2009-04-30',
        '1.8.0b1'   => '2009-04-23',
        '1.8.0a1'   => '2009-04-07',
        '1.7.8'     => '2009-03-30',
        '1.7.7'     => '2009-03-16',
        '1.7.6'     => '2009-03-02',
        '1.7.5'     => '2009-02-16',
        '1.7.4'     => '2009-02-02',
        '1.7.3PL1'  => '2009-01-21',
        '1.7.3'     => '2009-01-19',
        '1.7.2'     => '2008-12-23',
        '1.7.1'     => '2008-12-01',
        '1.7.0PL1'  => '2008-11-24',
        '1.7.0'     => '2008-11-17',
        '1.7.0PR'   => '2008-10-13',
        '1.6.2'     => '2008-10-13',
        '1.6.1'     => '2008-09-15',
        '1.6.0'     => '2008-09-02',
        '1.6.0RC3'  => '2008-08-26',
        '1.6.0RC2'  => '2008-08-11',
        '1.5.3'     => '2008-07-28',
        '1.6.0RC1'  => '2008-07-21',
        '1.5.2'     => '2008-05-15',
        '1.5.1'     => '2008-03-25',
        '1.5.0pl1'  => '2008-03-19',
        '1.5.0'     => '2008-03-17',
        '1.5.0RC3'  => '2008-03-13',
        '1.5.0RC2'  => '2008-03-11',
        '1.0.4'     => '2008-02-26',
        '1.5.0RC1'  => '2008-02-26',
        '1.5.0PR'   => '2008-01-28',
        '1.0.3'     => '2007-11-30',
        '1.0.2'     => '2007-09-25',
        '1.0.1'     => '2007-07-30',
        '1.0.0'     => '2007-06-30',
        '1.0.0RC3'  => '2007-06-23',
        '1.0.0RC2a' => '2007-06-11',
        '1.0.0RC2'  => '2007-06-07',
        '1.0.0RC1'  => '2007-05-28',
        '0.9.3Beta' => '2007-05-04',
        '0.9.2Beta' => '2007-04-06',
        '0.9.1Beta' => '2007-03-23',
        '0.9.0Beta' => '2007-03-16',
        '0.8.1'     => '2007-02-23',
        '0.8.0'     => '2007-02-21',
        '0.7.0'     => '2007-01-18',
        '0.6.0'     => '2006-12-16',
        '0.2.0'     => '2006-10-29',
        '0.1.5'     => '2006-07-10',
        '0.1.4'     => '2006-06-29',
        '0.1.3'     => '2006-04-18',
        '0.1.2'     => '2006-03-08',
        '0.1.1'     => '2006-03-03',
    );

    /**
     * @param string $productName
     * @param string $version OPTIONAL
     */
    public function __construct($productName, $version = null)
    {
        $this->_productName = $productName;

        $this->_releaseDirectory = dirname(__FILE__) . '/../../document_root/releases';

        $this->_getFileLists();

        if ($version === null || !array_key_exists($version, $this->_fileList)) {
            if ('ZendFramework' == $productName) {
                // Current stable version may have only 'PLxx' postfix
                foreach ($this->getVersionList() as $version) {
                    if (preg_match('/(\\d+.\\d+.\\d+)(pl(\\d+))?/i', $version)) {
                        break;
                    }
                }
            } else {
                // Other packages, we don't care about stability
                $versionList = $this->getVersionList();
                $version     = current($versionList);
            }
        }
        $this->_version = $version;

        $this->_data = $this->_fileList[$version];
    }

    protected function _getFileLists()
    {
        if (!($dh = opendir($this->_releaseDirectory))) {
            return false;
        }
        $matches[] = array();
        while (($file = readdir($dh)) !== false) {
            if (preg_match("/^{$this->_productName}-(\\d+.\\d+.\\d+)(?:-?((?:dev|alpha|a|beta|b|rc|pl|pr)(?:\\d+(?:[a-z])?)?))?/i", $file, $matches)) {
                $version = $matches[1];
                $suffix = '';
                if (isset($matches[2])) {
                    $suffix = $matches[2];
                }
                if ('dev' == substr($suffix, 0, 3)) {
                    continue;
                }
                $v     = $version . $suffix;

                $this->_fileList[$v]['version'] = $version;
                $this->_fileList[$v]['releaseSuffix'] = $suffix;
                $this->_fileList[$v]['releaseLabel'] = $this->_getReleaseLabel($version, $suffix);

                $dir = $this->_releaseDirectory . DIRECTORY_SEPARATOR . $file;
                if (is_dir($dir)) {
                    if ($subdh = opendir($dir)) {
                        $files = array();
                        while (($subfile = readdir($subdh)) !== false) {
                            $subfile = $file . '/' . $subfile;
                            if (is_file($this->_releaseDirectory . DIRECTORY_SEPARATOR . $subfile)) {
                                $this->_fileList[$v][] = $subfile;
                            }
                        }
                    }
                } else {
                    $this->_fileList[$v][] = $file;
                }
            }
        }
    }

    protected function _getReleaseLabel($version, $suffix)
    {
        if (preg_match('/^(dev|alpha|a|beta|b)(\d+[a-z]?)?/i', $suffix, $matches)) {
            $label = '';
            switch ($matches[1]) {
                case 'a':
                case 'alpha':
                case 'Alpha':
                    $label = 'Alpha';
                    break;
                case 'b':
                case 'beta':
                case 'Beta':
                    $label = 'Beta';
                case 'dev':
                    $label = 'Development';
                    break;
            }
            if (!empty($matches[2])) {
                $label .= ' ' . $matches[2];
            }
            return $label;
        }
        if (preg_match('/^RC(\d+(?:[a-z])?)?/i', $suffix, $matches)) {
            $label = 'RC';
            if (isset($matches[1])) {
                $label .= ' ' . $matches[1];
            }
            return $label;
        }
        if (preg_match('/^(pl|PL)(\d+)?/', $suffix, $matches)) {
            $label = 'patch ';
            if (isset($matches[2])) {
                $label .= ' ' . $matches[2];
            }
            return $label;
        }
        if (version_compare($version, '1.0') < 0 || preg_match('/^PR$/', $suffix)) {
            return 'Preview Release';
        }
        return '';
    }

    public function getVersionList($limit = null)
    {
        $list = array_keys($this->_fileList);
        usort($list, array($this, '_versionCompare'));
        $list = array_reverse($list);
        if (is_int($limit)) {
            $list = array_slice($list, 0, $limit);
        }
        return $list;
    }

    protected function _versionCompare($a, $b)
    {
        $cmp = version_compare(strtolower($a), strtolower($b));
        if ($cmp !== 0) {
            return $cmp;
        }
        return strcmp($a, $b);
    }

    public function getProductName()
    {
        return str_replace('Zend', 'Zend ', $this->_productName);
    }

    public function getReleaseVersion()
    {
        return $this->_data['version'];
    }

    public function getReleaseDate()
    {
        $v = $this->_data['version'] . $this->_data['releaseSuffix'];
        return $this->_dates[$v];
    }

    public function getReleaseDates()
    {
        return $this->_dates;
    }

    public function getReleases()
    {
        return array_keys($this->_dates);
    }

    public function getReleaseLabel()
    {
        return $this->_data['releaseLabel'];
    }

    public function getReleaseSuffix()
    {
        return $this->_data['releaseSuffix'];
    }

    public function getProductUrl($archive = 'tar.gz')
    {
        foreach ($this->_data as $key => $value) {
            if (!is_int($key) || preg_match('/-(apidoc|minimal|manual-)/', $value)) {
                continue;
            }
            if (preg_match("/$archive$/", $value)) {
                return '/releases/' . $value;
            }
        }
        return null;
    }

    public function getMinimalUrl($archive = 'tar.gz')
    {
        foreach ($this->_data as $key => $value) {
            if (is_int($key) && preg_match("/-minimal.$archive$/", $value)) {
                return '/releases/' . $value;
            }
        }
        return null;
    }

    public function getApidocUrl($archive = 'tar.gz')
    {
        foreach ($this->_data as $key => $value) {
            if (is_int($key) && preg_match("/-apidoc.$archive$/", $value)) {
                return '/releases/' . $value;
            }
        }
        return null;
    }

    public function getManualUrl($archive = 'tar.gz', $lang = 'en')
    {
        foreach ($this->_data as $key => $value) {
            if (is_int($key) && preg_match("/-manual-$lang.$archive$/", $value)) {
                return '/releases/' . $value;
            }
        }
        return null;
    }

    public function getManualLanguages()
    {
        $langs = array();
        foreach ($this->_data as $key => $value) {
            if (is_int($key) && preg_match("/-manual-($lang).(?:tar.gz|zip)$/", $value, $matches)) {
                $langs[] = $matches[1];
            }
        }
        return $langs;
    }

    public function isStable()
    {
    	if (substr(strtolower($this->getReleaseSuffix()), 0, 2) == 'pl') {
    	        // Consider patch releases as stable
    	    	return true;
    	    }

    	return $this->getReleaseSuffix() == '';
    }

    public function isVersionStable($version)
    {
        return !preg_match('/(a|alpha|b|beta|rc)(\d+[a-z]?)?$/i', $version);
    }
}
