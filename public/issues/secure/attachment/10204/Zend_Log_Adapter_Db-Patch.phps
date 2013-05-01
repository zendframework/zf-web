<?php

class Zend_Log_Adapter_Db implements Zend_Log_Adapter_Interface {

    /* ... */

    /**
     * Writes an array of key/value pairs to the database, where the keys are the
     * database field names and values are what to put in those fields.
     *
     * @param array $fields
     * @return bool
     */
    public function write($fields)
    {
        /**
         * If the field defaults for 'message' and 'level' have been changed
         * in the options, replace the keys in the $field array.
         */
        if ($this->_options['fieldMessage'] != 'message') {
            $fields[$this->_options['fieldMessage']] = $fields['message'];
            unset($fields['message']);
        }

        if ($this->_options['fieldLevel'] != 'level') {
            $fields[$this->_options['fieldLevel']] = $fields['level'];
            unset($fields['level']);
        }

        /**
         * INSERT the log line into the database.
         */
        $this->_dbAdapter->insert($this->_tableName, $fields);
        return true;
    }

    /* ... */

}

?>