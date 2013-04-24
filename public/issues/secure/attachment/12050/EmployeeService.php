<?php

/**
 * This is an auto-generated service class based on the table employee.
 */
class EmployeeService {

  private $connection;

  /**
   * The constructor initializes the connection to database. Everytime a request is 
   * received by Zend AMF, an instance of the service class is created and then the
   * requested method is invoked.
   */
  public function __construct() {
    $this->connection = mysql_connect("localhost:3306",  "root",  "") or die(mysql_error());
    mysql_select_db("fb_sample_ria");
  }
  
  /**
   * Returns all the rows from the table employee.
   *
   * @return array
   */
  public function getAllItems() {
    $sql = "SELECT * FROM employee";

    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());

    return $result;
  }

  /**
   * Returns the item corresponding to the primary key Id.
   *
   * 
   * @return stdClass
   */
  public function getItem($itemID) {

    $itemID = mysql_real_escape_string($itemID);

    $sql = "SELECT * FROM employee where Id=$itemID";

    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());

    $rows = array();
    while ($row = mysql_fetch_object($result)) {
      $rows[] = $row;
    }

    mysql_free_result($result);
    mysql_close($this->connection);

    return $rows;
  }

  /**
   * Returns the item corresponding to the primary key Id.
   *
   * 
   * @return stdClass
   */
  public function createItem($item) {

    $sql = "INSERT INTO employee (f2, DateHired, FirstName, f1, timeSt, Supervisor, Id, LastName) VALUES ($item->f2, '$item->DateHired', '$item->FirstName', '$item->f1', '$item->timeSt', '$item->Supervisor', $item->Id, '$item->LastName')";

    mysql_query($sql) or die('Query failed: ' . mysql_error());

    $autoid= mysql_insert_id($this->connection);
    mysql_close($this->connection);

    return $autoid;
  }

  /**
   * Updates the passed item in the table employee.
   *
   * @param stdClass $item
   * @return void
   */
  public function updateItem($item) {

    $sql = "UPDATE employee SET f2 = $item->f2, DateHired = '$item->DateHired', FirstName = '$item->FirstName', f1 = '$item->f1', timeSt = '$item->timeSt', Supervisor = '$item->Supervisor', Id = $item->Id, LastName = '$item->LastName'
      WHERE  Id = $item->Id";

    mysql_query($sql) or die('Query failed: ' . mysql_error());

    mysql_close($this->connection);

  }

  /**
   * Deletes the item corresponding to the passed primary key value from 
   * the table employee.
   *
   * 
   * @return void
   */
  public function deleteItem($itemID) {
    $itemID = mysql_real_escape_string($itemID);
    $sql = "DELETE FROM employee WHERE Id = $itemID";

    mysql_query($sql) or die('Query failed: ' . mysql_error());

    mysql_close($this->connection);
  }
  
  
  /**
   * Returns the number of rows in the table employee.
   *
   * 
   */
  public function count() {
    $sql = "SELECT * FROM employee";

    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    $rec_count = mysql_num_rows($result);

    mysql_free_result($result);
    mysql_close($this->connection);

    return $rec_count;
  }


  /**
   * Returns $numItems rows starting from the $startIndex row from the 
   * table employee
   *
   * 
   * 
   * @return array
   */
  public function getItems_paged($startIndex, $numItems) {

    $startIndex = mysql_real_escape_string($startIndex);
    $numItems = mysql_real_escape_string($numItems);

    $sql = "SELECT * FROM employee LIMIT $startIndex, $numItems";

    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());

    $rows = array();
    while ($row = mysql_fetch_object($result)) {
      $rows[] = $row;
    }

    mysql_free_result($result);
    mysql_close($this->connection);

    return $rows;
  }

}

?>