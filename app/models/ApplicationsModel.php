<?php

class ApplicationsModel
{
    const FILE = 'applications.xml';

    /**
     * Directory where the applications data are stored
     *
     * @var string
     */
    protected $_directory;

    /**
     * Sets the model directory where applications data are stored
     *
     * @return void
     */
    public function __construct()
    {
        $this->_directory = dirname(__FILE__) . '/Applications';
    }

    /**
     * Returns the application data represented as a SimpleXMLElement, or false on failure
     *
     * @return SimpleXMLElement|boolean
     */
    public function getApplications()
    {
        return $this->_parseApplicationsFile($this->_directory . DIRECTORY_SEPARATOR . self::FILE);
    }

    /**
     * Returns application data stored in the model file
     *
     * @param  string $filename
     * @return SimpleXMLElement
     * @throws Exception
     */
    protected function _parseApplicationsFile($filename)
    {
        set_error_handler(array($this, '_errorHandler'));
        $applications = simplexml_load_file($filename);
        restore_error_handler();
        return $applications;
    }

    /**
     * Throws an Exception whose "error" property contains the error
     *
     * @param  int    $errno
     * @param  string $errstr
     * @param  string $errfile
     * @param  int    $errline
     * @param  array  $errcontext
     * @throws Exception
     */
    protected function _errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $e = new Exception($errstr);

        $e->error = array(
            'errno'      => $errno,
            'errstr'     => $errstr,
            'errfile'    => $errfile,
            'errline'    => $errline,
            'errcontext' => $errcontext
            );

        throw $e;
    }
}
