<?php
ini_set("include_path", ini_get('include_path').';C:\Apache2\htdocs\ZendFramework-0.1.3\library\;');
include 'Zend.php';

function __autoload($class)
{
    Zend::loadClass($class);
}

try {

// Setting the second argument to TRUE creates a new index
$index = new Zend_Search_Lucene('C:\Apache2\htdocs\zend_framework_tests\test-index', true);

$doc = new Zend_Search_Lucene_Document();

// Store document URL to identify it in search result.
$doc->addField(Zend_Search_Lucene_Field::UnIndexed('url', 'http://example.com'));

// Index document content
$doc->addField(Zend_Search_Lucene_Field::Text('contents', 'example content for tests kółko ściółka '));

// Add document to the index.
$index->addDocument($doc);

// Write changes to the index.
$index->commit();


//require_once('Zend/Search/Lucene.php');

$index = new Zend_Search_Lucene('C:\Apache2\htdocs\zend_framework_tests\test-index');
$query = 'tests';
$hits = $index->find($query);

foreach ($hits as $hit) {
    echo $hit->id+1
         . ". {$hit->contents}\n{$hit->url}\n\n";
}

} catch(Exception $e) {
    var_export($e);
}

?>