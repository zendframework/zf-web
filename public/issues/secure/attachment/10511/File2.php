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
 * @package    Zend_Cache
 * @subpackage Backend
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Zend_Cache_Backend_Interface
 */
require_once 'Zend/Cache/Backend/Interface.php';

/**
 * Zend_Cache_Backend
 */
require_once 'Zend/Cache/Backend.php';


/**
 * @package    Zend_Cache
 * @subpackage Backend
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Cache_Backend_File2 extends Zend_Cache_Backend implements Zend_Cache_Backend_Interface 
{

	/**
	 * Available options
	 * 
	 * =====> (string) cache_dir :
	 * - Directory where to put the cache files
	 * 
	 * =====> (boolean) file_locking :
	 * - Enable / disable file_locking
	 * - Can avoid cache corruption under bad circumstances but it doesn't work on multithread
	 * webservers and on NFS filesystems for example
	 * 
	 * =====> (boolean) read_control :
	 * - Enable / disable read control
	 * - If enabled, a control key is embeded in cache file and this key is compared with the one
	 * calculated after the reading.
	 * 
	 * =====> (string) read_control_type :
	 * - Type of read control (only if read control is enabled). Available values are :
	 *   'md5' for a md5 hash control (best but slowest)
	 *   'crc32' for a crc32 hash control (lightly less safe but faster, better choice)
	 *   'strlen' for a length only test (fastest)
	 *   
	 * =====> (int) hashed_directory_level :
	 * - Hashed directory level
	 * - Set the hashed directory structure level. 0 means "no hashed directory 
	 * structure", 1 means "one level of directory", 2 means "two levels"... 
	 * This option can speed up the cache only when you have many thousands of 
	 * cache file. Only specific benchs can help you to choose the perfect value 
	 * for you. Maybe, 1 or 2 is a good start.
	 * 
	 * =====> (int) hashed_directory_umask :
	 * - Umask for hashed directory structure
	 * 
	 * =====> (string) file_name_prefix :
	 * - prefix for cache files 
	 * - be really carefull with this option because a too generic value in a system cache dir
	 *   (like /tmp) can cause disasters when cleaning the cache
	 * 
	 * @var array available options
	 */
	protected $_options = array(
		'cache_dir' => null,
		'file_locking' => true,
		'read_control' => true,
		'read_control_type' => 'crc32',
		'hashed_directory_level' => 0,
		'hashed_directory_umask' => 0700,
		'file_name_prefix' => 'zend_cache'
	);
	
	/**
	 * Array to buffer last tested cache id
	 * 
	 * @var array('id' => null|string, 'path' => null|string, 'exp' => null|int, 'exist' => boolean)
	 */
	private $_lastTestInfo = array(
		'id' => null,
		'path' => null,
		'exp' => null,
		'exist' => null,
	);
	
	/**
	 * Constructor
	 * 
	 * @param array $options associative array of options
	 */
	public function __construct($options = array())
	{   
		parent::__construct($options);
		if (!is_null($this->_options['cache_dir'])) { // particular case for this option
			$this->setCacheDir($this->_options['cache_dir']);
		} else {
			$this->_options['cache_dir'] = self::getTmpDir() . DIRECTORY_SEPARATOR;
		}
		if (isset($this->_options['file_name_prefix'])) { // particular case for this option
			// empty prefix is allowed, too
			if (!preg_match('#^[\w-]*?$#', $this->_options['file_name_prefix'])) {
				Zend_Cache::throwException('Invalid file_name_prefix : must use only [a-zA-Z0-9_]');
			}
		}
	}
	
	/**
	 * Set the cache_dir (particular case of setOption() method)
	 * 
	 * @param mixed $value
	 */
	public function setCacheDir($value)
	{
		// add a trailing DIRECTORY_SEPARATOR if necessary 
		$value = rtrim($value, '\\/') . DIRECTORY_SEPARATOR;
		$this->setOption('cache_dir', $value);
		$this->_cleanTestBuffer();
	}
    
	/**
	 * Test if a cache is available or not (for the given id)
	 * 
	 * @param string $id cache id
	 * @return mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
	 */
	public function test($id) {
		return $this->_test($id, false);
	}
	
	/**
	 * Save some string datas into a cache record
	 *
	 * Note : $data is always "string" (serialization is done by the 
	 * core not by the backend)
	 *
	 * @param string $data datas to cache
	 * @param string $id cache id
	 * @param array $tags array of strings, the cache record will be tagged by each string entry
	 * @param int $specificLifetime if != false, set a specific lifetime for this cache record (null => infinite lifetime)
	 * @return boolean true if no problem
	 */
	public function save($data, $id, $tags = array(), $specificLifetime = false)
	{
		if ((!is_dir($this->_options['cache_dir'])) || (!is_writable($this->_options['cache_dir']))) {
			$this->_log("Zend_Cache_Backend_File::save() : cache_dir doesn't exist or is not writable");
			return false;
		}
		
		// directory structure
		$path = $this->_path($id);
		if (!file_exists($path)) {
			if (!mkdir($path, $this->_options['hashed_directory_umask'], true)) {
				$this->_log('Zend_Cache_Backend_File::save() : Unable to create directory structure');
				return false;
			}
		}
		
		if ($this->_lastTestInfo['id'] == $id) {
			$this->_cleanTestBuffer();
		}

		// fwrite() Note that if the length argument is given, then the magic_quotes_runtime  configuration option will be ignored and no slashes will be stripped from string.
		
		// expire file
		$expPathname  = $path . $this->_filename($id, '.expire');
		if ( !($expFp=fopen($expPathname, 'wb')) ) {
			$this->_log('Zend_Cache_Backend_File::save() : Unable to open file "'.$expPathname.'"');
			return false;
		}
		$this->_flock($expFp, LOCK_EX);
		$expire = (string)$this->_expireTime($this->getLifetime($specificLifetime));
		if (!fwrite($expFp, $expire, strlen($expire))) {
			$this->_log('Zend_Cache_Backend_File::save() : Unable to write file "'.$expPathname.'"');
			$this->_flock($expFp, LOCK_UN);
			fclose($expFp);
			$this->_remove($expPathname);
			return false;
		} else {
			$this->_flock($expFp, LOCK_UN);
			fclose($expFp);
			chmod($expPathname, $this->_options['hashed_directory_umask']);
		}

		// data file (delete expire file on error)
		$dataPathname = $path . $this->_filename($id, '.data');
		if ( !($dataFp=fopen($dataPathname, 'wb')) ) {
			$this->_log('Zend_Cache_Backend_File::save() : Unable to open file "'.$dataPathname.'"');
			$this->_remove($expPathname);
			return false;
		}
		$this->_flock($dataFp, LOCK_EX);
		$dataStore = $this->_hash($data) . $data;
		if (!fwrite($dataFp, $dataStore, strlen($dataStore))) {
			$this->_log('Zend_Cache_Backend_File::save() : Unable to write file "'.$dataPathname.'"');
			$this->_remove($expPathname);
			$this->_flock($dataFp, LOCK_UN);
			fclose($dataFp);
			$this->_remove($dataPathname);
			return false;
		} else {
			$this->_flock($dataFp, LOCK_UN);
			fclose($dataFp);
			chmod($dataPathname, $this->_options['hashed_directory_umask']);
		}
		
		// tag file (delete expire file and data file on error)
		if (count($tags)) {
			$tagPathname = $path . $this->_filename($id, '.tag');
			if ( !($tagFp=fopen($tagPathname, 'wb')) ) {
				$this->_log('Zend_Cache_Backend_File::save() : Unable to open file "'.$tagPathname.'"');
				$this->_remove($expPathname);
				$this->_remove($dataPathname);
				return false;
			}
			$this->_flock($tagFp, LOCK_EX);
			$tagsStr = serialize($tags);
			if (!fwrite($tagFp, $tagsStr, strlen($tagsStr))) {
				$this->_log('Zend_Cache_Backend_File::save() : Unable to write file "'.$tagPathname.'"');
				$this->_remove($expPathname);
				$this->_remove($dataPathname);
				$this->_flock($tagFp, LOCK_UN);
				fclose($tagFp);
				$this->_remove($tagPathname);
				return false;
			} else {
				$this->_flock($tagFp, LOCK_UN);
				fclose($tagFp);
				chmod($tagPathname, $this->_options['hashed_directory_umask']);
			}
		}
		
		return true;
		
	}
	
	/**
	 * Test if a cache is available for the given id and (if yes) return it (false else)
	 * 
	 * @param string $id cache id
	 * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
	 * @return string cached datas (or false)
	 */
	public function load($id, $doNotTestCacheValidity = false) 
	{
		if ( !($this->_test($id, $doNotTestCacheValidity)) ) {
			return false;
		}
		$dataPathname = $this->_lastTestInfo['path'] . $this->_filename($id, '.data');
		
		// There is an available cache file !
		if ( (!$fp=fopen($dataPathname, 'rb')) ) {
			return false;
		}
		$this->_flock($fp, LOCK_SH);
		$length = filesize($dataPathname);
		$mqr = get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		if ($this->_options['read_control']) {
			$hash = fread($fp, 32);
			$length = $length - 32;
		}
		if ($length > 0) {
			$data = fread($fp, $length);
		} else {
			$data = '';
		}
		set_magic_quotes_runtime($mqr);
		$this->_flock($fp, LOCK_UN);
		fclose($fp);
		if (isset($hash) && $hash != $this->_hash($data)) {
			$this->_log('Zend_Cache_Backend_File::load() / read_control : stored hash and computed hash do not match');
			$this->remove($id);
			return false;
		}
			
		return $data;
	}
	
	/**
	 * Remove a cache record
	 * 
	 * @param string $id cache id
	 * @return boolean true if no problem
	 */
	public function remove($id) 
	{
		$result = true;
		
		if ($this->_lastTestInfo['id'] == $id) {
			$this->_cleanTestBuffer();
		}
		
		$path = $this->_path($id);
		$expPathname = $path . $this->_filename($id, '.expire');
		if (!file_exists($expPathname) || !$this->_remove($expPathname)) {
			$result = false;
		}
		$dataPathname = $path . $this->_filename($id, '.data');
		if (!file_exists($dataPathname) || !$this->_remove($dataPathname)) {
			$result = false;
		}
		$tagPathname = $path . $this->_filename($id, '.tag');
		// Tag file must not exist to return true
		if (file_exists($tagPathname) && !$this->_remove($tagPathname)) {
			$result = false;
		}
		$hashPathname = $path . $this->_filename($id, '.hash');
		// Hash file must not exist to return true
		if (file_exists($hashPathname) && !$this->_remove($hashPathname)) {
			$result = false;
		}
		
		return $result;
    }
	
	/**
	 * Clean some cache records
	 *
	 * Available modes are :
	 * 'all' (default)  => remove all cache entries ($tags is not used)
	 * 'old'            => remove too old cache entries ($tags is not used) 
	 * 'matchingTag'    => remove cache entries matching all given tags 
	 *                     ($tags can be an array of strings or a single string) 
	 * 'notMatchingTag' => remove cache entries not matching one of the given tags
	 *                     ($tags can be an array of strings or a single string)    
	 * 
	 * @param string $mode clean mode
	 * @param tags array $tags array of tags
	 * @return boolean true if no problem
	 */
	public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
	{
		$result = true;
		$tags = (array)$tags;
		$strlenPrefix = strlen($this->_options['file_name_prefix']);
		
		clearstatcache();
		$dir = new RecursiveDirectoryIterator($this->_options['cache_dir']);
		foreach (new RecursiveIteratorIterator($dir, true) as $file) {
			if ($file->isDir() /* || $file->isDot() */ ) {
				continue;
			}
			$filename = $file->getFilename();
			if (substr($filename, 0, $strlenPrefix) != $this->_options['file_name_prefix']) {
				continue;
			}
			
			if (substr($filename, -5) != '.data') {
				continue;
			}
			
			$id = substr($filename, $strlenPrefix, strrpos($filename, '.'));
			$path = rtrim($file->getPath(), '\\/') . DIRECTORY_SEPARATOR;
			
			if ($mode == Zend_Cache::CLEANING_MODE_ALL) {
				$this->_cleanTestBuffer();
			}
			switch ($mode) {
				case Zend_Cache::CLEANING_MODE_ALL:
					$result = $this->_remove($file->getPathname()) && $result;
					break;
				
				case Zend_Cache::CLEANING_MODE_OLD:
					if (substr($filename, -5) == '.data') {
						$expPathname = $path . $this->_filename($id, '.expire');
						if (!file_exists($expPathname)) {
							$result = $this->remove($id) && $result;
						} else {
							$expire = (int)file_get_contents($expPathname);
							if (time() > $expire) {
								$result = $this->remove($id) && $result;
							}
						}
					}
					break;
				
				case CLEANING_MODE_MATCHING_TAG:
					if (substr($filename,-4) == '.tag') {
						if ( !($fileTags=unserialize(file_get_contents($file->getPathname()))) ) {
							$result = false;
						} else {
							foreach ($fileTags as $tag) {
								if (in_array($tag, $tags)) {
									$result = $this->remove($id) && $result;
									break;
								}
							}
						}
					}
					break;
				
				case CLEANING_MODE_NOT_MATCHING_TAG:
					if (substr($filename,-4) == '.tag') {
						if ( !($fileTags=unserialize(file_get_contents($file->getPathname()))) ) {
							$result = false;
						} else {
							foreach ($fileTags as $tag) {
								if (!in_array($tag, $tags)) {
									$result = $this->remove($id) && $result;
									break;
								}
							}
						}
					}
					break;
			}
		}
		
		return $result;
	}

	/* private */ 
	
	private function _cleanTestBuffer() {
		$this->_lastTestInfo = array(
			'id' => null,
			'path' => null,
			'exp' => null,
			'exist' => null,
		);
	}
	
	public function _test($id, $doNotTestCacheValidity)
	{
		if ($id != $this->_lastTestInfo['id']) {
			clearstatcache();
			$path = $this->_path($id);
			$expPathname  = $path . $this->_filename($id, '.expire');
			$dataPathname = $path . $this->_filename($id, '.data');
			$this->_lastTestInfo = array(
				'id' => $id,
				'path' => $path,
				'exp' => null,
				'exist' => false,
			);
			
			if (!file_exists($expPathname) || !file_exists($dataPathname)) {
				return false;
			} else {
				$this->_lastTestInfo['exist'] = true;
			}
			
			$expire = (int)file_get_contents($expPathname);
			$this->_lastTestInfo['exp'] = (int)file_get_contents($expPathname);
			
		} elseif ( !$this->_lastTestInfo['exist'] ) {
			return false;
		} elseif ( !$this->_lastTestInfo['exp'] ) {
			return false;
		}
		
		if ($doNotTestCacheValidity === true) {
			return $this->_lastTestInfo['exp'];
		}
		
		if (time() <= $this->_lastTestInfo['exp']) {
			if (!isset($this->_lastTestInfo['mtime'])) {
				$this->_lastTestInfo['mtime'] = filemtime($this->_lastTestInfo['path'] . $this->_filename($id, '.expire'));
			}
			return $this->_lastTestInfo['mtime'];
		}
		return false;
	}
	
	/**
	 * Return the complete directory path of a filename (including hashedDirectoryStructure)
	 * 
	 * @param string $id cache id
	 * @return string complete directory path
	 */
	private function _path($id)
	{
		$root = $this->_options['cache_dir'];
		$prefix = $this->_options['file_name_prefix'];
		if ($this->_options['hashed_directory_level']>0) {
			$hash = md5($id);
			for ($i=0 ; $i<$this->_options['hashed_directory_level'] ; $i++) {
				$root = $root . $prefix . substr($hash, 0, $i + 1) . DIRECTORY_SEPARATOR;
			}
		}
		return $root;
	}
	
    /**
     * Transform a cache id into a file name and return it
     * 
     * @param string $id cache id
     * @param string $ext file extension
     * @return string file name
     */
    private function _filename($id, $ext)
    {
        $prefix = $this->_options['file_name_prefix'];
        return $prefix . $id . $ext;
    }
	
    private function _pathname($id, $ext) {
    	return $this->_path($id) . $this->_filename($id, $ext);
    }
    
	/**
	 * Remove a file
	 * 
	 * If we can't remove the file (because of locks or any problem), we will touch 
	 * the file to invalidate it
	 * 
	 * @param string $file complete file path
	 * @return boolean true if ok
	 */  
	private function _remove($file)
	{
		if (!unlink($file)) {
			# we can't remove the file (because of locks or any problem)
			$this->_log("Zend_Cache_Backend_File::_remove() : we can't remove $file => we are going to try to invalidate it");
			return false;
		}
		return true;
	}
    
	/**
	 * Make a control key with the string containing datas
	 *
	 * @param string $data data
	 * @param string $controlType type of control 'md5', 'crc32' or 'strlen'
	 * @return string control key
	 */
	private function _hash(&$data)
	{
		if ($this->_options['read_control']) {
			switch ($this->_options['read_control_type']) {
				case 'md5':
					return md5($data);
				case 'crc32':
					return sprintf('% 32d', crc32($data));
				case 'strlen':
					return sprintf('% 32d', strlen($data));
				default:
					Zend_Cache::throwException("Incorrect hash function : $controlType");
			}
		}
		return null;
	}
    
	/**
	 * Compute & return the expire time
	 * 
	 * @return int expire time (unix timestamp)
	 */
	private function _expireTime($lifetime) 
	{
		if (is_null($this->_directives['lifetime'])) {
			return 9999999999;
		}
		return time() + $lifetime;
	}
	
	/**
	 * Lock/Unlock an given file recource if option "file_locking" is enabled
	 *
	 * @param recource $fp
	 * @param int $mode
	 * @return boolean
	 */
	private function _flock($fp, $mode)
	{
		if ($this->_options['file_locking']) {
			return flock($fp, $mode);
		}
		return true;
	}
	
}
