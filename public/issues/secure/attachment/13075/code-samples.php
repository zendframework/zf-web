<?php

/**
 * How to reproduce this issue in a few quick steps:
 * 
 * 1. Create main MVC-navigation page, define controller, action.
 * 2. Create a few sub-pages for that page using the same action and controller
 * adding some params, each different (see attached xml-config file i used).
 * 3. Render breadcrumbs and menu in your view file.
 * 
 * What you should see now is that breadcrumbs for second-level navigation elements
 * are not created. Only the first-level label will appear.
 * 
 * Now let's see what happend inside Zend/Navigation/Page/Mvc.php during that time:
 */

/**
 * Request params first appear on line: 133
 */

$reqParams = $front->getRequest()->getParams();

/*

var_dump() gives something like:

array(4) {
  ["controller"]=>
  string(4) "page"
  ["action"]=>
  string(5) "index"
  ["params"]=>
  array(1) {
    ["page"]=>
    string(7) "contact"
  }
  ["module"]=>
  string(7) "default"
}

*/

/**
 * Now on line 139 we start to create $myParams array:
 */

$myParams = $this->_params;

