<?php

/**
 * Read the FAQ data from the .qa.txt files
 */
class FaqModel
{
    /**
     * Directory where the FAQ data files are stored
     * @var string
     */
    protected $_directory;


    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->_directory = dirname(__FILE__) . '/Faq';
    }


    /**
     * Return an array with the names of each FAQ section
     *
     * @return array
     */
    public function getSections()
    {
        $sections = array();

        $dir = new DirectoryIterator($this->_directory);
        foreach ($dir as $file) {
            if (preg_match('/(.*)\.qa\.txt/', $file->getFilename(), $patterns)) {
                $sections[] = $patterns[1];
            }
        }

        return $sections;
    }


    /**
     * Return an array of questions=>answers for an FAQ section
     * or array() on error.
     *
     * @param string $section
     * @return array
     */
    public function getFaq($section)
    {
        $section = ucfirst(strtolower($section));
        $file = @file_get_contents("$this->_directory/$section.qa.txt", true);

        if ($file===false) {
            return array();
        }

        $questions = array();
        foreach (explode('Q:', $file) as $qa) {
            if (strpos($qa, 'A:')) {
                list($q, $a) = explode('A:', $qa);
                $questions[trim($q)] = trim($a);
            }
        }

        return $questions;
    }

}