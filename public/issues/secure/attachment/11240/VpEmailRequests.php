<?php

class Vp_Db_Table_Vp_VpEmailRequests extends Zend_Db_Table_Abstract
{
	protected $_name = 'vp_Email_Requests';
	
	protected $_primary = 'TblPK';
	
	protected $_sequence = true;
	
	protected $_dependentTables = array('Oc');
	
	protected $_referenceMap    = array(
		'Oc' => array(
			'columns'           => 'oc_id',
			'refTableClass'     => 'Vp_Db_Table_Vp_Oc',
			'refColumns'        => 'oc_id'
		)
	);
}
?>