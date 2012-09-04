<?php

namespace Security\Model;

use InvalidArgumentException;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter as ArrayPaginator;

/**
 * AdvisoryModel 
 */
class AdvisoryModel
{
    protected $advisories;
    protected $pageSize;
    protected $paginator;

    public function __construct($advisories, $pageSize = 10)
    {
        if ($advisories instanceof Traversable) {
            $advisories = iterator_to_array($advisories);
        }
        if (!is_array($advisories)) {
            throw new InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($advisories) ? get_class($advisories) : gettype($advisories))
            ));
        }
        $this->advisories = $advisories;
        $this->sortAdvisories();

        if (!is_numeric($pageSize) || $pageSize < 1) {
            throw new InvalidArgumentException(sprintf(
                '%s expects an integer greater than 0 for the $pageSize argument; received "%s"',
                __METHOD__,
                (string) $pageSize
            ));
        }
        $this->pageSize = (int) $pageSize;
    }

    /**
     * Retrieve advisories.
     *
     * Returns a paginator with advisories sorted newest to oldest.
     * 
     * @return Paginator
     */
    public function getAdvisories()
    {
        if ($this->paginator instanceof Paginator) {
            return $this->paginator;
        }

        $paginator = new Paginator(new ArrayPaginator($this->advisories));
        $paginator->setItemCountPerPage($this->pageSize);
        $this->paginator = $paginator;

        return $paginator;
    }

    /**
     * Does a given advisory exist?
     * 
     * @param  string $advisory 
     * @return bool
     */
    public function advisoryExists($advisory)
    {
        return isset($this->advisories[$advisory]);
    }

    /**
     * Retrieve information on a given advisory
     * 
     * @param  string $advisory 
     * @return array
     */
    public function getAdvisoryInfo($advisory)
    {
        if (!$this->advisoryExists($advisory)) {
            throw new InvalidArgumentException(sprintf(
                '%s cannot fetch advisory "%s"; does not exist',
                __METHOD__,
                $advisory
            ));
        }
        $info = $this->advisories[$advisory];
        $info['id'] = $advisory;
        return $info;
    }

    /**
     * Sort the advisories so newest are first
     */
    protected function sortAdvisories()
    {
        uksort($this->advisories, function ($a, $b) {
            if ($a === $b) {
                return 0;
            }
            if ($a > $b) {
                return -1;
            }
            return 1;
        });
    }
}
