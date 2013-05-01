<?php
define("DB_NAME", "test");
define("DB_SERVER", "localhost");
define("DB_UN", "test");
define("DB_PASS", "1234a");

require_once("Zend/Amf/Value/ByteArray.php");
require_once "ComplexBARequest.php";

class TestService 
{
	public static $myConnection;
	public static $connected = false;
	
	/**
	 * Constructor
	 * @return void
	 */
	function __construct() 
	{
		self::$myConnection = NULL;
		$this->connect();
	}
	
	/**
	 * Destructor
	 * @return void
	 */
	function __destruct() 
	{
		$this->disconnect();
	}
	
	/**
	 * Connect to the Database
	 * 
	 * @return resource for mysql
	 * @throws Zend_Amf_Server_Exception
	 */
	function connect()
	{
		if (!isset(self::$myConnection))
			if (!self::$myConnection = mysql_connect(DB_SERVER, DB_UN, DB_PASS) ) 
				throw new Zend_Amf_Server_Exception("Unable to connect to DB Server. Server said: " . mysql_error());
			
			if (!mysql_select_db(DB_NAME)) 
				throw new Zend_Amf_Server_Exception("Unable to select the correct table in the DB Server. Server said: " . mysql_error());
		
		self::$connected = true;
		
		return self::$myConnection;
	}
	
	/**
	 * Close the MySQL database connection
	 * @return void
	 */
	function disconnect()
	{
		//if (isset(self::$myConnection))
		//	mysql_close(self::$myConnection);
		self::$connected = false;
	}
	
	public function savePhoto(Zend_Amf_Value_ByteArray $request)
	{
		$this->connect();
		$insertSQL = "INSERT INTO `pics` (`pic`) VALUES (0x" . $request->getData() . ");";
		$insertRES = mysql_query($insertSQL);
		
		$numRows = mysql_affected_rows(self::$myConnection);
		
		if ($numRows === 1)
		{
			return "INSERTED";
		}
		else
		{
			$var_err = mysql_error();
			$err = new stdClass();
			$err->message = "Photo Write Failed. ".$var_err;
			$request->result = "FAIL";
			throw new Zend_Amf_Exception(serialize($err), "FAIL");
		}
	}
	
	public function savePhotoComplex(ComplexBARequest $request)
	{
		$this->connect();
		$insertSQL = "INSERT INTO `pics` (`title`, pic`) VALUES ('" . $request->title . "', 0x" . $request->image->getData() . ");";
		$insertRES = mysql_query($insertSQL);
		
		$numRows = mysql_affected_rows(self::$myConnection);
		
		if ($numRows === 1)
		{
			return "INSERTED";
		}
		else
		{
			$var_err = mysql_error();
			$err = new stdClass();
			$err->message = "Photo Write Failed. ".$var_err;
			$request->result = "FAIL";
			throw new Zend_Amf_Exception(serialize($err), "FAIL");
		}
	}
}

?>