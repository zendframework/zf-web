<?php
/**
 * Get the information from the AgileZen cache file
 * 
 * @author Enrico Zimuel (enrico@zend.com)
 */
class AgileZenModel
{
    protected $data;
    protected $tags;
    /**
     * Constructor
     * 
     * @param $filename string
     */
    public function __construct($filename)
    {
        if (empty($filename)) {
            throw new Exception('The AgileZen model needs a file name');
        }
        $this->data = unserialize(file_get_contents($filename));
    }
    /**
     * Get Data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * Get tags
     * 
     * @return array 
     */
    public function getTags()
    {
        if (empty($this->tags)) {
            $tags = array();
            foreach ($this->data['phases'] as $phase) {
                foreach ($phase['stories'] as $story) {
                    $tags = array_merge($tags, array_diff($story['tags'], $tags));
                }
            }
            sort($tags);
            $this->tags = $tags;
        }
        return $this->tags;
    }
    /**
     * Get project Id
     * 
     * @return integer 
     */
    public function getProjectId()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }
    /**
     * Get project name
     * 
     * @return string 
     */
    public function getProjectName()
    {
        if (isset($this->data['name'])) {
            return $this->data['name'];
        }
    }
    /**
     * Filter the data by tag
     * 
     * @param  string $tag
     * @return array 
     */
    public function filterByTag($tag) 
    {
        if (empty($tag)) {
            return $this->data;
        }
        $filter= $this->data;
        foreach ($filter['phases'] as &$phase) {
            $stories = array();
            foreach ($phase['stories'] as $story) {
                if (in_array($tag, $story['tags'])) {
                    $stories[]= $story;
                }
            }   
            $phase['stories'] = $stories;
        }
        return $filter;
    }
}
