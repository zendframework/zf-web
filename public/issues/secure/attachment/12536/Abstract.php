<?php

/**
 * @author Michael Croes <mcroes@denc.nl>
 */

/**
 * @category Denc
 * @package Denc_Db
 * @subpackage Table
 */
abstract class Denc_Db_Table_Row_Abstract extends Zend_Db_Table_Row_Abstract {

	/**
	 * Turn magic function calls into non-magic function calls
	 * to class methods.
	 *
	 * @param  string $method
	 * @param  array $args OPTIONAL Zend_Db_Table_Select query modifier
	 * @return Zend_Db_Table_Row_Abstract|Zend_Db_Table_Rowset_Abstract
	 */
	public function __call($method, array $args)
	{
		$matches = array();

		if (count($args) && $args[0] instanceof Zend_Db_Table_Select) {
			$select = $args[0];
		} else {
			$select = null;
		}

		/**
		 * Recognize methods for Has-One cases:
		 * get<Rule>()
		 */
		if (preg_match('/^get(\w+)$/', $method, $matches)) {
			$rule = $matches[1];
			return $this->findParentRowByRule($rule, $select);
		}

		return parent::__call($method, $args);
	}

	/**
	 * Get reference by rule.
	 *
	 * @param  string $ruleKey
	 * @return array
	 * @throws Zend_Db_Table_Exception
	 */
	protected function _getReference($ruleKey) {
		$references = $this->getTable()->info(
			Zend_Db_Table_Abstract::REFERENCE_MAP);

		if (array_key_exists($ruleKey, $references)) {
			return $references[$ruleKey];
		}
		throw new Zend_Db_Table_Exception("No reference rule \"$rule\" from \"{$this->_tableToReference($this->getTable())}\".");
	}

	/**
	 * Find a parent row by rule.
	 *
	 * @param  string $rule
	 * @param  Zend_Db_Table_Select $select
	 * @return Zend_Db_Table_Row_Abstract
	 */
	public function findParentRowByRule($rule, Zend_Db_Table_Select $select = null) {
		$ref = $this->_getReference($rule);
		return $this->findParentRow(
			$ref[Zend_Db_Table_Abstract::REF_TABLE_CLASS], $rule, $select);
	}

	/**
	 * Get a string reference to provided table.
	 *
	 * @param  Zend_Db_Table_Abstract $table
	 * @return string
	 */
	protected function _tableToReference(Zend_Db_Table_Abstract $table) {
		$class = get_class($table);
		if ($class === 'Zend_Db_Table') {
			$class = $table->getDefinitionConfigName();
		}
		return $class;
	}

	/**
	 * Create a dependent row.
	 *
	 * @param  Zend_Db_Table_Abstract|string $table
	 * @param  string $ruleKey
	 * @param  mixed ... Proxied arguments
	 * @return Zend_Db_Table_Row_Abstract
	 */
	public function createDependentRow($table, $ruleKey = null) {
		$args = func_get_args();
		if (is_string($table)) {
			$table = $this->_getTableFromString($table);
		}

		$row = call_user_func_array(array($table, 'createRow'), array_slice($args, 2));
		return $row->setReference($this, $ruleKey);
	}

	/**
	 * Create dependent row by rule.
	 *
	 * @param  string $ruleKey
	 * @param  mixed ... Proxied arguments
	 * @return Zend_Db_Table_Row_Abstract
	 */
	public function createDependentRowByRule($ruleKey) {
		$args = func_get_args();
		$ref = $this->_getReference($ruleKey);
		array_unshift($args, $ref[Zend_Db_Table_Abstract::REF_TABLE_CLASS]);
		return call_user_func_array(array($this, 'createDependentRow'), $args);
	}

	/**
	 * Determine whether this row is a valid reference.
	 *
	 * To be a valid reference this row needs to fullfill at least the
	 * following conditions
	 * - Row is saved
	 * - Primary keys have not been altered in respect to saved row
	 *
	 * @return bool
	 */
	public function isValidReference() {
		switch (true) {
			case empty($this->_cleanData):
			case count(array_intersect_key($this->_primary,
				$this->_modifiedFields)) > 0:
				return false;
				break;
			default:
				return true;
		}
	}

	/**
	 * Set a reference to the provided row. See the getReference function
	 * from the table class for reference selection.
	 *
	 * @param  Zend_Db_Table_Row_Abstract $row
	 * @param  string $ruleKey
	 * @return Denc_Db_Table_Row_Abstract
	 * @throws Zend_Db_Table_Exception
	 */
	public function setReference(Denc_Db_Table_Row_Abstract $row,
		$ruleKey = null)
	{
		if (!$row->isValidReference()) {
			throw new Zend_Db_Table_Exception('Invalid reference row provided for ' .
				__METHOD__ . '.');
		}

		$reference = $this->getTable()->getReference(
			$this->_tableToReference($row->getTable()), $ruleKey);

		foreach (array_keys($reference[Zend_Db_Table_Abstract::COLUMNS])
			as $index)
		{
			$this->{$reference[Zend_Db_Table_Abstract::COLUMNS][$index]} =
				$row->{$reference[Zend_Db_Table_Abstract::REF_COLUMNS][$index]};

		}

		return $this;
	}

	/**
	 * Unset a reference to the provided table. See the getReference function
	 * from the table class for reference selection.
	 *
	 * @param  Zend_Db_Table_Abstract|string $table
	 * @param  string $ruleKey
	 * @return Denc_Db_Table_Row_Abstract
	 */
	public function unsetReference($table, $ruleKey = null) {
		if ($table instanceof Zend_Db_Table_Abstract) {
			$table = $this->_tableToReference($table);
		}

		$reference = $this->getTable()->getReference($table, $ruleKey);

		foreach ($reference[Zend_Db_Table_Abstract::COLUMNS] as $column) {
			$this->{$column} = null;
		}

		return $this;
	}

}
