<?php
/**
 * Test Zend Framework Requirements
 * 
 * @author  Kristof Vansant
 * @version 1.0
 */

$list = array(
		 array(
		 'extension'=>'apc',
         'required' => 'Hard',
	     'modules' => array('Zend_Cache_Backend_Apc')
		 ),

		 array(
		 'extension' => 'bcmath',
		 'required' => 'Soft',
	     'modules' => array('Zend_Locale')
		 ),

		 array(
		 'extension' => 'bitset',
		 'required' => 'Soft',
	     'modules' => array('Zend_Search_Lucene')),

		 array(
		 'extension' => 'ctype',
		 'required' => 'Hard',
	     'modules' => array('Zend_Auth_Adapter_Http',
				 'Zend_Gdata Zend_Http_Client',
				 'Zend_Pdf',
				 'Zend_Rest_Client',
				 'Zend_Rest_Server', 
				 'Zend_Search_Lucene',
				 'Zend_Uri',
				 'Zend_Validate')),

		 array(
		 'extension' => 'curl',
		 'required' => 'Hard',
		'modules' => array ('Zend_Http_Client_Adapter_Curl')), 

		array(
		'extension' => 'dom',
		'required' => 'Hard',
		'modules' => array('Zend_Feed',
				 'Zend_Gdata', 
				'Zend_Log_Formatter_Xml',
				'Zend_Rest_Server',
				'Zend_Search_Lucene',
				'Zend_Service_Amazon',
				'Zend_Service_Delicious',
				'Zend_Service_Flickr',
				'Zend_Service_Simpy',
				'Zend_Service_Yahoo',
				'Zend_XmlRpc')),
		array(
		'extension' => 'gd',
		'required'=>'Hard',
		'modules' => array('Zend_Pdf')),
		array(
		'extension' => 'hash',
		'required' => 'Hard',
		'modules' => array('Zend_Auth_Adapter_Http')),
		array(
		'extension' => 'ibm_db2',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Db2')),
		array(
		'extension' => 'iconv',
		'required' => 'Hard',
		'modules' => array('Zend_Currency',
					'Zend_Locale_Format', 
					'Zend_Mime', 
					'Zend_Pdf', 
					'Zend_Search_Lucene', 
					'Zend_Service_Audioscrobbler', 
					'Zend_Service_Flickr',
					'Zend_XmlRpc_Client')), 
		array(
		'extension' => 'interbase',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Firebird')), 
		array(
		'extension' => 'json',
		'required' => 'Soft',
		'modules' => array('Zend_Json')), 
		array(
		'extension' => 'libxml',
		'required' => 'Hard',
		'modules' => array('DOM','SimpleXML','XSLT')),
		array(
		'extension' => 'mbstring',
		'required' => 'Hard',
		'modules' => array('Zend_Feed')), 
		array(
		'extension' => 'memcache',
		'required' => 'Hard',
		'modules' => array('Zend_Cache_Backend_Memcached')), 
		array(
		'extension' => 'mime_magic',
		'required' => 'Hard',
		'modules' => array('Zend_Http_Client')), 
		array(
		'extension' => 'mysqli',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Mysqli')), 
		array(
		'extension' => 'oci8',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Oracle')), 
		array(
		'extension' => 'pcre',
		'required' => 'Hard',
		'modules' => array('Virtually all components')),
		 array(
		 'extension' => 'pdo',
		 'required' => 'Hard',
		 'modules' => array ('All PDO database adapters')), 
		array(
		'extension' => 'pdo_mssql',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Pdo_Mssql')), 
		array(
		'extension' => 'pdo_mysql',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Pdo_Mysql')), 
		array(
		'extension' => 'pdo_oci',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Pdo_Oci')), 
		array(
		'extension' => 'pdo_pgsql',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Pdo_Pgsql')), 
		array(
		'extension' => 'pdo_sqlite',
		'required' => 'Hard',
		'modules' => array('Zend_Db_Adapter_Pdo_Sqlite')), 
		array(
		'extension' => 'posix',
		'required' => 'Soft',
		'modules' => array('Zend_Mail')),
		 array(
		 'extension'=>'Reflection',
		 'required' => 'Hard',
		 'modules' => array('Zend_Controller','Zend_Filter','Zend_Filter_Input','Zend_Json','Zend_Log','Zend_Rest_Server','Zend_Server_Reflection','Zend_Validate', 'Zend_View','Zend_XmlRpc_Server')), 
		array(
		'extension'=> 'session',
		'required' => 'Hard',
		'modules' => array('Zend_Controller_Action_Helper_Redirector','Zend_Session')), 
		array(
		'extension'=> 'SimpleXML',
		'required'=> 'Hard',
		'modules' => array('Zend_Config_Xml','Zend_Feed','Zend_Rest_Client','Zend_Service_Audioscrobbler','Zend_XmlRpc')),
		 array(
		 'extension'=> 'soap',
		 'required' => 'Hard',
		 'modules'=> array('Zend_Service_StrikeIron')), 
		 array(
		 'extension'=>'SPL',
		 'required'=> 'Hard',
		 'modules' => array('Virtually all components')), 
		 array(
		 'extension' =>'SQLite',
		 'required'=> 'Hard', 
		 'modules' => array('Zend_Cache_Backend_Sqlite')), 
		array(
		'extension' =>'standard',
		'required'=> 'Hard',
		'modules' => array('Virtually all components')), 
		array(
		'extension' =>'xml',
		'required' => 'Hard',
		'modules'=> array('Zend_Translate_Adapter_Qt','Zend_Translate_Adapter_Tmx','Zend_Translate_Adapter_Xliff')), 
		array(
		'extension' => 'zlib',
		'required' => 'Hard',
		'modules'=> array ('Zend_Pdf','Memcache'))); 
		
		
		
		
$extensions = get_loaded_extensions();

?>
<table>
<tr>
	<th>Extension</th>
	<th>Depended modules</th>
</tr>
<?php

echo "<tr>";
if (version_compare(PHP_VERSION, '5.1.4') === -1) {
    echo "<th bgcolor='#FF0000'>" . PHP_VERSION . "</th><th> 5.1.4 is the minimum 5.2.3 is recommended </th>";
}elseif (version_compare(PHP_VERSION, '5.2.3') === -1) {
    echo "<th bgcolor='#F88017'>" . PHP_VERSION . "</th><th> 5.2.3 is recommended </th>";
}else{
     echo "<th bgcolor='#00FF00'>" . PHP_VERSION . "</th><th> Perfect </th>";
}

echo "</tr>";
foreach($list as $item){
echo "<tr>";
	if (in_array($item['extension'], $extensions, true)) {
		echo "<th bgcolor='#00FF00'>". $item['extension'] ."</th>" ;
    	//print $item['extension'] . " found <br/>";
	} else {
		if ($item['required'] == 'Hard'){
			echo "<th bgcolor='#FF0000'>". $item['extension']."</th>";
		} else {
			echo "<th bgcolor='#F88017'>". $item['extension']."</th>";
		}
		//print $item['extension'] . " not found <br />";
		
		
	}
	
echo "<th>";
foreach($item['modules'] as $module){
	echo $module . "<br />";
			
}
echo "</th>";
}


echo "</tr>";
?>

</table>
<br/>
Made by Kristof Vansant
