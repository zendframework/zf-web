<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Ini.php 8741 2008-03-10 21:55:52Z rob $
 */


/**
 * @see Zend_Config
 */
require_once 'Zend/Config.php';


/**
 * @category   Zend
 * @package    Zend_Config
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Config_Ini extends Zend_Config
{
    /**
     * String that separates nesting levels of configuration data identifiers
     *
     * @var string
     */
    protected $_nestSeparator = '.';

    /**
     * Loads the section $section from the config file $filename for
     * access facilitated by nested object properties.
     *
     * If the section name contains a ":" then the section name to the right
     * is loaded and included into the properties. Note that the keys in
     * this $section will override any keys of the same name in the sections 
     * that have been included via ":".
     *
     * If the $section is null, then all sections in the ini file are loaded.
     *
     * If any key includes a ".", then this will act as a separator to create 
     * a sub-property.
     *
     * example ini file:
     *      [all]
     *      db.connection = database
     *      hostname = live
     *
     *      [staging : all]
     *      hostname = staging
     *
     * after calling $data = new Zend_Config_Ini($file, 'staging'); then
     *      $data->hostname === "staging"
     *      $data->db->connection === "database"
     *
     * The $options parameter may be provided as either a boolean or an array.
     * If provided as a boolean, this sets the $allowModifications option of
     * Zend_Config. If provided as an array, there are two configuration
     * directives that may be set. For example:
     *
     * $options = array(
     *     'allowModifications' => false,
     *     'nestSeparator'      => '->'
     *      );
     *
     * @param  string        $filename
     * @param  string|null   $section
     * @param  boolean|array $options
     * @throws Zend_Config_Exception
     */
    public function __construct($filename, $section = null, $options = false)
    {
        if (empty($filename)) {
            /** @see Zend_Config_Exception */
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception('Filename is not set');
        }

        // process $options
        $allowModifications = false;
        if (is_bool($options)) {
            $allowModifications = $options;
        } elseif (is_array($options)) {
            if (isset($options['allowModifications'])) {
                $allowModifications = (bool) $options['allowModifications'];
            }
            if (isset($options['nestSeparator'])) {
                $this->_nestSeparator = (string) $options['nestSeparator'];
            }
        }

        $iniArray = $this->_loadIniFile($filename);

        if (null === $section) {
            // Load entire file
            $config = $this->_loadAllSections($iniArray);
        } elseif (is_array($section)) {
            // Load multiple sections
            $config = array();
            foreach ($section as $sectionName) {
                $config = array_merge($this->_loadSection($iniArray, $sectionName), $config);
            }
        } else {
            // Load single section
            $config = $this->_loadSection($iniArray, $section);
        }
        parent::__construct($config, $allowModifications);
        
        // Set _loadedSection after calling parent's constructor as it
        // will initialize it to null
        $this->_loadedSection = $section;

    }

    /**
     * Load the ini file and preprocess the : in the section name (that is used 
     * for section extension) so that the resultant array has the correct 
     * section names and the extension information is stored in a sub-key 
     * called ';extends'. We use ';extends' as this can never be a valid key 
     * name in an INI file that has been loaded.
     *
     * @param string $filename
     * @return array
     */
    protected function _loadIniFile($filename)
    {
        $loaded = parse_ini_file($filename, true);
        
        $iniArray = array();
        foreach ($loaded as $key => $data)
        {
            $bits = explode(':', $key);
            $thisSection = trim($bits[0]);
            switch (count($bits)) {
                case 1:
                    $iniArray[$thisSection] = $data;
                    break;

                case 2:
                    $extendedSection = trim($bits[1]);
                    $iniArray[$thisSection] = array_merge(array(';extends'=>$extendedSection), $data);
                    break;

                default:
                    /** @see Zend_Config_Exception */
                    require_once 'Zend/Config/Exception.php';
                    throw new Zend_Config_Exception("Section '$thisSection' may not extend multiple sections in $filename");
            }
        } 
        
        return $iniArray;
    }
    
    
    /**
     * Load all sections from the INI file. 
     * 
     * @note: This means that we may have some keys that are not part of a 
     * section but do use inheritence, so we use _processKey() for them. However
     * _processKey() needs to be seeded with a config array for the section. As
     * there is no section, we seed with an empty array.
     *
     * @param array $iniArray
     * @return array
     */
    protected function _loadAllSections($iniArray)
    {
        $config = array();
        foreach ($iniArray as $sectionName => $sectionData) {
            if(!is_array($sectionData)) {
                // This is a top level key without a section
                $config = array_merge_recursive($config, $this->_processKey(array(), $sectionName, $sectionData));
            } else {
                // This is a top level section with sub-keys
                $config[$sectionName] = $this->_processSection($iniArray, $sectionName);
            }
        }
        return $config;
    }
    
    
    /**
     * Load a single section from the INI file.
     *
     * @param array $iniArray
     * @param string $section
     * @return array
     */
    protected function _loadSection($iniArray, $section)
    {
        $config = array();
        if (!isset($iniArray[$section])) {
            /** @see Zend_Config_Exception */
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception("Section '$section' cannot be found in INI file.");
        }
        $config = $this->_processSection($iniArray, $section);
        return $config;
    }
    
    
    /**
     * Process each element in the section and handle the ";extends" inheritance 
     * key. Passes control to _processKey() to handle the "dot" sub-property 
     * syntax that may be used within the key name.
     *
     * @param array $iniArray
     * @param string $section
     * @param array $config
     * @throws Zend_Config_Exception
     * @return array
     */
    protected function _processSection($iniArray, $section, $config = array())
    {
        $thisSection = $iniArray[$section];

        foreach ($thisSection as $key => $value) {
            if (strtolower($key) == ';extends') {
                if (isset($iniArray[$value])) {
                    $this->_assertValidExtend($section, $value);
                    $config = $this->_processSection($iniArray, $value, $config);
                } else {
                    /** @see Zend_Config_Exception */
                    require_once 'Zend/Config/Exception.php';
                    throw new Zend_Config_Exception("Parent section '$section' cannot be found");
                }
            } else {
                $config = $this->_processKey($config, $key, $value);
            }
        }
        return $config;
    }

    /**
     * Assign the key's value to the property list. Handle the "dot"
     * notation for sub-properties by passing control to
     * processLevelsInKey().
     *
     * @param array $config
     * @param string $key
     * @param string $value
     * @throws Zend_Config_Exception
     * @return array
     */
    protected function _processKey($config, $key, $value)
    {
        if (strpos($key, $this->_nestSeparator) !== false) {
            $pieces = explode($this->_nestSeparator, $key, 2);
            if (strlen($pieces[0]) && strlen($pieces[1])) {
                if (!isset($config[$pieces[0]])) {
                    $config[$pieces[0]] = array();
                } elseif (!is_array($config[$pieces[0]])) {
                    /** @see Zend_Config_Exception */
                    require_once 'Zend/Config/Exception.php';
                    throw new Zend_Config_Exception("Cannot create sub-key for '{$pieces[0]}' as key already exists");
                }
                $config[$pieces[0]] = $this->_processKey($config[$pieces[0]], $pieces[1], $value);
            } else {
                /** @see Zend_Config_Exception */
                require_once 'Zend/Config/Exception.php';
                throw new Zend_Config_Exception("Invalid key '$key'");
            }
        } else {
            $config[$key] = $value;
        }
        return $config;
    }

}
