<?php

/**
 * Returns information about Zend Framework
 * development downloads.
 */
class SnapshotModel
{
    const DEVELOPMENT_VERSION = '1.0.0';
    const SNAPSHOT_DIRECTORY = '/var/local/framework/snapshots';
    const SNAPSHOT_URL = '/snapshots';
    private $snapshots = array();

    
    /**
     * Returns the version number of the latest release.
     * @return string
     */
    public function getDevVersion()
    {
        return self::DEVELOPMENT_VERSION;
    }
    

    /**
     * Returns the directory where snapshots are stored.
     * @return string
     */
    public function getSnapshotDirectoryname()
    {
        return self::SNAPSHOT_DIRECTORY;
    }
    

    /**
     * Returns the directory where snapshots are stored.
     * @return string
     */
    public function getSnapshotUrl()
    {
        return self::SNAPSHOT_URL;
    }
    

    /**
     * Returns the Zend Framework release filename if the
     * release is available in the given file extension,
     * or FALSE if not found.
     *
     * @return array
     */
    public function __construct()
    {
	$globlist = glob(self::SNAPSHOT_DIRECTORY."/ZendFramework-*.*");
	rsort($globlist); // sort in reverse alphabetical order

	foreach ($globlist as $filename) {
		$filesize = filesize($filename);
		$filesize /= (1024*1024);
		$filesize = round($filesize, 1);
		$basename = basename($filename);
		preg_match('/(ZendFramework-\d+-\d+).?(en|all)?\.(tar\.gz|zip)/', $basename, $matches);
		$base = $matches[1];
		$langbundle = $matches[2];
		if ($langbundle == '') {
			$langbundle = 'all';
		}
		$archive = $matches[3];
		if ($base != '' && $langbundle != '' && $archive != '') {
			$this->snapshots[$base][$langbundle][$archive] = array('filename' => $basename, 'filesize' => $filesize);
		}
	}
    }

    /**
     * Returns the Zend Framework release filename if the
     * release is available in the given file extension,
     * or FALSE if not found.
     *
     * @return array
     */
    public function getSnapshots()
    {
	return $this->snapshots;
    }

    /**
     * Returns the Zend Framework release filename if the
     * release is available in the given file extension,
     * or FALSE if not found.
     *
     * @return array
     */
    public function getLatestSnapshot()
    {
	reset($this->snapshots);
	$s = current($this->snapshots);
	if ($s['en']) {
		return $s['en'];
	} else {
		return $s['all'];
	}
    }
}

