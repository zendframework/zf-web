<?php

/*
 * Filtre permettant d'avoir une chaine de caractère dont
 * la première lettre est en majuscule et les autres en minuscules.
 */
class Zend_Filter_StringToCapitalize implements Zend_Filter_Interface {

	protected $_encoding = null;

	public function setEncoding($encoding = null){
		if (!function_exists('mb_strtolower')) {
			require_once 'Zend/Filter/Exception.php';
			throw new Zend_Filter_Exception('mbstring is required for this feature');
		}
		$this->_encoding = $encoding;
	}

	public function filter($value){
		if ($this->_encoding) {
			return ucfirst(mb_strtolower((string) $value, $this->_encoding));
		}
		return ucfirst(strtolower((string) $value));
	}

}

?>
