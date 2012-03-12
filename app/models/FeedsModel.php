<?php

/** Zend_Feed */
require_once 'Zend/Feed.php';


class FeedsModel
{
    const ZEND_DEVZONE   = 'zend_devzone';
    const ZEND_FRAMEWORK = 'zend_framework';
    const MANAGEMENT     = 'Zend_Framework_Management';
    const DEVELOPER      = 'Zend_Framework_Developer';
    const CONTRIBUTOR    = 'Zend_Framework_Contributor';

    protected $_cacheDir;


    /**
     * Class constructor - sets the directory where feeds are cached
     */
    public function __construct()
    {
        $this->_cacheDir = dirname(dirname(__FILE__)) . '/cache/feeds';
    }


    /**
     * Returns the contents of a feed, or FALSE if none or invalid
     *
     * @param string $feedConst
     * @return string|false
     */
    public function getFeed($feedConst)
    {
        switch ($feedConst) {
            case self::ZEND_DEVZONE:
            case self::ZEND_FRAMEWORK:
            case self::MANAGEMENT:
            case self::DEVELOPER:
            case self::CONTRIBUTOR:
                return $this->_readFeedFile($feedConst);
            default:
                return false;
        }
    }


    /**
     * Reads a feed file from the cache
     *
     * @param string $filename
     * @return string|false
     */
    public function _readFeedFile($filename)
    {
        try {
            $file = Zend_Feed::importFile("$this->_cacheDir/$filename.xml");
        } catch (Zend_Feed_Exception $e) {
            $file = false;
        }

        return $file;
    }
}
