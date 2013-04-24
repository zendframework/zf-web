Index: Activemq.php
===================================================================
--- Activemq.php	(revision 17472)
+++ Activemq.php	(working copy)
@@ -54,6 +54,7 @@
      * @var Zend_Queue_Adapter_Stomp_client
      */
     private $_client = null;
+    private $_isConnected;
 
     /**
      * Constructor
@@ -65,7 +66,7 @@
     public function __construct($options, Zend_Queue $queue = null)
     {
         parent::__construct($options);
-
+        $this->isConnected = 0;
         $options = &$this->_options['driverOptions'];
         if (!array_key_exists('scheme', $options)) {
             $options['scheme'] = self::DEFAULT_SCHEME;
@@ -195,13 +196,15 @@
         if ($queue === null) {
             $queue = $this->_queue;
         }
-
-        // signal that we are reading
+         if ($this->isConnected == 0)
+       { // signal that we are reading
         $frame = $this->_client->createFrame();
         $frame->setCommand('SUBSCRIBE');
         $frame->setHeader('destination', $queue->getName());
         $frame->setHeader('ack','client');
         $this->_client->send($frame);
+        $this->isConnected = 1;
+       }
 
         // read
         $data = array();
