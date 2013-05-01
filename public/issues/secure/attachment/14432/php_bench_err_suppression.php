<?php

$max = 100000;
$i   = 0;

$globalLastErrorMsg=null;
function tmpErrorHandler($errno, $errstr, $errfile, $errline)
{
    global $globalLastErrorMsg;
    $globalLastErrorMsg = $errstr;
}

function noError()
{
    return true;
}

function triggerError()
{
    trigger_error('Test Error Message');
    return false;
}



echo 'error suppression (no error):       ';
$start = microtime(true);
for ($i=0; $i<$max; $i++) {
    $rs = @noError();
    if (!$rs) {
        // never called
    }
}
$end = microtime(true);
echo ($end-$start) . PHP_EOL;


echo 'error handler     (no error):       ';
$start = microtime(true);
for ($i=0; $i<$max; $i++) {
    set_error_handler('tmpErrorHandler');
    $rs = noError();
    restore_error_handler();
    if (!$rs) {
        // never called
    }
}
$end = microtime(true);
echo ($end-$start) . PHP_EOL;


echo 'error suppression (use last error): ';
$start = microtime(true);
for ($i=0; $i<$max; $i++) {
    $rs = @triggerError();
    if (!$rs) {
        $err = error_get_last();
	$globalLastErrorMsg = $err['message'];
	// do somethink with $globalLastErrorMsg
    }
}
$end = microtime(true);
echo ($end-$start) . PHP_EOL;


echo 'error handler     (use last error): ';
$start = microtime(true);
for ($i=0; $i<$max; $i++) {
    set_error_handler('tmpErrorHandler');
    $rs = triggerError();
    restore_error_handler();
    if (!$rs) {
        // do somethink with $globalLastErrorMsg
    }
}
$end = microtime(true);
echo ($end-$start) . PHP_EOL;

