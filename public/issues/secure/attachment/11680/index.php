<?php

require_once '/var/www/library/Zend/Search/Lucene.php';

Zend_Search_Lucene::create('/tmp/index');

// Doesn't work when including a number:
$searchTerm = '12stones';

$index = Zend_Search_Lucene::open('/tmp/index');

$document = new Zend_Search_Lucene_Document();
$document->addField(Zend_Search_Lucene_Field::Text('test', $searchTerm));

$index->addDocument($document);
$index->commit();

$query = new Zend_Search_Lucene_Search_Query_MultiTerm();
$query->addTerm(new Zend_Search_Lucene_Index_Term($searchTerm, 'test'), true);

$hits = $index->find($query);

echo 'Hits:';

// For my use case, this should return one hit, however, it currently returns none:
foreach($hits as $hit)
{
	var_dump($hit->test);
}