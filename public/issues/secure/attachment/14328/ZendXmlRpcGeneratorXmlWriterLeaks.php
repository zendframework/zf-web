<pre>
<?php
/**
 * Zend_XmlRpc_Generator_XmlWriter->saveXml()
 * fix   
 *   $this->_xmlWriter->flush(false)
 * to
 *   $this->_xmlWriter->flush(true);   
 */
// installed framework
set_include_path(
  realpath('../src/ZendFramework/library')
);
// required classes
require_once('Zend/XmlRpc/Generator/XmlWriter.php');
require_once('Zend/XmlRpc/Value.php');
// be sure to use the leaky generator
$generator = new Zend_XmlRpc_Generator_XmlWriter();
Zend_XmlRpc_Value::setGenerator($generator);
// test a thousand times
for( $i = 1000; $i > 0; $i-- ){
  // make a thousand stars
  $bug = str_pad('', 1000, '*');
  // factorize value
  $bug = Zend_XmlRpc_Value::getXmlRpcValue($bug, Zend_XmlRpc_Value::XMLRPC_TYPE_STRING);
  // save value, this leaks!
  $bug = $bug->saveXml();
  // report sometimes
  if( $i%100 === 0 ){
    $mem = floor(memory_get_usage()/1024);
    echo "KB $mem\n";
  }
  // print once
  if( $i === 1 ){    
    echo htmlentities($bug);
  }
  // clean
  unset($bug);
}
?>
</pre>