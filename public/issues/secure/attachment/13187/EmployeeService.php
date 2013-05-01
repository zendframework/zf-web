<?php 
/** 
 * This sample service contains functions that illustrate typical
 * service operations. This code is for prototyping only. 
 *  
 *  Authenticate users before allowing them to call these methods. 
 */ 

class EmployeeService { 
  var $username = "test"; 
  var $password = "test"; 
  var $server = "localhost"; 
  var $port = "3306"; 
  var $databasename = "testdrive_db"; 
  var $tablename = "employees"; 
  
  var $connection; 
  public function __construct() { 
    $this->connection = mysqli_connect( 
                       $this->server,  
                       $this->username,  
                       $this->password, 
                       $this->databasename, 
                       $this->port 
                       ); 
    
    $this->throwExceptionOnError($this->connection); 
  } 

  public function getEmployees() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              employees.id,
              employees.firstname,
              employees.lastname,
              employees.title,
              employees.departmentid,
              employees.officephone,
              employees.cellphone,
              employees.email,
              employees.street,
              employees.city,
              employees.state,
              employees.zipcode,
              employees.office,
              employees.photofile
           FROM employees");     
         
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->id, $row->firstname,
                    $row->lastname, $row->title, $row->departmentid,
                    $row->officephone, $row->cellphone, $row->email,  
                    $row->street, $row->city, $row->state, 
                    $row->zipcode, $row->office, $row->photofile);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt,  $row->id, $row->firstname,
                    $row->lastname, $row->title, $row->departmentid,
                    $row->officephone, $row->cellphone, $row->email,  
                    $row->street, $row->city, $row->state, 
                    $row->zipcode, $row->office, $row->photofile);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  

  public function getDepartments() {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              departments.id,
              departments.name,
              departments.manager,
              departments.costcenter,
              departments.businessunit,
	      departments.budget,
	      departments.actualexpenses,
	      departments.estsalary,
              departments.actualsalary,
              departments.esttravel,
              departments.actualtravel,
	      departments.estsupplies,
              departments.actualsupplies,
              departments.estcontractors,
              departments.actualcontractors
           FROM departments");
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->id, $row->name,
                    $row->manager, $row->costcenter, $row->businessunit,
		    $row->budget, $row->actualexpenses,$row->estsalary, $row->actualsalary,
		    $row->esttravel, $row->actualtravel,$row->estsupplies, $row->actualsupplies,
                    $row->estcontractors, $row->actualcontractors);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->id, $row->name,
                         $row->manager, $row->costcenter, $row->businessunit,
                         $row->budget, $row->actualexpenses,$row->estsalary, $row->actualsalary,
		    $row->esttravel, $row->actualtravel,$row->estsupplies, $row->actualsupplies,
                    $row->estcontractors, $row->actualcontractors);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;
  }  


  public function getEmployeesByID($itemID) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              employees.title,
              employees.street,
              employees.id,
              employees.firstname,
              employees.lastname,
              employees.cellphone,
              employees.departmentid,
              employees.zipcode,
              employees.office,
              employees.email,
              employees.state,
              employees.officephone,
              employees.photofile,
              employees.city
           FROM employees where employees.id=?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 'i', $itemID);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->title, $row->street, $row->id, 
                              $row->firstname, $row->lastname, $row->cellphone,
                              $row->departmentid, $row->zipcode, $row->office, 
                              $row->email, $row->state, $row->officephone ,
                              $row->photofile, $row->city);

      if (mysqli_stmt_fetch($stmt)) {
                  return $row;
      } else {
                  return null;
          }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

  }  

  public function getEmployeesByName($searchStr) {
     $stmt = mysqli_prepare($this->connection,
          "SELECT
              employees.title,
              employees.street,
              employees.id,
              employees.firstname,
              employees.lastname,
              employees.cellphone,
              employees.departmentid,
              employees.zipcode,
              employees.office,
              employees.email,
              employees.state,
              employees.officephone,
              employees.photofile,
              employees.city
           FROM employees where employees.lastName LIKE ?");
      $this->throwExceptionOnError();
          
      mysqli_stmt_bind_param($stmt, 's', $searchStr);
      $this->throwExceptionOnError();

      mysqli_stmt_execute($stmt);
      $this->throwExceptionOnError();

      $rows = array();
      mysqli_stmt_bind_result($stmt, $row->title, $row->street, $row->id, 
                              $row->firstname, $row->lastname, $row->cellphone,
                              $row->departmentid, $row->zipcode, $row->office, 
                              $row->email, $row->state, $row->officephone ,
                              $row->photofile, $row->city);

      while (mysqli_stmt_fetch($stmt)) {
          $rows[] = $row;
          $row = new stdClass();
          mysqli_stmt_bind_result($stmt, $row->title, $row->street, $row->id, 
                              $row->firstname, $row->lastname, $row->cellphone,
                              $row->departmentid, $row->zipcode, $row->office, 
                              $row->email, $row->state, $row->officephone ,
                              $row->photofile, $row->city);
      }

      mysqli_stmt_free_result($stmt);
      mysqli_close($this->connection);

      return $rows;

  }  

   public function createEmployee($item) {	$stmt = mysqli_prepare($this->connection,		"INSERT INTO employees (
			firstname,lastname,title,departmentid,officephone,cellphone, 	
			email,street,city,state,zipcode,office,photofile) 
		VALUES (?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?)");	$this->throwExceptionOnError();
	
	mysqli_bind_param($stmt, 'sssisssssssss', $item->firstname, $item->lastname,		$item->title, $item->departmentid, $item->officephone, $item->cellphone,
		$item->email, $item->street, $item->city, $item->state,
		$item->zipcode, $item->office, $item->photofile
	);	$this->throwExceptionOnError();

	mysqli_stmt_execute($stmt);	$this->throwExceptionOnError();
	
	$autoid = mysqli_stmt_insert_id($stmt);
	
	mysqli_stmt_free_result($stmt);	mysqli_close($this->connection);	
	return $autoid;  }

  public function deleteEmployee($itemID) {	$stmt = mysqli_prepare($this->connection,		"DELETE FROM employees WHERE id = ?");	$this->throwExceptionOnError();
	
	mysqli_bind_param($stmt, 'i', $itemID);
	mysqli_stmt_execute($stmt);	$this->throwExceptionOnError();
	mysqli_stmt_free_result($stmt);	mysqli_close($this->connection);  }

  public function updateEmployee($item) {	$stmt = mysqli_prepare($this->connection,
		"UPDATE employees SET
			firstname=?,lastname=?,title=?,departmentid=?,officephone=?,cellphone=?, 	
			email=?,street=?,city=?,state=?,zipcode=?,office=?,photofile=?
			WHERE id=?");	$this->throwExceptionOnError();

	mysqli_bind_param($stmt, 'sssisssssssssi', $item->firstname, $item->lastname,		$item->title, $item->departmentid, $item->officephone, $item->cellphone,
		$item->email, $item->street, $item->city, $item->state,
		$item->zipcode, $item->office, $item->photofile,$item->id
	);	$this->throwExceptionOnError();
	mysqli_stmt_execute($stmt);	$this->throwExceptionOnError();
	mysqli_stmt_free_result($stmt);	mysqli_close($this->connection);  }

/** 
  * Utitity function to throw an exception if an error occurs 
  * while running a mysql command. 
  */ 
  private function throwExceptionOnError($link = null) { 
    if($link == null) { 
      $link = $this->connection; 
    } 
    if(mysqli_error($link)) { 
      $msg = mysqli_errno($link) . ": " . mysqli_error($link); 
      throw new Exception('MySQL Error - '. $msg); 
    }         
  } 
 
} 
?>
