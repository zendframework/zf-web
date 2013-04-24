<?php
/**
 * Creating a Zend_Config with a passed ini string.
 *
 * @author Romeo Disca
 */
class Jbc_Config_String extends Zend_Config_Ini
{

    /**
     * Load the INI file from disk using parse_ini_file(). Use a private error
     * handler to convert any loading errors into a Zend_Config_Exception
     *
     * @param string $filename
     * @throws Zend_Config_Exception
     * @return array
     */
    protected function _parseIniFile($filename)
    {
        set_error_handler(array($this, '_loadFileErrorHandler'));
        $iniArray = parse_ini_string($filename, true); // Warnings and errors are suppressed
        restore_error_handler();

        // Check if there was a error while loading file
        if ($this->_loadFileErrorStr !== null) {
            /**
             * @see Zend_Config_Exception
             */
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception($this->_loadFileErrorStr);
        }

        return $iniArray;
    }

}
