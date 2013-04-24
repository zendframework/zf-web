<?php

class Zend_Loader_PluginLoader_PathCache
{
	/**
	 * Stores array of each cache hit and miss
	 *
	 * @var Array
	 */
	protected $_cacheLog 	= array();
	
	protected $_cache;
	
	protected static $_instance;
	protected static $_cacheIdentifier = 'Zend_Loader_PluginLoader_PathCache';
	
	/**
	 * Singleton instance of current class
	 */
	public static function getInstance($cache)
	{
		if (!self::$_instance)
		{
			$pathCache = $cache->load(self::$_cacheIdentifier);	

			if ($pathCache)
			{
				self::$_instance = $pathCache;
			}
			else
			{
				self::$_instance = new Zend_Loader_PluginLoader_PathCache($cache);
				$cache->save(self::$_instance);
			}
		}
		
		return self::$_instance;
	}
	
	public function __construct($cache)
	{
		$this->_cache = $cache;
	}
	
	/**
	 * Get a plugin from the cache pool, if available, otherwise return false
	 *
	 * @param String $pluginName
	 * @param Array $usePaths
	 */
	public function getPluginPath($pluginName, $usePaths)
	{
		//var_dump($usePaths);
		foreach ($usePaths as $prefix => $paths)
		{
			
			# If we don't have this in our cache log then we obviously do not have a log of a cache hit, so return
			if (!isset($this->_cacheLog[$prefix]))
			{
				return null;
			}
			
			$paths = array_reverse($paths, true);
			foreach ($paths as $path)
			{
				$pathHash = md5($path);
				# Do we have a record of this prefix, plugin, path combo?
				if (isset($this->_cacheLog[$prefix][$pluginName][$pathHash]))
				{
					# We have a record of this combo, however is it true (plugin exists) or false?
					if  ($this->_cacheLog[$prefix][$pluginName][$pathHash] === true)
					{
						return array($prefix => array($path));
					}
					
					# Don't return here as we did have information for this combo, it just doesn't exist at this location
				}
				elseif(isset($this->_cacheLog[$prefix][$pluginName][md5('')]))
				{
					return (array($prefix => $paths));
				}
				else
				{
					# As we do not have any information on this path then we must assume it exists we don't know about it
					# so return null and let the plugin loader decide
					return null;
				}
			}
		}
	}
	
	/**
	 * Log a cache miss
	 *
	 * @param String $name
	 * @param String $prefix
	 * @param String $path
	 */
	public function logCacheMiss($name, $prefix, $path)
	{
		$pathHash = md5($path);
		$this->_cacheLog[$prefix][$name][$pathHash] = false;
		
		$this->_save();
	}
	
	/**
	 * Log a cache hit
	 *
	 * @param String $name
	 * @param String $prefix
	 * @param String $path
	 */
	public function logCacheHit($name, $prefix, $path = '')
	{
		$pathHash = md5($path); 
		$this->_cacheLog[$prefix][$name][$pathHash] = true;
		$this->_save();		
	}	
	
	public function _save()
	{
		$this->_cache->save($this, self::$_cacheIdentifier);
	}
}

?>