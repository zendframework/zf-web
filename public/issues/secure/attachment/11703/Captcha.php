<?php
abstract class Zend_Captcha {
	
	
	/**
	 * Standard adapters
	 *
	 * @var array
	 */
	public static $standardAdapters = array ('Word', 'Dumb', 'Figlet', 'Image', 'ReCaptcha' );
	
	
	/**
	 * Factory
	 *
	 * @param mixed	$adapter		frontend name (string) or Zend_Captcha_ object
	 * @param array	$options		associative array of options for the corresponding adapter constructor
	 * 
	 * @throws Exception
	 * @return Zend_Captcha_Base
	 */
	public static function factory($adapter, $options = array()) {
		if (is_string ( $adapter )) {
			$adapterObject = self::_makeAdapter ( $adapter, $options );
		} else {
			if (is_object ( $adapter )) {
				$adapterObject = $adapter;
			} else {
				self::throwException ( 'frontend must be a frontend name (string) or an object' );
			}
		}
		
		return $adapterObject;
	}
	
	
	/**
	 * Backend Constructor
	 *
	 * @param string  $adapter
	 * @param array   $options
	 * @return Zend_Captcha_Base
	 */
	public static function _makeAdapter($adapter, $options = array()) {
		if (in_array ( $adapter, self::$standardAdapters )) {
			// we use a standard frontend
			// For perfs reasons, with frontend == 'Core', we can interact with the Core itself
			$adapterClass = 'Zend_Captcha_' . $adapter;
			// security controls are explicit
			require_once str_replace ( '_', DIRECTORY_SEPARATOR, $adapterClass ) . '.php';
		} else {
			// we use a custom frontend
			if (! preg_match ( '~^[\w]+$~D', $adapter )) {
				Zend_Cache::throwException ( "Invalid frontend name [$adapter]" );
			}
			
			$adapterClass = $adapter;
			$file = str_replace ( '_', DIRECTORY_SEPARATOR, $adapterClass ) . '.php';
			if (! (self::_isReadable ( $file ))) {
				self::throwException ( "file $file not found in include_path" );
			}
			
			require_once $file;
		}
		
		return new $adapterClass ( $options );
	}
	
	
	/**
	 * Throw an exception
	 *
	 * @param  string $msg  Message for the exception
	 * @throws Exception
	 */
	public static function throwException($msg) {
		// For perfs reasons, we use this dynamic inclusion
		throw new Exception ( $msg );
	}
	
	
	/**
	 * Returns TRUE if the $filename is readable, or FALSE otherwise.
	 * This function uses the PHP include_path, where PHP's is_readable()
	 * does not.
	 *
	 * Note : this method comes from Zend_Loader (see #ZF-2891 for details)
	 *
	 * @param string   $filename
	 * @return boolean
	 */
	private static function _isReadable($filename) {
		if (! $fh = @fopen ( $filename, 'r', true )) {
			return false;
		}
		
		@fclose ( $fh );
		return true;
	}
}