<?php
namespace Downloads\Model;

use DomainException;
use InvalidArgumentException;
use Traversable;

/**
 * Returns information about the latest Zend Framework
 * release download.
 */
class ReleaseModel
{
    const ARCHIVE_TAR = 'tar.gz';
    const ARCHIVE_ZIP = 'zip';

    protected $archiveTypes = array(
        self::ARCHIVE_TAR,
        self::ARCHIVE_ZIP,
    );
    protected $languages;
    protected $mostRecentVersion;
    protected $normalizedVersions;
    protected $products;
    protected $releaseBasePath;
    protected $releaseTemplates = array(
        'framework-full-v0'    => '%s/ZendFramework-%s.%s',
        'framework-full'       => '%s/ZendFramework-%s/ZendFramework-%s.%s',
        'framework-minimal'    => '%s/ZendFramework-%s/ZendFramework-%s-minimal.%s',
        'framework-minimal-v2' => '%s/ZendFramework-%s/ZendFramework-minimal-%s.%s',
        'framework-manual'     => '%s/ZendFramework-%s/ZendFramework-%s-manual-%s.%s',
        'framework-apidoc'     => '%s/ZendFramework-%s/ZendFramework-%s-apidoc.%s',
        'product'              => '%s/Zend%s-%s/Zend%s-%s.%s',
    );
    protected $sortedVersions;
    protected $versions;

    /**
     * @param  string $releaseBasePath Base URI for releases
     * @param  array $versions hash table of version/release date pairs
     * @param  array $languages Manual languages available, by range of versions
     * @param  array $products Products available, and first and latest version available
     */
    public function __construct($releaseBasePath, $versions, $languages, $products)
    {
        $this->releaseBasePath = rtrim($releaseBasePath, '/');

        if (!is_array($versions) && !$versions instanceof Traversable) {
            throw new InvalidArgumentException('Invalid versions provided');
        }
        if ($versions instanceof Traversable) {
            $versions = iterator_to_array($versions);
        }
        $this->versions = $versions;

        if (!is_array($languages) && !$languages instanceof Traversable) {
            throw new InvalidArgumentException('Invalid languages provided');
        }
        $this->languages = array();
        foreach ($languages  as $data) {
            if (!is_array($data) && !$data instanceof Traversable) {
                throw new InvalidArgumentException('Invalid language specification provided');
            }

            if ($data instanceof Traversable) {
                $data = iterator_to_array($data);
            }
            $this->languages[] = $data;
        }

        if (!is_array($products) && !$products instanceof Traversable) {
            throw new InvalidArgumentException('Invalid products provided');
        }
        $this->products = array();
        foreach ($products as $product => $data) {
            if (!is_array($data) && !$data instanceof Traversable) {
                throw new InvalidArgumentException('Invalid product specification provided');
            }

            if ($data instanceof Traversable) {
                $data = iterator_to_array($data);
            }
            $data['product'] = $product;
            $this->products[strtolower($product)] = $data;
        }
    }

    /**
     * Retrieve the current stable version
     *
     * If no version is provided, locates the most stable current version.
     *
     * If an integer version string is provided, finds the latest stable
     * version in that major revision.
     *
     * If a minor version string ("X.Y") is provided, finds the latest
     * stable version in that minor revision.
     *
     * If for any reason a lookup fails (e.g., major or minor version
     * does not exist, or does not have a stable version), returns false.
     * 
     * @param  null|int|string $version 
     * @return string|false
     */
    public function getCurrentStableVersion($version = null)
    {
        if (null === $version) {
            return $this->findMostRecentVersion();
        }

        if (!strstr($version, '.')) {
            $next     = ($version + 1) . '.0.0';
            $version .= '.0.0';
            return $this->findMostRecentVersionInSeries($version, $next);
        }

        list($major, $minor) = explode('.', $version, 2);
        if (strstr($minor, '.')) {
            throw new \DomainException(sprintf(
                'Invalid version "%s" provided to %s; must be a major or minor version only',
                $version,
                __METHOD__
            ));
        }
        $start = $version . '.0';
        $end   = sprintf('%d.%d.0', $major, ($minor + 1));
        return $this->findMostRecentVersionInSeries($start, $end);
    }

    /**
     * Get date for a release
     *
     * If a matching release is found, returns the date; otherwise, returns
     * boolean false.
     * 
     * @param  string $version 
     * @return string|false
     */
    public function getReleaseDate($version)
    {
        $version  = $this->normalizeVersion($version);
        $versions = $this->getNormalizedVersions();
        if (isset($versions[$version])) {
            return $versions[$version];
        }
        return false;
    }

    /**
     * Get list of version/release date pairs
     *
     * @return array
     */
    public function getReleaseDates()
    {
        return $this->versions;
    }

    /**
     * Retrieve list of all versions
     * 
     * @return array
     */
    public function getVersions()
    {
        return $this->sortVersions();
    }

    /**
     * Does the given version have a minimal version?
     * 
     * @param  string $version 
     * @return bool
     */
    public function hasMinimalVersion($version)
    {
        $version = $this->normalizeVersion($version);
        if (strnatcmp($version, '1.6.0') < 0
            || preg_match('/^1\.6\.0rc/', $version)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Whether or not the given version has API docs
     * 
     * @param mixed $version 
     * @return void
     */
    public function hasApidocs($version)
    {
        if (strnatcasecmp($version, '1.0.0') < 0
            || preg_match('/^1\.0\.0-rc1$/i', $version)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Whether or not the given version has end-user docs
     * 
     * @param mixed $version 
     * @return void
     */
    public function hasDocumentation($version)
    {
        $languages = $this->getManualLanguages($version);
        return (!empty($languages));
    }

    /**
     * Retrieve major version from version string
     * 
     * @param  string $version 
     * @return string
     */
    public function getMajorVersion($version)
    {
        if (!strstr($version, '.')) {
            throw new InvalidArgumentException(sprintf(
                'Invalid version string provided to %s',
                __METHOD__
            ));
        }
        list($major, $remainder) = explode('.', $version, 2);
        if (!is_numeric($major)) {
            throw new InvalidArgumentException(sprintf(
                '%s: Invalid major version segment "%s"; must be numeric',
                __METHOD__,
                $major
            ));
        }
        return $major;
    }

    /**
     * Retrieve minor version from version string
     * 
     * @param  string $version 
     * @return string
     */
    public function getMinorVersion($version)
    {
        if (!strstr($version, '.')) {
            throw new InvalidArgumentException(sprintf(
                'Invalid version string provided to %s',
                __METHOD__
            ));
        }
        $segments = explode('.', $version, 3);
        $major    = array_shift($segments);
        $minor    = array_shift($segments);
        foreach (array('major' => $major, 'minor' => $minor) as $key => $value) {
            if (!is_numeric($value)) {
                throw new InvalidArgumentException(sprintf(
                    '%s: Invalid %s version segment "%s"; must be numeric',
                    __METHOD__,
                    $key,
                    $value
                ));
            }
        }
        return $major . '.' . $minor;
    }

    /**
     * Retrieve the URI for a download archive by version and format
     *
     * Utilizes the release base path provided at instantiation.
     *
     * @param  string $version 
     * @param  string $format One of the ARCHIVE_* constants
     * @return string|false
     * @throw  InvalidArgumentException if the $version or $format is unknown
     */
    public function getArchive($version, $format = self::ARCHIVE_TAR)
    {
        if (!isset($this->versions[$version])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid version "%s" provided; cannot create archive URI string',
                $version
            ));
        }
        if (!in_array($format, $this->archiveTypes)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid format "%s" provided; must be one of "%s" or "%s"',
                $format,
                self::ARCHIVE_TAR,
                self::ARCHIVE_ZIP
            ));
        }

        list($major, $minor, $patch) = explode('.', $version, 3);
        if ($major > 1 && $format == self::ARCHIVE_TAR) {
            $format = 'tgz';
        }

        $template = $this->releaseTemplates['framework-full'];
        $params   = array(
            $this->releaseBasePath,
            $version,
            $version,
            $format,
        );
        if (strnatcasecmp($version, '1.0.0') < 0
            || preg_match('/^1\.0\.0-rc1$/i', $version)
        ) {
            $template = $this->releaseTemplates['framework-full-v0'];
            $params   = array(
                $this->releaseBasePath,
                $version,
                $format,
            );
        }

        return vsprintf($template, $params);
    }

    /**
     * Retrieve the URI for a MINIMAL download archive by version and format
     *
     * Utilizes the release base path provided at instantiation.
     *
     * @param  string $version 
     * @param  string $format One of the ARCHIVE_* constants
     * @return string|false
     * @throw  InvalidArgumentException if the $version or $format is unknown
     */
    public function getMinimalArchive($version, $format = self::ARCHIVE_TAR)
    {
        if (!isset($this->versions[$version])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid version "%s" provided; cannot create minimal archive URI string',
                $version
            ));
        }
        if (!$this->hasMinimalVersion($version)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid version "%s" provided; versions prior to 1.6.0 did not have minimal packages',
                $version
            ));
        }
        if (!in_array($format, $this->archiveTypes)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid format "%s" provided; must be one of "%s" or "%s"',
                $format,
                self::ARCHIVE_TAR,
                self::ARCHIVE_ZIP
            ));
        }

        list($major, $minor, $patch) = explode('.', $version, 3);
        if ($major > 1 && $format == self::ARCHIVE_TAR) {
            $format = 'tgz';
        }

        $template = (strnatcasecmp($version, '2.0.0') >= 0) 
                  ? $this->releaseTemplates['framework-minimal-v2'] 
                  : $this->releaseTemplates['framework-minimal'];

        return sprintf(
            $template,
            $this->releaseBasePath,
            $version,
            $version,
            $format
        );
    }

    /**
     * Get manual languages for a given version
     *
     * Returns an array of version strings.
     * 
     * @param  string $version 
     * @return array
     */
    public function getManualLanguages($version)
    {
        $version = $this->normalizeVersion($version);
        foreach ($this->languages as $data) {
            $languages = $this->compareLanguageVersions($version, $data);
            if ($languages) {
                return $languages;
            }
        }
        return array();
    }

    /**
     * Get a download archive URL for a given version and language of the manual
     *
     * If the version does not exist, or the manual does not exist in the given 
     * language, raises an exception
     * 
     * @param  string $version 
     * @param  string $language 
     * @param  string $format 
     * @return string|false
     */
    public function getManualArchive($version, $language, $format = self::ARCHIVE_TAR)
    {
        $languages = $this->getManualLanguages($version);
        $language  = strtolower($language);
        if (!in_array($language, $languages)) {
            throw new InvalidArgumentException(sprintf(
                'Either the version "%s" does not exist, or the language "%s" is not available in that version',
                $version,
                $language
            ));
        }

        if (!in_array($format, $this->archiveTypes)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid format "%s" provided; must be one of "%s" or "%s"',
                $format,
                self::ARCHIVE_TAR,
                self::ARCHIVE_ZIP
            ));
        }

        list($major, $minor, $patch) = explode('.', $version, 3);
        if ($major > 1 && $format == self::ARCHIVE_TAR) {
            $format = 'tgz';
        }

        return sprintf(
            $this->releaseTemplates['framework-manual'],
            $this->releaseBasePath,
            $version,
            $version,
            $language,
            $format
        );
    }

    /**
     * Retrieve an API documentation archive for the given version, in the given format
     *
     * Returns false if version does not exist.
     * 
     * @param  string $version 
     * @param  string $format 
     * @return string|false
     */
    public function getApidocArchive($version, $format = self::ARCHIVE_TAR)
    {
        if (!isset($this->versions[$version])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid version "%s" provided; cannot create apidoc URI string',
                $version
            ));
        }
        if (!in_array($format, $this->archiveTypes)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid format "%s" provided; must be one of "%s" or "%s"',
                $format,
                self::ARCHIVE_TAR,
                self::ARCHIVE_ZIP
            ));
        }

        list($major, $minor, $patch) = explode('.', $version, 3);
        if ($major > 1 && $format == self::ARCHIVE_TAR) {
            $format = 'tgz';
        }

        return sprintf(
            $this->releaseTemplates['framework-apidoc'],
            $this->releaseBasePath,
            $version,
            $version,
            $format
        );
    }

    /**
     * Get a download archive URI for a given product version, in the format specified
     * 
     * @param  string $product 
     * @param  string $version 
     * @param  string $format 
     * @return string
     */
    public function getProductArchive($product, $version, $format = self::ARCHIVE_TAR)
    {
        if (!$this->productVersionExists($product, $version)) {
            throw new DomainException(sprintf(
                'Cannot locate archive for product "%s" of version "%s"',
                $product,
                $version
            ));
        }

        if (!in_array($format, $this->archiveTypes)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid format "%s" provided; must be one of "%s" or "%s"',
                $format,
                self::ARCHIVE_TAR,
                self::ARCHIVE_ZIP
            ));
        }

        $product = $this->products[strtolower($product)]['product'];

        return sprintf(
            $this->releaseTemplates['product'],
            $this->releaseBasePath,
            $product,
            $version,
            $product,
            $version,
            $format
        );
    }

    /**
     * Retrieve the most recent stable product version
     *
     * If version is not provided, returns most recent stable version.
     *
     * If version is a major string, returns most recent stable version on that
     * major branch.
     * 
     * If version is a minor string, returns most recent stable version on that
     * minor branch.
     * 
     * @param  string $product 
     * @param  null|string $version 
     * @return string|false
     */
    public function getCurrentStableProductVersion($product, $version = null)
    {
        $product = strtolower($product);
        if (!isset($this->products[$product])) {
            throw new InvalidArgumentException(sprintf(
                'Unknown product "%s" provided',
                $product
            ));
        }

        if (null === $version) {
            $max = $this->products[$product]['latest'];
            return $this->getCurrentStableVersion($max);
        }

        $current = $this->getCurrentStableVersion($version);
        if (!$this->productVersionExists($product, $current)) {
            return false;
        }
        return $current;
    }

    /**
     * Get all versions for a given product
     * 
     * @param  string $product 
     * @return array
     */
    public function getProductVersions($product)
    {
        $product = strtolower($product);
        if (!isset($this->products[$product])) {
            throw new InvalidArgumentException(sprintf(
                'Unknown product "%s" provided',
                $product
            ));
        }
        if (isset($this->products[$product]['versions'])) {
            return $this->products[$product]['versions'];
        }

        $first = $this->products[$product]['first'];
        $last  = $this->products[$product]['latest'];
        $last  = $this->getCurrentStableVersion($last);

        $allVersions = $this->sortVersions();
        $versions    = array();
        foreach ($allVersions as $version) {
            if (version_compare($version, $first, 'lt')) {
                continue;
            }
            if (version_compare($version, $last, 'gt')) {
                continue;
            }
            $versions[] = $version;
        }

        natsort($versions);
        $versions = array_reverse($versions);

        $this->products[$product]['versions'] = $versions;
        return $versions;
    }

    /**
     * Get list of released products
     * 
     * @return array
     */
    public function getProducts()
    {
        $products = array();
        foreach ($this->products as $data) {
            $products[] = $data['product'];
        }
        return $products;
    }

    /**
     * Does the given version string represent a stable version?
     * 
     * @param  string $version 
     * @return bool
     */
    public function isStable($version)
    {
        $version = $this->normalizeVersion($version);
        return !preg_match('/(a|alpha|b|beta|rc|dev)/', $version);
    }

    /**
     * Normalize the version string
     *
     * Normalizes to lowercase. If the version string contains 'pr' ("preview
     * release"), replaces it with "alpha".
     * 
     * @param  string $version 
     * @return string
     */
    public function normalizeVersion($version)
    {
        $version = strtolower($version);

        if (strstr($version, 'pr')) {
            $version = str_replace('pr', 'alpha', $version);
        }
        if (strstr($version, 'pl')) {
            $version = str_replace('pl', 'p', $version);
        }
        return $version;
    }

    /**
     * Retrieve a list of version/date pairs, with the version strings normalized
     * 
     * @return array
     */
    protected function getNormalizedVersions()
    {
        if ($this->normalizedVersions) {
            return $this->normalizedVersions;
        }

        $this->normalizedVersions = array();
        foreach ($this->versions as $version => $date) {
            $this->normalizedVersions[$this->normalizeVersion($version)] = $date;
        }
        return $this->normalizedVersions;
    }

    /**
     * Find the most recent stable version.
     * 
     * @return string
     */
    protected function findMostRecentVersion()
    {
        if ($this->mostRecentVersion) {
            return $this->mostRecentVersion;
        }
        $versions = $this->sortVersions();

        foreach ($versions as $version) {
            if ($this->isStable($version)) {
                $this->mostRecentVersion = $version;
                break;
            }
        }

        return $this->mostRecentVersion;
    }

    /**
     * Find the most recent stable version in a series
     *
     * Accepts the start version representing the floor, and the end version
     * representing the ceiling for a range in which to scan. Returns the most
     * recent stable version between those versions, including the start 
     * version, but less than the end.
     *
     * If no stable version is found in that range, returns false.
     * 
     * @param  string $start 
     * @param  string $end 
     * @return string|false
     */
    protected function findMostRecentVersionInSeries($start, $end)
    {
        $versions = $this->sortVersions();
        $versions = array_reverse($versions);
        $current  = false;
        foreach ($versions as $version) {
            if (version_compare($version, $end, 'ge')) {
                break;
            }
            if (version_compare($version, $start, 'lt')) {
                continue;
            }
            if (!$this->isStable($version)) {
                continue;
            }

            $current = $version;
        }
        return $current;
    }

    /**
     * Sort versions
     *
     * Sorts the versions provided to the constructor, and returns a list
     * of all versions in reverse order.
     * 
     * @return array
     */
    protected function sortVersions()
    {
        if ($this->sortedVersions) {
            return $this->sortedVersions;
        }
        $versions = array_keys($this->versions);
        array_walk($versions, array($this, 'normalizeVersion'));
        if (!usort($versions, 'version_compare')) {
            throw new \DomainException('Sorting failed?');
        }
        $this->sortedVersions = array_reverse($versions);
        return $this->sortedVersions;
    }

    /**
     * Determine if the version provided falls within the range specified in the 
     * data. If so return the languages from the data; otherwise, return false.
     * 
     * @param  string $version 
     * @param  array|\ArrayAccess $data 
     * @return false|array
     */
    protected function compareLanguageVersions($version, $data)
    {
        $versionMatch = $data['version_match'];
        if (version_compare($version, $versionMatch['min'], 'ge')
            && (false === $versionMatch['max'] 
                || version_compare($version, $versionMatch['max'], 'lt'))
        ) {
            return $data['languages'];
        }
        return false;
    }

    /**
     * Determine if a product version exists
     *
     * Throws an exception if no matching product is found.
     *
     * If the product exists, it checks that $version is in the range
     * of versions available for that product.
     * 
     * @param  string $product 
     * @param  string $version 
     * @return bool
     */
    protected function productVersionExists($product, $version)
    {
        $product = strtolower($product);
        if (!isset($this->products[$product])) {
            throw new DomainException(sprintf(
                'Unable to find product by name of "%s"',
                $product
            ));
        }

        $versions = $this->products[$product];
        $version  = $this->normalizeVersion($version);
        $first    = $versions['first'];
        $last     = $this->getCurrentStableVersion($versions['latest']);
        if (version_compare($version, $first, 'ge')
            && version_compare($version, $last, 'le')
        ) {
            return true;
        }
        return false;
    }
}
