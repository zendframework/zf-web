<?php

/** Zend_Registry */
require_once 'Zend/Registry.php';

/**
 * Read the manual files
 */
class ManualModel
{
    /**
     * Directory where the FAQ data files are stored
     * @var string
     */
    protected $_directory;

    /**
     * Directory where the FAQ data files are stored
     * @var string
     */
    protected $_manual;

    /**
     * Array describing locales
     *
     * [code]
     *     name => English
     */
    protected $_locales = array();

    /**
     * Code of currently selected locale
     */
    protected $_code = 'en';

    protected $_versions = array();

    /**
     * Class Constructor
     */
    public function __construct($locale = 'en')
    {
        $this->_directory = dirname(__FILE__) . '/Manual';

        $config = Zend_Registry::get('config');

        $this->_manual  = $config->manual->source;
        $this->setVersions($config->manual->versions->toArray());
        $this->_locales = parse_ini_file($this->_directory . '/translations.ini', true);
        $this->setLocale($locale);
    }

    /**
     * Set manual versions available
     *
     * Create the mapping of minor versions to specific mini-release versions
     * to use when displaying manual versions.
     * 
     * @param  array $versions 
     * @return ManualModel
     */
    public function setVersions(array $versions)
    {
        foreach ($versions as $key => $value) {
            $this->addVersion($key, $value);
        }
        return $this;
    }

    /**
     * Add a manual version mapping
     *
     * Mapping keys should be minor versions, and may optionally be prefixed with 'r_' (which 
     * will be stripped).
     * 
     * @param  string $minor 
     * @param  string $specific 
     * @return ManualModel
     */
    public function addVersion($minor, $specific)
    {
        $minor = preg_replace('/^r_(.*)$/', '$1', $minor);
        $minor = str_replace('_', '.', $minor);
        $this->_versions[$minor] = $specific;
        return $this;
    }

    /**
     * Retrieve all manual version mappings
     * 
     * @return array
     */
    public function getVersions()
    {
        return $this->_versions;
    }

    /**
     * Retrieve the specific manual version to use, given a minor version 
     * number.
     *
     * If provided with a specific manual version, will determine the minor 
     * version prior to doing the lookup.
     * 
     * @param  string $minor 
     * @return string
     * @throws Exception if invalid minor version, or no corresponding revision
     */
    public function getVersion($minor)
    {
        $minor = $this->getManualVersion($minor);
        if (!isset($this->_versions[$minor])) {
            throw new Exception('Invalid minor version supplied; registered versions include: ' . var_export($this->_versions, 1));
        }
        return $this->_versions[$minor];
    }

    /**
     * Get the manual version requested (i.e., minor revision)
     * 
     * @param  string $version 
     * @return string
     * @throws Exception if 'current' version requested, but none registered
     */
    public function getManualVersion($version)
    {
        if ($version == 'current') {
            if (!isset($this->_versions['current'])) {
                throw new Exception('No current version registered');
            }
            $version = $this->_versions['current'];
        }

        return preg_replace('/^(\d+\.\d+)\..*$/', '$1', $version);
    }

    /**
     * Retrieve manual source directory
     *
     * Injects appropriate version number into source manual specification.
     * 
     * @param  string $version 
     * @return  string
     */
    public function getManualSourceDirectory($version)
    {
        $version = $this->getVersion($version);
        return sprintf($this->_manual, $version);
    }

    /**
     * Sets the locale from which manual translations will be retrieved.
     *
     * If and only if the locale does not exist or is not published, this method returns false.
     *
     * @return boolean
     */
    public function setLocale($code)
    {
        if (!isset($this->_locales[$code]['published']) || !$this->_locales[$code]['published']) {
            return false;
        }

        $this->_code = $code;
        return true;
    }


    /**
     * Return an array of data for all locales
     */
    public function getLocales()
    {
        return $this->_locales;
    }


    /**
     * Processes text from the manual or FALSE on failure
     *
     * @param string $filename
     * @return string|boolean
     */
    public function getManualFile($filename, $version = 'current')
    {
        // security check
        if (preg_match('/[^a-z0-9\-_.]/i', $filename)) {
            return false;
        }

        $filename = $this->getManualSourceDirectory($version) 
                  . '/' . $this->_code 
                  . "/$filename";
        $page   = @file_get_contents($filename);

        if ($page === false) {
            return false;
        }

        if ('index.html' == basename($filename)) {
            $page = $this->_reformatIndex($page);
        }

        $page = $this->_reformat($page, $version);

        return $page;
    }

    /**
     * Slightly restructures the docbook HTML for display
     * within the site layout
     *
     * @param  string $page
     * @param  string $version
     * @return string
     */
    protected function _reformat($page, $version)
    {
        // We don't need any of this junk.
        $page = preg_replace('!<link rel="[^>]*" href="[^>]*" title="[^>]*">!is',
                             '', $page);

        // ...or this.
        $page = str_replace(array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">',
                                  '<html>',
                                  '<head>',
                                  '</head>',
                                  '<body>',
                                  '</body>',
                                  '</html>',
                                  '<div class="revinfo"></div>',
                                  '<link rel="stylesheet" href="dbstyle.css" type="text/css">',
                                  '<meta name="generator" content="DocBook XSL Stylesheets V1.69.1">',
                                  '<body bgcolor="white" text="black" link="#0000FF" vlink="#840084" alink="#0000FF">',
                                  '<title>Programmer\'s Reference Guide</title>'),
                            '', $page);

        // ... or this
        $page = preg_replace('!<title>[^>]*</title>!is',
                             '',
                             $page);


        $page = str_replace('<h2 class="title" style="clear: both">',
                            '<h2>',
                            $page);

        $page = str_replace('<hr>',
                            '<hr />',
                            $page);

        // fix navigation header - collapsing bug in IE?
        $page = str_replace('<table width="100%" summary="Navigation header">',
                            '<table width="99%" summary="Navigation header">',
                            $page);

        // fix navigation footer
        $page = str_replace('<div class="navfooter">',
                            '<br clear="all" /><div class="navfooter">',
                            $page);

        // rewrite links (don't rewrite absolute links or in-page references)
        $versionString = ('current' === $version)
                       ? ''
                       : $this->getManualVersion($version) . '/';
        preg_match_all('!<[^>]*href="([^"]+)"[^>]*>!is', $page, $matches);
        foreach ($matches[0] as $i => $href) {
            if (!strpos($matches[1][$i], '://') && $matches[1][$i][0] != '#') {
                $replace = '<a href="/manual/' . $versionString . $this->_code . '/' . $matches[1][$i]. '">';
                $page = str_replace($href, $replace, $page);
            }
        }

        // fix notes
        $page = preg_replace('!<img alt="\[([^"]+)\]" src="images/note.png">!s',
                            '<img alt="[\\1]" src="/images/note.gif" style="border: 0;" />',
                            $page);

        // fix tips
        $page = preg_replace('!<img alt="\[([^"]+)\]" src="images/tip.png">!s',
                            '<img alt="[\\1]" src="/images/tip.gif" style="border: 0;" />',
                            $page);


        // highlight source code
        preg_match_all('!<pre class="programlisting">([^>]+)</pre>!is', $page, $matches);
        foreach ($matches[0] as $i => $programlisting) {
            // original HTML-formatted source, without <pre></pre> wrapper
            $code = trim(html_entity_decode($matches[1][$i]));

            // reformat to highlighted PHP source code
            $replace = '<pre class="programlisting">'
                     . highlight_string($code, true)
                     . '</pre>';

            // insert formatted source into original document
            $page = str_replace($programlisting, $replace, $page);
        }

        return $page;
    }

    /**
     * Strip out unwanted depth from TOC
     * 
     * @param  string $page 
     * @return string
     */
    protected function _reformatIndex($page)
    {
        $d = new DOMDocument('1.0', 'UTF-8');
        $d->strictErrorChecking = false;
        $d->loadHTML($page);

        $removeNodes = array();

        $x = new DOMXPath($d);

        // Grab all spans with a class of "sect2"
        $sect2 = $x->query('//span[@class="sect2"]');
        foreach ($sect2 as $span) {
            // Grab the parent node of the span (a <dt> element)
            $parent = $x->query('parent::*', $span);
            foreach ($parent as $dt) {
                // Check to see if the next node after the parent is a <dd>;
                // if so, mark it for removal
                $uncle = $x->query('following-sibling::*', $dt);
                foreach ($uncle as $dd) {
                    if ($dd->nodeName == 'dd') {
                        $removeNodes[] = $dd;
                    }
                }

                // Mark the <dt> for removal
                $removeNodes[] = $dt;
            }
        }

        // Remove marked nodes
        foreach ($removeNodes as $node) {
            $parent = $node->parentNode;
            if (empty($parent)) {
                // No parent node means a bad match; do nothing
                continue;
            }

            // Remove the node
            $parent->removeChild($node);

            // Query the parent now to see if it's empty; if so, remove it
            $list = $x->query('*', $parent);
            if (0 === $list->length) {
                $gp = $parent->parentNode;
                if (!empty($gp)) {
                    $gp->removeChild($parent);
                }
            }
        }

        // Save the new HTML
        $html = $d->saveHTML();    
        if (empty($html)) {
            // if an error occurs saving the HTML, return the original text
            return $page;
        }

        return $html;
    }

    /**
     * Retrieve the TOC list for this language
     * 
     * @return array
     */
    public function getTocList($version = 'current')
    {
        $filename = $this->getManualSourceDirectory($version) 
                  . '/' . $this->_code 
                  . "/index.html";
        $page     = @file_get_contents($filename);

        if ($page === false) {
            return false;
        }

        // rewrite links (don't rewrite absolute links or in-page references)
        if ('current' !== $version) {
            $version = $this->getManualVersion($version);
        }
        $linkVersion = ('current' === $version) ? '' : '/' . $version;

        // Rewrite links
        preg_match_all('!<[^>]*href="([^"]+)"[^>]*>!is', $page, $matches);
        foreach ($matches[0] as $i => $href) {
            if (!strpos($matches[1][$i], '://') && $matches[1][$i][0] != '#') {
                $replace = '<a href="/manual' 
                         . $linkVersion
                         . '/' . $this->_code 
                         . '/'. $matches[1][$i]. '">';
                $page = str_replace($href, $replace, $page);
            }
        }

        // Get part/chapter entries for TOC
        $items = array();
        preg_match_all('!<span class="(part|chapter|appendix)"><a[^>]*href="([^"]+)"[^>]*>([^<]*)<!is', $page, $matches);
        foreach (array_keys($matches[0]) as $i) {
            $title = $matches[3][$i];
            $title = preg_replace('/^\s*[A-Z0-9]+\.(\d+\.)*\s/', '', $title);
            $item  = array(
                'href' => $matches[2][$i],
                'text' => $title,
            );
            $items[] = $item;
        }

        return $items;
    }
}
