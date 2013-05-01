<?php

/**
    Mysql Server version: 5.1.41
 
 SCHEMA: 

CREATE TABLE IF NOT EXISTS `blobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blob_data` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

 DATA:
 
 70000 BYTES created from: http://www.lipsum.com/



 */

set_include_path('/Users/ralphschindler/Projects/ZFStandardTrunk/library');

$username = 'developer';
$password = 'developer';
$dbname   = 'test';

// ZEND_DB
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
$dbAdapter = Zend_Db::factory(
    'Mysqli',  
    array('username'=>$username,'password'=>$password,'dbname'=>$dbname)
    );
Zend_Db_Table::setDefaultAdapter($dbAdapter);
$blobDataZ = $dbAdapter->fetchOne('SELECT blob_data FROM blobs where id = 1');
print "ZEND_DB BLOB_DATA LEN=".strlen($blobDataZ);
print PHP_EOL;

// MYSQLI
$link = mysqli_connect(null, $username, $password, $dbname);
$result = mysqli_query($link, 'SELECT blob_data FROM blobs WHERE id = 1');
$data = mysqli_fetch_assoc($result);
$blobDataM = $data['blob_data'];
mysqli_close($link);
print "MYSQLI BLOB_DATA LEN=".strlen($blobDataM);
print PHP_EOL;


for ($i=0; $i < 5; $i++) {
  print PHP_EOL . PHP_EOL;
  print "POS=".$i;
  print PHP_EOL;
  print "SUBSTR_ZF=".substr($blobDataZ, $i, 1).", ".ord(substr($blobDataZ, $i, 1));
  print PHP_EOL;
  print "SUBSTR_MYSQLI=".substr($blobDataM, $i, 1).", ".ord(substr($blobDataM, $i, 1));
}