<?php
/**
 * @package    Better_Cache
 * @subpackage Better_Cache_Backend
 * @author daniel.hartmann
 */

/** @see Zend_Cache_Backend */
require_once 'Zend/Cache/Backend/Apc.php';

/**
 * @package    Better_Cache
 * @subpackage Better_Cache_Backend
 */
class Better_Cache_Backend_Apc extends Zend_Cache_Backend_Apc {

	const TAG_LIST = "__ZF_CACHE_TAGS";

    /**
     * @see Zend_Cache_Backend_Apc::save();
     *
     * @param string $data datas to cache
     * @param string $id cache id
     * @param array $tags array of strings, the cache record will be tagged by each string entry
     * @param int $specificLifetime if != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @return boolean true if no problem
     */
    public function save($data, $id, $tags = array(), $specificLifetime = false)  {
        $lifetime = $this->getLifetime($specificLifetime);
        $result = apc_store($id, array($data, time()), $lifetime);
        if (count($tags) > 0) {
        	$tagArray = unserialize(apc_fetch(self::TAG_LIST));
        	foreach ($tags as $tag){
        		if(!array_key_exists($tag, $tagArray)){
        			$tagArray[$tag] = array();
        		}
        		if(!in_array($id, $tagArray[$tag])){
        			$tagArray[$tag][] = $id;
        		}
        	}
			apc_store(self::TAG_LIST, serialize($tagArray));
			unset($tagArray);
        }
        return $result;
    }

    /**
     * @see Zend_Cache_Backend_Apc::remove()
     *
     * @param  string $id cache id
     * @return boolean true if no problem
     */
    public function remove($id) {
    	// remove tags
       	$tagArray = unserialize(apc_fetch(self::TAG_LIST));
    	foreach ($tagArray as $key => $ids){
    		foreach ($ids as $index => $name){
    			if($name==$id){
    				unset($tagArray[$key][$index]);
    			}
    		}
    		if(count($tagArray[$key])==0){
    			unset($tagArray[$key]);
    		}
    	}
    	$data = serialize($tagArray);
		apc_store(self::TAG_LIST, $data);
        return apc_delete($id);
    }

    /**
     * @see Zend_Cache_Backend_Apc::clean()
     *
     * @param  string $mode clean mode
     * @param  array  $tags array of tags
     * @return boolean true if no problem
     */
    public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array()) {
        if ($mode==Zend_Cache::CLEANING_MODE_ALL) {
            return parent::clean($mode, $tags);
        }
        if ($mode==Zend_Cache::CLEANING_MODE_OLD) {
            $this->_log("Better_Cache_Backend_Apc::clean() : CLEANING_MODE_OLD is unsupported by the Apc backend");
        }
        if ($mode==Zend_Cache::CLEANING_MODE_MATCHING_TAG) {
       		$tagArray = unserialize(apc_fetch(self::TAG_LIST));
	    	foreach ($tagArray as $tag => $items){
	    		if(in_array($tag, $tags)){
	    			foreach ($items as &$id){
	    				$this->remove($id);
	    			}
	    		}
	    	}
	    	return true;
        }
        if ($mode==Zend_Cache::CLEANING_MODE_NOT_MATCHING_TAG) {
       		$tagArray = unserialize(apc_fetch(self::TAG_LIST));
       		foreach ($tagArray as $tag => &$items){
	    		if(!in_array($tag, $tags)){
	    			foreach ($items as $id){
	    				$this->remove($id);
	    				unset($id);
	    			}
	    		}
	    	}
	    	return true;
        }
        return false;
    }

}
?>
