Index: Unix.php
===================================================================
--- Unix.php	(revision 102)
+++ Unix.php	(working copy)
@@ -275,6 +275,7 @@
             
             posix_kill($this->_pid, 9);
             pcntl_waitpid($this->_pid, $status, WNOHANG);
+            $this->_setFinished();
             $success = pcntl_wifexited($status);
             $this->_cleanProcessContext();
         }
@@ -291,6 +292,38 @@
     {       
         return $this->_isRunning;
     }
+    
+    /**
+     * Test if the pseudo-thread is already finished.
+     *
+     * @return boolean
+     */
+    public function isFinished()
+    {       
+        return (bool)$this->getVariable('_finished');
+    }     
+     
+    /**
+     * Return the time when the process finished, otherwise 0.
+     *
+     * @return integer
+     */
+    public function getFinished()
+    {       
+        $finished = $this->getVariable('_finished');
+        return ($finished == null ? 0 : $finished);
+    }
+    
+    /**
+     * Set a pseudo-thread property that can be read from parent process
+     * in order to know the child activity.
+     *
+     * @return void
+     */
+    protected function _setFinished()
+    {
+        $this->_writeVariable('_finished', true);	
+    }
 
     /**
      * Set a variable into the shared memory segment, so that it can accessed
