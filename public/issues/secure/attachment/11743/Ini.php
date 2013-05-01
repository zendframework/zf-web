<?php

require_once( 'Zend/Config.php' );

class Puli_Config_Ini extends Zend_Config
{
	/**
	 * String that separates nesting levels of configuration data identifiers
	 *
	 * @var string
	 */
	protected $_nestSeparator = '.';
	
	protected $_configIni;
	
	protected $_iniCache = array();

	/**
	* Loads the section $section from the config file $filename for
	* access facilitated by nested object properties.
	*
	* If the section name contains a ":" then the section name to the right
	* is loaded and included into the properties. Note that the keys in
	* this $section will override any keys of the same
	* name in the sections that have been included via ":".
	*
	* If the $section is null, then all sections in the ini file are loaded.
	*
	* If any key includes a ".", then this will act as a separator to
	* create a sub-property.
	*
	* example ini file:
	*      [all]
	*      db.connection = database
	*      hostname = "live"
	*
	*      [staging:all]
	*      hostname = "staging"
	*
	* You can use the '|' to specify a section from an extended ini file
	*
	* another ini file ( extend.ini ):
	*      [fooExtend]
	*      hostname = "extendedValue"
	*
	*      [foo:c:/path/to/file/extend.ini|fooExtend]
	*      hostname = "staging"
	*
	* !!! The value must be quoted !!!!
	*  
	* You can concat evaluated elements as a value with the {E:} block. 
	* Example: 
	*
	* [foo:extend{E:fooClass::getUsername()}]
	* will be the same:
	* [foo:extendTestUser]
	*
	*
	* or
	* [foo:c:/{E:fooClass::getFilePath()}/extend.ini|fooExtend]
	* will be the same:
	* [foo:c:/path/to/file/extend.ini|fooExtend]
	*
	*
	* or
	* [foo]
	* file.path = "c:/{E:fooClass::getFilePath()}"
	* will be the same:
	* [foo]
	* file.path = "c:/path/to/file"
	*
	*
	* or
	* [Db]
	* host.name = "FooName"
	*
	* [foo:extend{Db.host.name}]
	* will be the same:
	* [foo:extendFooName]
	*
	*
	* or
	* 
	* [Logger]
	* file.path = "c:/path/to/file"
	*
	* [foo:{Logger.file.path}/log.ini|Log]
	* will be the same:
	* [foo:c:/path/to/file/log.ini|Log]
	*
	*
	* or
	* 
	* [Logger]
	* file.path = "c:/path/to/file"
	*
	* [Log]
	* file = "{Logger.file.path}/debug.log"
	* will be the same:
	* [Log]
	* file = "c:/path/to/file/debug.log"
	*
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
	* @return void
	*/
	public function __construct( $filename, $section = null, $options = false )
	{
		$allowModifications = false;
		
		if (is_bool($options))
		{
			$allowModifications = $options;
		}
		elseif (is_array($options))
		{
			if (isset($options['allowModifications']))
			{
				$allowModifications   = (bool) $options['allowModifications'];
			}
			if (isset($options['nestSeparator']))
			{
				$this->_nestSeparator = (string) $options['nestSeparator'];
			}
		}
		
		$this->_configIni = $this->parseIni( $filename, true);
		
		$this->setupIniArray( $this->_configIni );
		
		$this->putFileToCache( $filename, $this->_configIni );
		
		foreach ( $this->_configIni as $sectionName => $content )
		{
			$this->_configIni = $this->mergeExtends( $sectionName, $this->_configIni );				
		}
		
		$preProcessedArray = $this->_configIni;
		
		if ( is_null($section) )
		{
			$dataArray = array();
			foreach ( $preProcessedArray as $sectionName => $sectionData )
			{
				$dataArray[$sectionName] = array();
				foreach ( $sectionData as $name => $value )
				{
					$dataArray[$sectionName] = $this->_processKey( $name, $value, $dataArray[$sectionName] );
				}
				
			}
		}
		elseif (is_array($section))
		{
			$dataArray = array();
			foreach ($section as $sectionName)
			{
				if ( !isset( $preProcessedArray[$sectionName] ) )
				{
					throw new Zend_Config_Exception("Section '$sectionName' cannot be found in $filename");
				}
				$dataArray[$sectionName] = array();
				foreach ( $preProcessedArray[$sectionName] as $name => $value )
				{
					$dataArray[$sectionName] = $this->_processKey( $name, $value, $dataArray[$sectionName] );
				}
			}
		}
		else
		{
			if (!isset($preProcessedArray[$section]))
			{
				require_once 'Zend/Config/Exception.php';
				throw new Zend_Config_Exception("Section '$section' cannot be found in $filename");
			}
			$dataArray[$section] = array();
			foreach ( $preProcessedArray[$section] as $name => $value )
			{
				$dataArray[$section] = $this->_processKey( $name, $value, $dataArray[$sectionName] );
			}
		}
		parent::__construct($dataArray, $allowModifications);
		
		$this->_loadedSection = $section;
	}
	
	/**
	 * Setup the original section name where we have inharitance
	 *
	 * @param array $iniArray
	 * @return void
	 */
	protected function setupIniArray( &$iniArray )
	{
		foreach ( $iniArray as $section => $content )
		{
			if ( strpos( $section, ':' ) !== false )
			{
				$thisSection                        = substr( $section, 0, strpos( $section, ':' ) );
				$extendedSection                    = substr( $section, ( strpos( $section, ':' ) + 1 ) );
				$iniArray[$thisSection]             = array_merge( $iniArray[$section], $iniArray[$section] );
				$iniArray[$thisSection][';extends'] = $extendedSection;
				unset( $iniArray[$section] );
			}
		}
	}
	
	/**
	 * Merge all the extended sections
	 *
	 * @param string $section
	 * @param array $iniArray
	 * @throws Zend_Config_Exception
	 * @return array
	 */
	protected function mergeExtends( $section, $iniArray )
	{
		$sectionContent = $iniArray[$section];
		if ( isset($sectionContent[';extends']) )
		{
			if( stripos( $sectionContent[';extends'], "|" ) !== false )
			{
				$bits = explode( '|', $sectionContent[';extends']);
				
				$filename     = $bits[0];
				$extendSetion = $bits[1];
				
				if( stripos( $filename, "{" ) !== false )
				{
					$filename = $this->evaluateString( $filename, $iniArray );
				}
				if( stripos( $extendSetion, "{" ) !== false )
				{
					$extendSetion = $this->evaluateString( $bits[1], $iniArray );
				}
				if ( file_exists($filename) )
				{
					if ( !$extendIni = $this->getFileFromCache( $filename ) )
					{
						$extendIni = $this->parseIni( $filename, true);
						$this->setupIniArray( $extendIni );
					}
					if ( isset( $extendIni[$extendSetion][';extends'] ) )
					{
						$extendIni = $this->mergeExtends( $extendSetion, $extendIni );
					}
					if ( !isset( $extendIni[$extendSetion] ) )
					{
						throw new Zend_Config_Exception( 'Can\'t find the section: ' . $extendSetion );
					}
					$iniArray[$section] = array_merge( $iniArray[$section], $extendIni[$extendSetion] );
					unset($iniArray[$section][';extends']);
					unset($extendIni[$extendSetion][';extends']);
					$this->putFileToCache( $filename, $extendIni );
				}
				else
				{
					throw new Zend_Config_Exception( 'Extended ini file doesn\'t exists: ' . $filename );
				}
			}
			else
			{
				if( stripos( $sectionContent[';extends'], "{" ) !== false )
				{
					$sectionContent[';extends'] = $this->evaluateString( $sectionContent[';extends'], $iniArray );
				}
				if ( isset($iniArray[$sectionContent[';extends']][';extends']) )
				{
					$iniArray = $this->mergeExtends( $sectionContent[';extends'], $iniArray );
				}
				if ( !isset( $iniArray[$sectionContent[';extends']] ) )
				{
					throw new Zend_Config_Exception( 'Can\'t find the section: ' . $sectionContent[';extends'] );
				}
				$iniArray[$section] = array_merge( $iniArray[$section], $iniArray[$sectionContent[';extends']] );
				unset($iniArray[$section][';extends']);
			}	
		}
		return $iniArray;
	}
	
	/**
	 * Evaluate the given string
	 *
	 * @param string $string
	 * @param array $iniArray
	 * @throws Zend_Config_Exception
	 * @return string
	 */
	protected function evaluateString( $string, &$iniArray )
	{
		if( stripos( $string, "{" ) !== false )
		{
			$string      = trim($string);
			$preString   = substr($string, 0, strpos($string, '{') );
			$postString  = substr($string, ( strpos($string, '}') + 1 ) );
			$valueString = substr($string, ( strpos($string, '{') + 1 ), ( strpos($string, '}') - ( strpos($string, '{') + 1 ) ) );
			if ( strpos( $valueString, 'E:') === 0 )
			{
				if ( strlen($preString) == 0 && strlen($postString) == 0 )
				{
					return eval( 'return ' . substr($valueString,2) . ';' );
				}
				else
				{
					$return = eval( 'return ' . substr($valueString,2) . ';' );
					if ( is_string($return) )
					{
						$string = $preString . $return . $postString;
					}
					else
					{
						throw new Zend_Config_Exception( 'Can\'t concatenate an object with string' );
					}
				}
			}
			elseif( count( explode( $this->_nestSeparator, $valueString, 2) ) > 1 )
			{
				$path = explode( $this->_nestSeparator, $valueString, 2);
				$section = $path[0];
				$path = $path[1];
				if ( isset($this->_configIni[$section][$path]) )
				{
					$string = $preString . $this->_configIni[$section][$path] . $postString;
				}
				else
				{
					throw new Zend_Config_Exception( 'Can\'t find path:' . $path . ' in ' . $section );
				}
			}
			else
			{
				throw new Zend_Config_Exception( 'Invalid evaluated string: ' . $valueString );
			}
			if( stripos( $string, "{" ) !== false )
			{
				$string = $this->evaluateString( $string, $iniArray );
			}
		}
		return $string;
	}
	
	/**
	 * Get the content of the ini file from cache
	 *
	 * @param string $filename
	 * @param array $iniContent
	 * @return void
	 */
	protected function putFileToCache( $filename, $iniContent )
	{
		$name = md5_file( $filename );
		$this->_iniCache[$name] = $iniContent;
	}
	
	/**
	 * Get the content of the ini file from cache
	 *
	 * @param string $filename
	 * @return boolean|array
	 */
	protected function getFileFromCache( $filename )
	{
		$name = md5_file( $filename );
		if ( isset( $this->_iniCache[$name] ) )
		{
			return $this->_iniCache[$name];
		}
		return false;
	}
	
	/**
	 * Parse the given ini file
	 *
	 * @param string $filename
	 * @throws Zend_Config_Exception
	 * @return array
	 */
	protected function parseIni($filename)
	{
		if (empty($filename))
		{
			throw new Zend_Config_Exception("Filename is not set!");
		}

		if (!is_file($filename))
		{
			throw new Zend_Config_Exception("Can't find $filename!");
		}
		return parse_ini_file($filename, true);
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
	protected function _processKey( $key, $value, $config )
	{
		if ( strpos($key, $this->_nestSeparator) !== false )
		{
			
			$pieces = explode($this->_nestSeparator, $key, 2);
			if (strlen($pieces[0]) && strlen($pieces[1]))
			{
				if (!isset($config[$pieces[0]]))
				{
					$config[$pieces[0]] = array();
				}
				elseif (!is_array($config[$pieces[0]]))
				{
					throw new Zend_Config_Exception("Cannot create sub-key for '{$pieces[0]}' as key already exists");
				}
				$config[$pieces[0]] = $this->_processKey( $pieces[1], $value, $config[$pieces[0]] ); 
			}
		}
		else
		{
			if( stripos( $value, "{" ) !== false || stripos( $value, "{" ) !== false )
			{
				if ( substr_count($value, '{') != substr_count($value, '}') )
				{
					throw new Zend_Config_Exception("Parse error in: " . $value );
				}
				$value = $this->evaluateString( $value, $this->_configIni );
			}
			elseif ( is_numeric( $value ) )
			{
				if ( $value == intval( $value ) )
				{
					$value = (int) $value;
				}
				else
				{
					$value = (float) $value;
				}
			}
			else
			{
				switch ($value)
				{
					case 'false':
						$value = false;
						break;
					case 'true':
						$value = true;
						break;
					default:
						$value = $value;
						break;
				}
			}
			$config[$key] = $value;
		}
		
		return $config;
	}
	
}
