<?php

/*foreach($_POST as $key => $value)
echo "variable $key had value $value\n";*/

echo($GLOBALS["HTTP_RAW_POST_DATA"]); 

?>
