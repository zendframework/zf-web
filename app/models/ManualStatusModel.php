<?php

class ManualStatusModel implements Serializable
{
    /**
     * @var string
     */
    protected $_fisheyeBaseUrl = 'http://framework.zend.com/code/browse/Zend_Framework/standard/trunk/documentation/manual';

    /**
     * @var string
     */
    protected $_manualSvnUrl   = 'http://framework.zend.com/svn/framework/standard/trunk/documentation/manual';

    /**
     * @var string
     */
    protected $_manualSvnHome;

    /**
     * @var string
     */
    protected $_docStatusFile;

    /**
     * @var string
     */
    protected $_svn;

    /**
     * @var array
     */
    protected $_locales = array('en',
        'ar', 'bg', 'cs', 'de', 'es', 'fa', 'fr',
        'he', 'hu', 'id', 'it', 'ja', 'ko', 'nl',
        'pl', 'pt-br', 'ro', 'ru', 'sk', 'sl',
        'sr', 'tr', 'uk', 'zh'
    );

    /**
     * @var array
     */
    protected $_status = null;

    /**
     * @param Zend_Config $config
     */
    public function __construct($config = null)
    {
        if ($config) {
            $this->setConfig($config);
        }
    }

    /**
     * @param Zend_Config $config
     * @return void
     */
    public function setConfig($config)
    {
        $this->_manualSvnHome = $config->manual->svn;
        $this->_docStatusFile = $config->manual->docstatus;
        $this->_svn           = $config->svn->binary;
    }

    /**
     * @return void
     */
    public function getStatus()
    {
        foreach ($this->_locales as $locale) {
            echo "Getting info for files in locale '$locale': ";
            $this->_getStatusPerLocale($locale, 'module_specs');
            $this->_getStatusPerLocale($locale, 'ref');
            echo "\n";
        }
    }

    /**
     * @return void
     */
    public function analyze()
    {
        if ($this->_status === null) {
            $this->getStatus();
        }

        foreach ($this->_locales as $locale) {
            echo "Analyze files in locale '$locale': \n";
            $this->_status['locale'][$locale]['numMissing'] = 0;
            foreach ($this->_status['dir'] as $dir => &$files) {
                $numFiles = count($files);
                if (isset($this->_status['locale'][$locale]['numFiles'])) {
                    $this->_status['locale'][$locale]['numFiles'] += $numFiles;
                } else {
                    $this->_status['locale'][$locale]['numFiles'] = $numFiles;
                }
                foreach ($files as $filename => &$status) {

                    // Case 1: Locale file is not present.  If it's English, or
                    // English file is also not present, then they are both
                    // 'removed'.
                    // If the Locale file is not present but English file is
                    // present, then Locale file is 'missing'.
                    if (!isset($status[$locale])) {
                        if ($locale == 'en') {
                            $status[$locale]['status'] = 'removed';
                        }
                        if ($status['en']['status'] == 'removed') {
                            $status[$locale]['status'] = 'removed';
                        } else {
                            $status[$locale]['status'] = 'missing';
                            $this->_status['locale'][$locale]['numMissing']++;
                        }
                    } else

                    // Case 2: Locale file is identical to English file.
                    if ($status[$locale]['sha1'] == $status['en']['sha1']) {
                        $status[$locale]['status'] = 'english';
                    } else

                    // Case 3: Locale file's last revision is older than
                    // English file's last revision.  But Locale file might be
                    // identical to a past English file at the same revision.
                    if ($status[$locale]['last']['rev'] < $status['en']['last']['rev']) {
                        $status[$locale]['status'] = 'older';

                        $rev = $status[$locale]['last']['rev'];
                        if (!isset($englishDigest[$filename][$rev])) {
                            $englishFilename = "en/$dir/" . $filename;
                            $englishFileContent = $this->_svnCat($englishFilename, $rev);
                            $englishDigest[$filename][$rev] = sha1($englishFileContent);
                        }
                        if ($status[$locale]['sha1'] == $englishDigest[$filename][$rev]) {
                            $status[$locale]['status'] = 'pastEnglish';
                        }
                    } else

                    // Case 4: English file is not present but Locale file is
                    // present.  So Locale file is due to be removed.
                    if ($status['en']['status'] == 'removed') {
                        $status[$locale]['status'] = 'toRemove';
                    } else

                    // Case 5: The last possibility is that the Locale file is
                    // newer than the English file.
                    {
                        $status[$locale]['status'] = 'newer';
                    }

                    printf("%5s/%-60s\t%s\n", $locale, $filename, $status[$locale]['status']);
                }
            }
        }
    }

    /**
     * @return void
     */
    public function save()
    {
        if ($this->_status === null) {
            $this->analyze();
        }

        echo "Saving status data to $this->_docStatusFile\n";

        $serialized = $this->serialize();

        if (!($fp = fopen($this->_docStatusFile, 'w'))) {
            die("Cannot open $this->_docStatusFile");
        }
        if (fwrite($fp, $serialized) === false) {
            die("Cannot write to $this->_docStatusFile");
        }
        fclose($fp);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->_status);
    }

    /**
     * @return array
     */
    public function load()
    {
        if (!($fp = fopen($this->_docStatusFile, 'r'))) {
            die("Cannot open $this->_docStatusFile");
        }
        if (($serialized = fread($fp, filesize($this->_docStatusFile))) === false) {
            die("Cannot read from $this->_docStatusFile");
        }
        fclose($fp);

        return $this->unserialize($serialized);
    }

    /**
     * @param string $serialized
     * @return mixed
     */
    public function unserialize($serialized)
    {
        $this->_status = unserialize($serialized);
        ksort($this->_status['dir']['ref']);
        ksort($this->_status['dir']['module_specs']);
        return $this->_status;
    }

    /**
     * @param string $locale
     * @param string $dir
     * @return void
     */
    protected function _getStatusPerLocale($locale, $dir)
    {
        $dirPath = $this->_manualSvnHome . '/' . $locale . '/' . $dir;
        if (!is_dir($dirPath)) {
            return array();
        }
        if (!($dh = opendir($dirPath))) {
            return array();
        }
        while ($filename = readdir($dh)) {
            if (!$filename) {
                continue;
            }
            $path = $dirPath . '/' . $filename;
            if (is_dir($path)) {
                continue;
            }
            if (!preg_match('/.xml$/', $filename)) {
                continue;
            }
            $this->_status['dir'][$dir][$filename][$locale]['sha1'] = sha1_file($path);
            $this->_status['dir'][$dir][$filename][$locale]['fisheye']
                = $this->_fisheyeBaseUrl . '/' . $locale . '/' . $dir . '/' . $filename;
        }

        $svnInfoArray = $this->_svnInfo($dirPath);
        foreach ($svnInfoArray as $svnFilename => $svnInfo) {
            $this->_status['dir'][$dir][$svnFilename][$locale]['last'] = $svnInfo;
            echo '.';
        }
        unset($this->_status['dir'][$dir]['']);
    }

    /**
     * @todo use PEAR SVN package
     *
     * @param string $dir
     * @return array
     */
    protected function _svnInfo($dir)
    {
        $command = $this->_svn . " info --recursive --config-dir /tmp \"$dir\"";
        $svnOutput = shell_exec($command);
        $filename = '';
        $svnInfo = array();
        foreach (explode("\n", $svnOutput) as $svnLine) {
            if (preg_match('/^Name: (.*)$/', $svnLine, $matches)) {
                $filename = $matches[1];
                continue;
            }
            if (preg_match('/^Last Changed Author: (.+)$/', $svnLine, $matches)) {
                $lastChangeAuthor = $matches[1];
                $svnInfo[$filename]['author'] = $lastChangeAuthor;
                continue;
            }
            if (preg_match('/^Last Changed Rev: (\d+)$/', $svnLine, $matches)) {
                $lastChangeRevision = $matches[1];
                $svnInfo[$filename]['rev'] = $lastChangeRevision;
                continue;
            }
            if (preg_match('/^Last Changed Date: ([^(]+)/', $svnLine, $matches)) {
                $lastChangeDate = $matches[1];
                $svnInfo[$filename]['date'] = $lastChangeDate;
                $svnInfo[$filename]['timestamp'] = strtotime($lastChangeDate);
                continue;
            }
        }
        return $svnInfo;
    }

    /**
     * @todo use PEAR SVN package
     *
     * @param string $filename
     * @param string $revision
     * @return string
     */
    protected function _svnCat($filename, $revision = null)
    {
        $command = $this->_svn . ' cat';
        if ($revision) {
            $command .= " --revision $revision";
        }
        $filename = $this->_manualSvnUrl . '/' . $filename;
        $command .= " --config-dir /tmp \"$filename\"";
        $content = shell_exec($command);
        return $content;
    }

}

