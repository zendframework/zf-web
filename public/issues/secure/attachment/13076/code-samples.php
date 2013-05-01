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

/**
 * After short while we're on line: 158. Yes that's the empty one and that's
 * where I placed var_dump that gave a result like below:
 */

/*

array(4) {
  ["controller"]=>
  string(4) "page"
  ["action"]=>
  string(5) "index"
  ["page"]=>
  string(7) "contact"
  ["module"]=>
  string(7) "default"
}

*/

/**
 * In that case function used on line: 159 which is: array_intersect_assoc returned
 * FALSE. Which is obviously not true. Or I'm wrong ?
 */

/**
 * PATH PATH PATH ! How to fix !?
 * Happily replace code in your Zend/Navigation/Page/Mvc.php to one shown below:
 */

/**
 * LINE 139 WAS:
 */

$myParams = $this->_params;

/**
 * REPLACE WITH:
 */

$myParams = array();

/**
 * LINE 158 ADD THIS:
 */

if(isset($reqParams['params'])){
	$testParams = array_diff($this->_params, $reqParams['params']);
	if(!empty($testParams)){
		// params in request are different
		return false;
	}
}elseif (!empty($this->_params)){
	// request doesnt have any params but our array is not empty
	return false;
}else{/* no params neither here or in request - continue */}

/**
 * DONE ! Now its working as expected.
 */