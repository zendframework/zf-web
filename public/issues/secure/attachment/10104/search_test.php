<?php
ini_set("include_path",ini_get("include_path").";./library/");
include_once("./library/Zend.php");
Zend::loadClass("Zend_Search_Lucene");
Zend::loadClass("Zend_Search_Lucene_Document");
Zend::loadClass("Zend_Search_Lucene_Field");
// Setting the second argument to TRUE creates a new index
function getword() {
	$randwords[]="jik";
	$randwords[]="tak";
	$randwords[]="sha";
	$randwords[]="lq";
	$randwords[]="lqlq";
	$randwords[]="lubo";
	$randwords[]="mas";
	$randwords[]="lake";
	$randwords[]="make";
	$randwords[]="a";
	$randwords[]="store";
	$str="";
	for($i=0;$i<10;$i++) {
			$str.=" ".$randwords[rand(0,count($randwords))];
	}
	return rtrim($str);
}
function fill_index() {
	for($i=0;$i<10;$i++) {
		$index = new Zend_Search_Lucene('./data/index', true);
		$index->find("test");
		$doc = new Zend_Search_Lucene_Document();
		$doc->addField(Zend_Search_Lucene_Field::Text("test", getword()));
		$doc->addField(Zend_Search_Lucene_Field::UnStored("contents", getword()));
		$index->addDocument($doc);
		$index->commit();
		$index->getDirectory()->close(); //comment this to see another bug :-|
	}
}
fill_index();
?>
