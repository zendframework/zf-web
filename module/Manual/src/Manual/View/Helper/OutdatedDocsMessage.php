<?php

namespace Manual\View\Helper;

use Zend\View\Helper\AbstractHelper;

class OutdatedDocsMessage extends AbstractHelper
{
    /**
     * @var array
     */
    protected $docsPaths;

    /**
     * @var array
     */
    protected $maintainedMajorVersions;


    public function __construct($docsPaths, $maintainedMajorVersions)
    {
        $this->docsPaths = $docsPaths;
        $this->maintainedMajorVersions = $maintainedMajorVersions;
    }

    /**
     * Returns a message to show to the user if they are viewing a page from an
     * older version of Zend Framework. The message will include a link to the
     * current version of that page if possible.
     *
     * @param  string $page
     * @param  string $lang
     * @param  string $version
     * @param  array $versions
     * @return string
     */
    public function __invoke($page, $lang, $version, $versions)
    {
        // for now don't do anything for older, supported major versions
        if (in_array($version, $this->maintainedMajorVersions)) {
            return '';
        }

        $latestVersion = max($versions);

        if ($version >= $latestVersion) {
            // they're viewing the latest version of the docs already
            return '';
        }

        $message = 'The documentation you are viewing is for an older version of Zend Framework.';

        $options = array();

        // are they viewing an older version of a maintained major version?
        foreach ($this->maintainedMajorVersions as $maintainedMajorVersion) {
            if ($version < $maintainedMajorVersion) {
                // link to the equivalent page in that version if possible
                $latestPage = $this->getEquivalentPage($page, $lang, $maintainedMajorVersion);
                if ($latestPage) {
                    $options[] = '<a href="'.$latestPage.'">Click here</a> to view the equivalent page in the '.$maintainedMajorVersion.' documentation.';
                }
            }
        }

        $equivalentPage = $this->getEquivalentPage($page, $lang, $latestVersion);
        if ($equivalentPage) {
            $options[] = '<a href="'.$equivalentPage.'">Click here</a> to view the equivalent page for the current release version ('.$latestVersion.').';
        }

        if (count($options) > 0) {
            if (count($options) == 1) {
                $message .= ' '.$options[0];
            } else if (count($options) > 1) {
                $message .= '<ul><li>'.implode('</li><li>', $options).'</li></ul>';
            }
        }

        return '<div class="outdated-docs-msg alert-box alert">'.$message.'</div>';
    }

    /**
     * Returns the path to the current version of the supplied page if it
     * exists in $version, false otherwise.
     *
     * @param  string $page
     * @param  string $lang
     * @param  string $version
     * @return string|false
     */
    protected function getEquivalentPage($page, $lang, $version)
    {
        // work out what the full path to this page would be
        $fullPath = $this->docsPaths[$version][$lang] . $page;

        if (!file_exists($fullPath)) {
            return false;
        }

        return $this->view->manualUrl($page, $lang, $version);
    }
}
