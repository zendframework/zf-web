<?php

/**
 * Read the contributors data from the .ini files
 */
class ContributorsModel
{
    /**
     * Directory where the contributors data files are stored
     * @var string
     */
    protected $_directory;


    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->_directory = dirname(__FILE__) . '/Contributors';
    }



    /**
     * Return an array with the core contributors.
     *
     * @return array
     */
    public function getContributors()
    {
        return $this->_parseModelFile($this->_directory . '/contributors.ini');
    }


    /**
     * Return an array with others to thank.
     *
     * @return array
     */
    public function getSpecialThanks()
    {
        return $this->_parseModelFile($this->_directory . '/special_thanks.ini');
    }


    /**
     * Return an array of objects, one for each contributor
     *
     * @return array
     */
    protected function _parseModelFile($filename)
    {
        $ini = @parse_ini_file($filename, true);

        if ($ini === false) {
            return array();
        }

        // alphabetize names
        ksort($ini);

        $contributors = array();
        foreach ($ini as $name => $details) {
            if (strpos($name, ',')) {
                list($surname, $givenName) = explode(',', $name, 2);
                $name = trim($givenName) .' '. trim($surname);
            }

            $photo   = isset($details['photo'])   ? $details['photo'] : 'no_photo.gif';
            $company = isset($details['company']) ? $details['company'] : '';

            $contributors[] = (object)array('name'    => $name,
                                            'photo'   => $photo,
                                            'company' => $company);
        }

        return $contributors;
    }
}
