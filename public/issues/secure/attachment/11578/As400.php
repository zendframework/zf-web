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
 * @package    Zend_Db
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 */

/**
 * @see Zend_Db
 */
require_once 'Zend/Db.php';

/**
 * @see Zend_Db_Adapter_Abstract
 */
require_once 'Zend/Db/Adapter/Abstract.php';

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @see Zend_Db_Adapter_Pdo_Abstract
 */
require_once 'Zend/Db/Adapter/Pdo/Abstract.php';



/**
 * @package    Zend_Db
 * @copyright  Copyright (c) 2005-2008 Zend Technologies Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

class Zend_Db_Adapter_Pdo_Odbc_As400 extends Zend_Db_Adapter_Pdo_Abstract
{
    /**
     * User-provided configuration.
     *
     * Basic keys are:
     *
     * username   => (string)  Connect to the database as this username.
     * password   => (string)  Password associated with the username.
     * host       => (string)  What host to connect to (default 127.0.0.1)
     * dbname     => (string)  The name of the database to user
     * protocol   => (string)  Protocol to use, defaults to "TCPIP"
     * port       => (integer) Port number to use for TCP/IP if protocol is "TCPIP"
     * persistent => (boolean) Set TRUE to use a persistent connection (db2_pconnect)
     *
     * @var array
     */
    protected $_config = array(
        'dbname'       => null
    );

    protected $_pdoType = 'odbc';


    /**
     * Keys are UPPERCASE SQL datatypes or the constants
     * Zend_Db::INT_TYPE, Zend_Db::BIGINT_TYPE, or Zend_Db::FLOAT_TYPE.
     *
     * Values are:
     * 0 = 32-bit integer
     * 1 = 64-bit integer
     * 2 = float or decimal
     *
     * @var array Associative array of datatypes to values 0, 1, or 2.
     */
    protected $_numericDataTypes = array(
        Zend_Db::INT_TYPE    => Zend_Db::INT_TYPE,
        Zend_Db::BIGINT_TYPE => Zend_Db::BIGINT_TYPE,
        Zend_Db::FLOAT_TYPE  => Zend_Db::FLOAT_TYPE,
        'INTEGER'            => Zend_Db::INT_TYPE,
        'SMALLINT'           => Zend_Db::INT_TYPE,
        'BIGINT'             => Zend_Db::BIGINT_TYPE,
        'DECIMAL'            => Zend_Db::FLOAT_TYPE,
        'NUMERIC'            => Zend_Db::FLOAT_TYPE
    );



    /**
     * Returns a list of the tables in the database.
     * @param string $schema OPTIONAL
     * @return array
     */
    public function listTables( $schemaName = null)
  	{
  	    $sql = "SELECT TABLE_NAME FROM QSYS2.SYSTABLES WHERE TABLE_TYPE='P'";
        if ($schemaName) {
            $sql .= $this->quoteInto(' AND TABLE_SCHEMA = UPPER(?)', $schemaName);
        }
  	    return $this->query($sql)->fetchAll(Zend_Db::FETCH_COLUMN);
  	}    
 
    /**
     * Returns the column descriptions for a table.
     *
     * The return value is an associative array keyed by the column name,
     * as returned by the RDBMS.
     *
     * The value of each array element is an associative array
     * with the following keys:
     *
     * SCHEMA_NAME      => string; name of database or schema
     * TABLE_NAME       => string;
     * COLUMN_NAME      => string; column name
     * COLUMN_POSITION  => number; ordinal position of column in table
     * DATA_TYPE        => string; SQL datatype name of column
     * DEFAULT          => string; default expression of column, null if none
     * NULLABLE         => boolean; true if column can have nulls
     * LENGTH           => number; length of CHAR/VARCHAR
     * SCALE            => number; scale of NUMERIC/DECIMAL
     * PRECISION        => number; precision of NUMERIC/DECIMAL
     * UNSIGNED         => boolean; unsigned property of an integer type
     * PRIMARY          => boolean; true if column is part of the primary key
     * PRIMARY_POSITION => integer; position of column in primary key
     * IDENTITY         => integer; true if column is auto-generated with unique values
     *
     * @todo Discover integer unsigned property.
     *
     * @param string $tableName
     * @param string $schemaName OPTIONAL
     * @return array
     */
    public function describeTable($tableName, $schemaName = null)
    {
        $sql = "SELECT DISTINCT C.TABLE_SCHEMA, C.TABLE_NAME, C.COLUMN_NAME, C.ORDINAL_POSITION,
              C.DATA_TYPE, C.COLUMN_DEFAULT, C.NULLS ,C.LENGTH, C.SCALE, LEFT(C.IDENTITY,1),
	            LEFT(tc.TYPE, 1) AS tabconsttype, k.COLSEQ
	            FROM QSYS2.SYSCOLUMNS C     
	            LEFT JOIN (QSYS2.syskeycst k JOIN QSYS2.SYSCST tc
	                ON (k.TABLE_SCHEMA = tc.TABLE_SCHEMA
	                  AND k.TABLE_NAME = tc.TABLE_NAME
	                  AND LEFT(tc.type,1) = 'P'))                  
	                ON (C.TABLE_SCHEMA = k.TABLE_SCHEMA
	                   AND C.TABLE_NAME = k.TABLE_NAME
	                   AND C.COLUMN_NAME = k.COLUMN_NAME)                          
	            WHERE "
              . $this->quoteInto('UPPER(C.TABLE_NAME) = UPPER(?)', $tableName);
        if ($schemaName) {
            $sql .= $this->quoteInto(' AND UPPER(C.TABLE_SCHEMA) = UPPER(?)', $schemaName);
        }
         
        $sql .= " ORDER BY C.ORDINAL_POSITION FOR FETCH ONLY";

        $desc = array();
        $stmt = $this->query($sql);

        /**
         * To avoid case issues, fetch using FETCH_NUM
         */
        $result = $stmt->fetchAll(Zend_Db::FETCH_NUM);

        /**
         * The ordering of columns is defined by the query so we can map
         * to variables to improve readability
         */
        $tabschema      = 0;
        $tabname        = 1;
        $colname        = 2;
        $colno          = 3;
        $typename       = 4;
        $default        = 5;
        $nulls          = 6;
        $length         = 7;
        $scale          = 8;
        $identityCol    = 9;
        $tabconstype    = 10;
        $colseq         = 11;

        foreach ($result as $key => $row) {
            list ($primary, $primaryPosition, $identity) = array(false, null, false);
            if ($row[$tabconstype] == 'P') {
                $primary = true;
                $primaryPosition = $row[$colseq];
            }
            /**
             * In IBM DB2, an column can be IDENTITY
             * even if it is not part of the PRIMARY KEY.
             */
            if ($row[$identityCol] == 'Y') {
                $identity = true;
            }

            // only colname needs to be case adjusted
            $desc[$this->foldCase($row[$colname])] = array(
                'SCHEMA_NAME'      => $this->foldCase($row[$tabschema]),
                'TABLE_NAME'       => $this->foldCase($row[$tabname]),
                'COLUMN_NAME'      => $this->foldCase($row[$colname]),
                'COLUMN_POSITION'  => $row[$colno]+1,
                'DATA_TYPE'        => $row[$typename],
                'DEFAULT'          => $row[$default],
                'NULLABLE'         => (bool) ($row[$nulls] == 'Y'),
                'LENGTH'           => $row[$length],
                'SCALE'            => $row[$scale],
                'PRECISION'        => ($row[$typename] == 'DECIMAL' ? $row[$length] : 0),
                'UNSIGNED'         => null, // @todo
                'PRIMARY'          => $primary,
                'PRIMARY_POSITION' => $primaryPosition,
                'IDENTITY'         => $identity
            );
        }

        return $desc;
    }


    /**
     * Adds an adapter-specific LIMIT clause to the SELECT statement.
     *
     * @param string $sql
     * @param integer $count
     * @param integer $offset OPTIONAL
     * @return string
     */
    public function limit($sql, $count, $offset = 0)
    {
        $count = intval($count);
        if ($count <= 0) {
            /**
             * @see Zend_Db_Adapter_Db2_Exception
             */
            require_once 'Zend/Db/Adapter/Db2/Exception.php';
            throw new Zend_Db_Adapter_Db2_Exception("LIMIT argument count=$count is not valid");
        }

        $offset = intval($offset);
        if ($offset < 0) {
            /**
             * @see Zend_Db_Adapter_Db2_Exception
             */
            require_once 'Zend/Db/Adapter/Db2/Exception.php';
            throw new Zend_Db_Adapter_Db2_Exception("LIMIT argument offset=$offset is not valid");
        }

        if ($offset == 0) {
            $limit_sql = $sql . " FETCH FIRST $count ROWS ONLY";
            return $limit_sql;
        }

        /**
         * DB2 does not implement the LIMIT clause as some RDBMS do.
         * We have to simulate it with subqueries and ROWNUM.
         * Unfortunately because we use the column wildcard "*",
         * this puts an extra column into the query result set.
         */
        $limit_sql = "SELECT z2.*
            FROM (
                SELECT ROW_NUMBER() OVER() AS \"ZEND_DB_ROWNUM\", z1.*
                FROM (
                    " . $sql . "
                ) z1
            ) z2
            WHERE z2.zend_db_rownum BETWEEN " . ($offset+1) . " AND " . ($offset+$count);
        return $limit_sql;
    }

    /**
     * Creates a PDO DSN for the adapter from $this->_config settings.
     *
     * @return string
     */
    protected function _dsn()
    {
        // baseline of DSN parts
        $dsn = $this->_config;

        return $this->_pdoType . ':' . $this->_config['dbname'];
    }

    protected function _quote($q)
    {
      return "'".$q."'";
    }

    /**
     * Returns the symbol the adapter uses for delimited identifiers.
     *
     * @return string
     */
    public function getQuoteIdentifierSymbol()
    {
        return '';
    }
    
}

  
