<?php
  require('includes/application_top.php');
  require('zend.php');
  Zend::loadClass('Zend_Search_Lucene');
  $index = new Zend_Search_Lucene('/Inetpub/wwwroot/aa', true);
  $doc = new Zend_Search_Lucene_Document();
  // Store document URL to identify it in search result.
  $q = $db->query("select * from products");
  while($r = $db->fetch($q)) {
    $doc->addField(Zend_Search_Lucene_Field::Text('products_name', strtolower($r['products_name'])));
	$doc->addField(Zend_Search_Lucene_Field::UnStored('contents', strtolower($r['products_description'])));
    $index->addDocument($doc);
  }
  $index->commit();
  
  $index = new Zend_Search_Lucene('/Inetpub/wwwroot/aa');
  $hits  = $index->find('punch');
  
  foreach ($hits as $hit) {    
	echo $hit->score;
	echo '<br>';
	echo $hit->products_name;
	echo '<br>';
  }
  
?>