--- Query.php	2010-04-19 12:11:14.063945937 -0400
+++ Query.php	2010-04-19 12:28:15.000000000 -0400
@@ -56,7 +56,6 @@

     /**
      * Base URL
-     * TODO: Add setters and getters
      *
      * @var string
      */
@@ -78,6 +77,24 @@
     }

     /**
+     * @return string queryUrl
+     */
+    public function getQueryUrl()
+    {
+        return $this->_url;
+    }
+
+    /**
+     * @param $url Gdata Base URL
+     * @return Zend_Gdata_Query Provides a fluent interface
+     */
+    public function setQueryUrl($url)
+    {
+        $this->_url = $url;
+        return $this;
+    }
+
+    /**
      * @return string querystring
      */
     public function getQueryString()
