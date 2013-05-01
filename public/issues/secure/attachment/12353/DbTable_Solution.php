<?php
/** @see Zend_Session_SaveHandler_DbTable */ 
/**
 * @author      Yancho Georgiev <yancho@inspirestudio.net>
 */
class Yancho_Zend_Session_SaveHandler_DbTable extends Zend_Session_SaveHandler_DbTable 
{	
	protected $has_record = false;
	
	/**
     * Read session data
     *
     * @param string $id
     * @return string
     */
	public function read($id)
    {
        $return = '';
		
        $rows = call_user_func_array(array(&$this, 'find'), $this->_getPrimary($id));
		
        if (count($rows)) 
        {
            if ($this->_getExpirationTime($row = $rows->current()) > time()) 
            {
                $return = $row->{$this->_dataColumn};
                $this->has_record = true;
            } 
            else 
            {
                $this->destroy($id);
            }
        }

        return $return;
    }
    
 	/**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return boolean
     */
    public function write($id, $data)
    {
        $return = false;

        $data = array($this->_modifiedColumn => time(),
                      $this->_dataColumn     => (string) $data);
		
		/* We already execute this in read method */
       //$rows = call_user_func_array(array(&$this, 'find'), $this->_getPrimary($id));
		
        //if (count($rows)) {
        if ($this->has_record) {
			/* Update _lifetimeColumn only if make sense */
        	//$data[$this->_lifetimeColumn] = $this->_getLifetime($rows->current());
            if ($this->_overrideLifetime) $data[$this->_lifetimeColumn] = $this->_lifetime;

            if ($this->update($data, $this->_getPrimary($id, self::PRIMARY_TYPE_WHERECLAUSE))) {
                $return = true;
            }
        } else {
            $data[$this->_lifetimeColumn] = $this->_lifetime;

            if ($this->insert(array_merge($this->_getPrimary($id, self::PRIMARY_TYPE_ASSOC), $data))) {
                $return = true;
            }
        }

        return $return;
    }
}
?>