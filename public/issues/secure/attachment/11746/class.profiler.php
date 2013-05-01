<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classprofiler
 *
 * @author roysimkes
 */
class Profiler extends Zend_Db_Profiler_Firebug {
    /**
     *
     * @param string $label
     */
    public function  __construct($label) {
        parent::__construct($label);
    }

    /**
     *
     * @param boolean $enable
     */
    public function setEnabled($enable) {
        parent::setEnabled($enable);
        if ($enable) {
            $this->_message = new TableMessage($this->label);
            $this->_message->setBuffered(true);
            $this->_message->setHeader(array('Time','Event','Parameters','Results'));
            $this->_message->setDestroy(true);
            Zend_Wildfire_Plugin_FirePhp::getInstance()->send($this->_message);
        }
    }

    /**
     *
     * @param ArrayObject $results
     */
    public function addResults($results = null) {
        $lastRowIndex = $this->_message->getRowCount()-1;
        $row = $this->_message->getLastRow();
        if (!is_null($results)) {
            $row[] = ($params=$results->getArrayCopy()) ? $params : null;
        } else {
            $row[] = null;
        }
        $this->_message->setRow($lastRowIndex, $row);
    }
}
?>