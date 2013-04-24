<?php

require_once ('Zend/Validate/Db/RecordExists.php');

class Remihchuv_calidate_db_RecordExists extends Zend_Validate_Db_RecordExists {
  
	private $_schema;
    /**
     * Provides basic configuration for use with Zend_Validate_Db Validators 
     * Setting $exclude allows a single record to be excluded from matching.
     * Exclude can either be a String containing a where clause, or an array with `field` and `value` keys
     * to define the where clause added to the sql.  
     * A database adapter may optionally be supplied to avoid using the registered default adapter. 
     * 
     * @param string $table The database table to validate against
     * @param string $field The field to check for a match
     * @param string||array $exclude An optional where clause or field/value pair to exclude from the query
     * @param Zend_Db_Adapter_Abstract $adapter An optional database adapter to use.
     */   
    public function __construct($table, $field, $exclude = null, Zend_Db_Adapter_Abstract $adapter = null)    
    {    
        if ($adapter !== null) { 
            $this->_adapter = $adapter;    
        }   
        $this->_exclude = $exclude; 
        if (is_array($table)){
        	$this->_table   = $table['table']; 
        	$this->_schema   = (string) $table['schema'];         	  
        } else {
        	$this->_table   = (string) $table;    
        }
        $this->_field   = (string) $field;   
    }  
     
    /**
     * Run query and returns matches, or null if no matches are found.
     *
     * @param  String $value
     * @return Array when matches are found.
     */ 
    protected function _query($value) 
    { 
        /**
         * Check for an adapter being defined. if not, fetch the default adapter.
         */ 
        if($this->_adapter === null) {
            $this->_adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        }

        /**
         * Build select object
         */ 
        $select = new Zend_Db_Select($this->_adapter);
        $select->from($this->_table, array($this->_field),$this->_schema)
               ->where($this->_adapter->quoteIdentifier($this->_field).' = ?', $value); 
        if ($this->_exclude !== null) { 
            if (is_array($this->_exclude)) { 
                $select->where($this->_adapter->quoteIdentifier($this->_exclude['field']).' != ?', $this->_exclude['value']); 
            } else { 
                $select->where($this->_exclude); 
            } 
        } 
        $select->limit(1); 
                 
        /**
         * Run query
         */ 
        $result = $this->_adapter->fetchRow($select, array(), Zend_Db::FETCH_ASSOC); 
         
        return $result; 
    } 
}

?>