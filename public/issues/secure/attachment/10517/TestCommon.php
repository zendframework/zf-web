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
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: TestCommon.php 5014 2007-05-27 04:30:18Z bkarwin $
 */


/**
 * @see Zend_Db_TestSetup
 */
require_once 'Zend/Db/TestSetup.php';


PHPUnit_Util_Filter::addFileToFilter(__FILE__);


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Db_Adapter_TestCommon extends Zend_Db_TestSetup
{

    public abstract function testAdapterExceptionInvalidLoginCredentials();

    /**
     * Test Adapter's delete() method.
     * Delete one row from test table, and verify it was deleted.
     * Then try to delete a row that doesn't exist, and verify it had no effect.
     */
    public function testAdapterDelete()
    {
        $product_id = $this->_db->quoteIdentifier('product_id');

        $select = $this->_db->select()->from('zfproducts')->order('product_id ASC');
        $result = $this->_db->fetchAll($select);

        $this->assertEquals(3, count($result), 'Expected count of result to be 2');
        $this->assertEquals(1, $result[0]['product_id'], 'Expecting product_id of 0th row to be 1');

        $rowsAffected = $this->_db->delete('zfproducts', "$product_id = 2");
        $this->assertEquals(1, $rowsAffected, 'Expected rows affected to return 1', 'Expecting rows affected to be 1');

        $select = $this->_db->select()->from('zfproducts')->order('product_id ASC');
        $result = $this->_db->fetchAll($select);

        $this->assertEquals(2, count($result), 'Expected count of result to be 2');
        $this->assertEquals(1, $result[0]['product_id'], 'Expecting product_id of 0th row to be 1');

        $rowsAffected = $this->_db->delete('zfproducts', "$product_id = 327");
        $this->assertEquals(0, $rowsAffected, 'Expected rows affected to return 0');
    }

    /**
     * Test Adapter's describeTable() method.
     * Retrieve the adapter's description of the test table and examine it.
     */
    public function testAdapterDescribeTableHasColumns()
    {
        $desc = $this->_db->describeTable('zfproducts');

        $cols = array(
            'product_id',
            'product_name'
        );
        $this->assertEquals($cols, array_keys($desc));
    }

    public function testAdapterDescribeTableMetadataFields()
    {
        $desc = $this->_db->describeTable('zfproducts');

        $keys = array(
            'SCHEMA_NAME',
            'TABLE_NAME',
            'COLUMN_NAME',
            'COLUMN_POSITION',
            'DATA_TYPE',
            'DEFAULT',
            'NULLABLE',
            'LENGTH',
            'SCALE',
            'PRECISION',
            'UNSIGNED',
            'PRIMARY',
            'PRIMARY_POSITION',
            'IDENTITY'
        );
        $this->assertEquals($keys, array_keys($desc['product_name']));
    }

    public function testAdapterDescribeTableAttributeColumn()
    {
        $desc = $this->_db->describeTable('zfproducts');

        $this->assertEquals('zfproducts',        $desc['product_name']['TABLE_NAME']);
        $this->assertEquals('product_name',      $desc['product_name']['COLUMN_NAME']);
        $this->assertEquals(2,                   $desc['product_name']['COLUMN_POSITION']);
        $this->assertRegExp('/varchar/i',        $desc['product_name']['DATA_TYPE']);
        $this->assertEquals('',                  $desc['product_name']['DEFAULT']);
        $this->assertTrue(                       $desc['product_name']['NULLABLE'], 'Expected product_name to be nullable');
        $this->assertEquals(0,                   $desc['product_name']['SCALE']);
        $this->assertEquals(0,                   $desc['product_name']['PRECISION']);
        $this->assertFalse(                      $desc['product_name']['PRIMARY'], 'Expected product_name not to be a primary key');
        $this->assertNull(                       $desc['product_name']['PRIMARY_POSITION'], 'Expected product_name to return null for PRIMARY_POSITION');
        $this->assertFalse(                      $desc['product_name']['IDENTITY'], 'Expected product_name to return false for IDENTITY');
    }

    public function testAdapterDescribeTablePrimaryKeyColumn()
    {
        $desc = $this->_db->describeTable('zfproducts');

        $this->assertEquals('zfproducts',        $desc['product_id']['TABLE_NAME']);
        $this->assertEquals('product_id',        $desc['product_id']['COLUMN_NAME']);
        $this->assertEquals(1,                   $desc['product_id']['COLUMN_POSITION']);
        $this->assertEquals('',                  $desc['product_id']['DEFAULT']);
        $this->assertFalse(                      $desc['product_id']['NULLABLE'], 'Expected product_id not to be nullable');
        $this->assertEquals(0,                   $desc['product_id']['SCALE']);
        $this->assertEquals(0,                   $desc['product_id']['PRECISION']);
        $this->assertTrue(                       $desc['product_id']['PRIMARY'], 'Expected product_id to be a primary key');
        $this->assertEquals(1,                   $desc['product_id']['PRIMARY_POSITION']);
    }

    /**
     * Test that an auto-increment key reports itself.
     * This is not supported in all RDBMS brands, so we test
     * it separately from the full describe tests above.
     */
    public function testAdapterDescribeTablePrimaryAuto()
    {
        $desc = $this->_db->describeTable('zfproducts');

        $auto = $desc['product_id']['IDENTITY'];
        if ($auto === null) {
            $this->markTestIncomplete($this->getDriver() . ' needs to learn how to discover auto-increment keys');
        }
        $this->assertTrue($desc['product_id']['IDENTITY']);
    }

    /**
     * Test the Adapter's fetchAll() method.
     */
    public function testAdapterFetchAll()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchAll("SELECT * FROM $products WHERE $product_id > ? ORDER BY $product_id ASC", 1);
        $this->assertEquals(2, count($result));
        $this->assertEquals('2', $result[0]['product_id']);
    }

    /**
     * Test the Adapter's fetchAssoc() method.
     */
    public function testAdapterFetchAssoc()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchAssoc("SELECT * FROM $products WHERE $product_id > ? ORDER BY $product_id DESC", 1);
        foreach ($result as $idKey => $row) {
            $this->assertEquals($idKey, $row['product_id']);
        }
    }

    /**
     * Test the Adapter's fetchCol() method.
     */
    public function testAdapterFetchCol()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchCol("SELECT * FROM $products WHERE $product_id > ? ORDER BY $product_id ASC", 1);
        $this->assertEquals(2, count($result)); // count rows
        $this->assertEquals(2, $result[0]);
        $this->assertEquals(3, $result[1]);
    }

    /**
     * Test the Adapter's fetchOne() method.
     */
    public function testAdapterFetchOne()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');
        $product_name = $this->_db->quoteIdentifier('product_name');

        $prod = 'Linux';
        $result = $this->_db->fetchOne("SELECT $product_name FROM $products WHERE $product_id > ? ORDER BY $product_id", 1);
        $this->assertEquals($prod, $result);
    }

    /**
     * Test the Adapter's fetchPairs() method.
     */
    public function testAdapterFetchPairs()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');
        $product_name = $this->_db->quoteIdentifier('product_name');

        $prod = 'Linux';
        $result = $this->_db->fetchPairs("SELECT $product_id, $product_name FROM $products WHERE $product_id > ? ORDER BY $product_id ASC", 1);
        $this->assertEquals(2, count($result)); // count rows
        $this->assertEquals($prod, $result[2]);
    }

    /**
     * Test the Adapter's fetchRow() method.
     */
    public function testAdapterFetchRow()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchRow("SELECT * FROM $products WHERE $product_id > ? ORDER BY $product_id", 1);
        $this->assertEquals(2, count($result)); // count columns
        $this->assertEquals(2, $result['product_id']);
    }

    /**
     * Test the Adapter's insert() method.
     * This requires providing an associative array of column=>value pairs.
     */
    public function testAdapterInsert()
    {
        $row = array (
            'bug_description' => 'New bug',
            'bug_status'      => 'NEW',
            'created_on'      => '2007-04-02',
            'updated_on'      => '2007-04-02',
            'reported_by'     => 'micky',
            'assigned_to'     => 'goofy',
            'verified_by'     => 'dduck'
        );
        $rowsAffected = $this->_db->insert('zfbugs', $row);
        $this->assertEquals(1, $rowsAffected);
        $lastInsertId = $this->_db->lastInsertId();
        $this->assertEquals('5', (string) $lastInsertId,
            'Expected new id to be 5');
    }

    public function testAdapterInsertSequence()
    {
        $row = array (
            'product_id' => $this->_db->nextSequenceId('zfproducts_seq'),
            'product_name' => 'Solaris',
        );
        $rowsAffected = $this->_db->insert('zfproducts', $row);
        $this->assertEquals(1, $rowsAffected);
        $lastInsertId = $this->_db->lastInsertId('zfproducts');
        $lastSequenceId = $this->_db->lastSequenceId('zfproducts_seq');
        $this->assertEquals('4', (string) $lastInsertId, 'Expected new id to be 4');
    }

    /**
     * Test the Adapter's limit() method.
     * Fetch 1 row.  Then fetch 1 row offset by 1 row.
     */
    public function testAdapterLimit()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $sql = $this->_db->limit("SELECT * FROM $products ORDER BY $product_id", 1);

        $stmt = $this->_db->query($sql);
        $result = $stmt->fetchAll();
        $this->assertEquals(1, count($result),
            'Expecting row count to be 1');
        $this->assertEquals(2, count($result[0]),
            'Expecting column count to be 2');
        $this->assertEquals(1, $result[0]['product_id'],
            'Expecting to get product_id 1');
    }

    public function testAdapterLimitOffset()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $sql = $this->_db->limit("SELECT * FROM $products ORDER BY $product_id", 1, 1);

        $stmt = $this->_db->query($sql);
        $result = $stmt->fetchAll();
        $this->assertEquals(1, count($result),
            'Expecting row count to be 1');
        $this->assertEquals(2, count($result[0]),
            'Expecting column count to be 2');
        $this->assertEquals(2, $result[0]['product_id'],
            'Expecting to get product_id 2');
    }

    /**
     * Test the Adapter's listTables() method.
     * Fetch the list of tables and verify that the test table exists in
     * the list.
     */
    public function testAdapterListTables()
    {
        $tables = $this->_db->listTables();
        $this->assertContains('zfproducts', $tables);
    }

    public function testAdapterQuoteIdentifier()
    {
        $value = $this->_db->quoteIdentifier('table_name');
        $this->assertEquals('"table_name"', $value);
        $value = $this->_db->quoteIdentifier('table_"_name');
        $this->assertEquals('"table_""_name"', $value);
    }

    public function testAdapterQuote()
    {
        // test double quotes are fine
        $value = $this->_db->quote('St John"s Wort');
        $this->assertEquals("'St John\\\"s Wort'", $value);

        // test that single quotes are escaped with another single quote
        $value = $this->_db->quote("St John's Wort");
        $this->assertEquals("'St John\'s Wort'", $value);

        // quote an array
        $value = $this->_db->quote(array("it's", 'all', 'right!'));
        $this->assertEquals("'it\\'s', 'all', 'right!'", $value);

        // test numeric
        $value = $this->_db->quote('1');
        $this->assertEquals("'1'", $value);

        $value = $this->_db->quote(1);
        $this->assertEquals("1", $value);

        $value = $this->_db->quote(array(1,'2',3));
        $this->assertEquals("1, '2', 3", $value);
    }

    public function testAdapterQuoteInto()
    {
        // test double quotes are fine
        $value = $this->_db->quoteInto('id=?', 'St John"s Wort');
        $this->assertEquals("id='St John\\\"s Wort'", $value);

        // test that single quotes are escaped with another single quote
        $value = $this->_db->quoteInto('id = ?', 'St John\'s Wort');
        $this->assertEquals("id = 'St John\\'s Wort'", $value);
    }

    /**
     * @todo testAdapterTransactionCommit()
     */

    /**
     * @todo testAdapterTransactionRollback()
     */

    /**
     * Test the Adapter's update() method.
     * Update a single row and verify that the change was made.
     * Attempt to update a row that does not exist, and verify
     * that no change was made.
     */
    public function testAdapterUpdate()
    {
        $product_id = $this->_db->quoteIdentifier('product_id');

        // Test that we can change the values in
        // an existing row.
        $result = $this->_db->update(
            'zfproducts',
            array('product_name' => 'Vista'),
            "$product_id = 1"
        );
        $this->assertEquals(1, $result);

        // Query the row to see if we have the new values.
        $select = $this->_db->select();
        $select->from('zfproducts');
        $select->where("$product_id = 1");
        $stmt = $this->_db->query($select);
        $result = $stmt->fetchAll();

        $this->assertEquals(1, $result[0]['product_id']);
        $this->assertEquals('Vista', $result[0]['product_name']);

        // Test that update affects no rows if the WHERE
        // clause matches none.
        $result = $this->_db->update(
            'zfproducts',
            array('product_name' => 'Vista'),
            "$product_id = 327"
        );
        $this->assertEquals(0, $result);
    }

    public function testAdapterExceptionInvalidLimitArgument()
    {
        try {
            $sql = $this->_db->limit('SELECT * FROM zfproducts', 0);
            $this->fail('Expected to catch Zend_Db_Adapter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Adapter_Exception', $e,
                'Expecting object of type Zend_Db_Adapter_Exception, got '.get_class($e));
        }

        try {
            $sql = $this->_db->limit('SELECT * FROM zfproducts', 1, -1);
            $this->fail('Expected to catch Zend_Db_Adapter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Adapter_Exception', $e,
                'Expecting object of type Zend_Db_Adapter_Exception, got '.get_class($e));
        }
    }

    /**
     * Ensures that query() provides expected behavior when returning no results
     *
     * @return void
     */
    public function testAdapterQueryResultsNone()
    {
        $stmt = $this->_db->query('SELECT * FROM ' . $this->_db->quoteIdentifier('zfbugs') . ' WHERE '
            . $this->_db->quoteIdentifier('bug_id') . ' = -1');

        $this->assertTrue(is_object($stmt),
            'Expected query() to return object; got ' . gettype($stmt));

        $this->assertType('Zend_Db_Statement_Interface', $stmt,
            'Expected query() to return Zend_Db_Statement or PDOStatement; got ' . get_class($stmt));

        $this->assertEquals(0, $count = count($stmt->fetchAll()),
            "Expected fetchAll() to return zero rows; got $count");
    }

    /**
     * Ensures that query() throws an exception when given a bogus query
     *
     * @return void
     */
    public function testAdapterQueryBogus()
    {
        try {
            $this->_db->query('Bogus query');
            $this->fail('Expected exception not thrown');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Statement_Exception', $e,
                'Expecting object of type Zend_Db_Statement_Exception, got '.get_class($e));
        }
    }

    /**
     * Ensures that query() throws an exception when given a bogus table
     *
     * @return void
     */
    public function testAdapterQueryTableBogus()
    {
        try {
            $this->_db->query('SELECT * FROM BogusTable');
            $this->fail('Expected exception not thrown');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Statement_Exception', $e,
                'Expecting object of type Zend_Db_Statement_Exception, got '.get_class($e));
        }
    }

    /**
     * Used by _testAdapterOptionCaseFoldingNatural()
     * DB2 and Oracle return identifiers in uppercase naturally,
     * so those test suites will override this method.
     */
    protected function _getCaseNaturalIdentifier()
    {
        return 'case_folded_identifier';
    }

    /**
     * Used by _testAdapterOptionCaseFoldingNatural()
     * SQLite needs to do metadata setup,
     * because it uses the in-memory database,
     * so that test suite will override this method.
     */
    protected function _testAdapterOptionCaseFoldingSetup(Zend_Db_Adapter_Abstract $db)
    {
        $db->getConnection();
    }

    /**
     * Used by:
     * - testAdapterOptionCaseFoldingNatural()
     * - testAdapterOptionCaseFoldingUpper()
     * - testAdapterOptionCaseFoldingLower()
     *
     * @param int $case
     * @return string
     */
    public function _testAdapterOptionCaseFoldingCommon($case)
    {
        $params = $this->_util->getParams();
        
        $params['options'] = array(
            Zend_Db::CASE_FOLDING => $case
        );
        $db = Zend_Db::factory($this->getDriver(), $params);
        $this->_testAdapterOptionCaseFoldingSetup($db);
        $products = $db->quoteIdentifier('zfproducts');
        $product_name = $db->quoteIdentifier('product_name');
        /*
         * it is important not to delimit the pname identifier
         * in the following query
         */
        $sql = "SELECT $product_name AS case_folded_identifier FROM $products";
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(Zend_Db::FETCH_ASSOC);
        $keys = array_keys($result[0]);
        return $keys[0];
    }

    /**
     * Test the connection's CASE_FOLDING option.
     * Case: Zend_Db::CASE_NATURAL
     */
    public function testAdapterOptionCaseFoldingNatural()
    {
        $natural = $this->_testAdapterOptionCaseFoldingCommon(Zend_Db::CASE_NATURAL);
        $expected = $this->_getCaseNaturalIdentifier();
        $this->assertEquals($natural, $expected, 'Natural case does not match');
    }

     /**
     * Test the connection's CASE_FOLDING option.
     * Case: Zend_Db::CASE_UPPER
     */
    public function testAdapterOptionCaseFoldingUpper()
    {
        $upper = $this->_testAdapterOptionCaseFoldingCommon(Zend_Db::CASE_UPPER);
        $expected = strtoupper($this->_getCaseNaturalIdentifier());
        $this->assertEquals($upper, $expected, 'Upper case does not match');
    }

     /**
     * Test the connection's CASE_FOLDING option.
     * Case: Zend_Db::CASE_LOWER
     */
    public function testAdapterOptionCaseFoldingLower()
    {
        $lower = $this->_testAdapterOptionCaseFoldingCommon(Zend_Db::CASE_LOWER);
        $expected = strtolower($this->_getCaseNaturalIdentifier());
        $this->assertEquals($lower, $expected, 'Lower case does not match');
    }
    
    /**
     * Test ALWAYS_QUOTE_IDENTIFIERS option
     * Case: Zend_Db::ALWAYS_QUOTE_IDENTIFIERS = true
     */
    public function testAdapterAutomaticQuotingTrue()
    {
    	$params = $this->_util->getParams();
    	
    	$params['options'] = array(
    		Zend_Db::ALWAYS_QUOTE_IDENTIFIERS => true);
        $db = Zend_Db::factory($this->getDriver(), $params);
        $db->getConnection();
        
       	$select = $this->_db->select();
        $select->from('zfproducts');
        $stmt = $this->_db->query($select);
        $result = $stmt->fetchAll();

        $this->assertEquals(1, $result[0]['product_id']);
        
        $select = $this->_db->select();
        $select->from('ZFPRODUCTS');
        try {
      	  $stmt = $this->_db->query($select);
      	  $result = $stmt->fetchAll();
      	  $this->fail('Expected exception not thrown');
        } catch (Zend_Exception $e) {
            $this->assertType('Zend_Db_Statement_Exception', $e,
                'Expecting object of type Zend_Db_Statement_Exception, got '.get_class($e));
        }
    }
    
    /**
     * Test ALWAYS_QUOTE_IDENTIFIERS option
     * Case: Zend_Db::ALWAYS_QUOTE_IDENTIFIERS = false
     */
    public function testAdapterAutomaticQuotingFalse()
    {
    	$params = $this->_util->getParams();
    	
    	$params['options'] = array(
    		Zend_Db::ALWAYS_QUOTE_IDENTIFIERS => false);
        $db = Zend_Db::factory($this->getDriver(), $params);
        $db->getConnection();
        $tablename = 'zfnoquote';
        
        $drop = "DROP TABLE $tablename";
  		$create = "CREATE TABLE $tablename(id INT not null, stuff VARCHAR(20))";
  		
        try {
       		$stmt = $db->query($create);
        } catch (Zend_Exception $e) {
        	$stmt = $db->query($drop);
        	$stmt = $db->query($create);
    	}
        
       	$data = array(
    		'id'      => 1,
    		'stuff' => 'no quote'
    	);
    	$numrows = $db->insert($tablename, $data);
    	$this->assertEquals(1, $numrows);
	
		$uptablename = strtoupper($tablename);
    	$db->insert($uptablename, $data);
       	$this->assertEquals(1, $numrows);
    		
       	$stmt = $db->query($drop);
    }
}
