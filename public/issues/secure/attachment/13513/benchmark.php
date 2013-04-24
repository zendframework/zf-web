<?php

require_once '/var/www/teenhollywood2/library/Zend/Http/Client.php';
require_once '/var/www/teenhollywood2/library/Zend/Mime.php';

if (!function_exists('imap_8bit')) {
   die('This test requires that the PHP IMAP extension is enabled');
}
if (!function_exists('quoted_printable_encode')) {
   die('This test requires PHP 5.3+');
}

define('LOOPS', 5000);

$client = new Zend_Http_Client("http://de.wikipedia.org/w/index.php?title=PHP&printable=yes");

$string = $client->request()->getBody();

echo "===================================================================\n";
echo "RUNNING BENCHMARK\n";
echo "===================================================================\n";

echo "Starting encoding with imap_8bit() (" . LOOPS . " times)\n";
$startTime = microtime(true);
for ($i = 0; $i < LOOPS; $i++){
   imap_8bit($string);
}
$durationImap_8bit = microtime(true)-$startTime;
echo "Duration: " . round($durationImap_8bit,4) . " seconds\n\n";

echo "Starting encoding with Zend_Mime::encodeQuotedPrintable() (" . LOOPS . " times)\n";
$startTime = microtime(true);
for ($i = 0; $i < LOOPS; $i++){
   Zend_Mime::encodeQuotedPrintable($string, Zend_Mime::LINELENGTH, Zend_Mime::LINEEND);
}
$durationZendMime = microtime(true)-$startTime;
echo "Duration: " . round($durationZendMime,4) . " seconds\n\n";

echo "Starting encoding with quoted_printable_encode() (" . LOOPS . " times)\n";
$startTime = microtime(true);
for ($i = 0; $i < LOOPS; $i++){
   quoted_printable_encode($string);
}
$durationQPE = microtime(true)-$startTime;
echo "Duration: " . round($durationQPE,4) . " seconds\n\n";

echo "===================================================================\n";
echo "SUMMARY\n";
echo "===================================================================\n";

echo "imap_8bit() is " . round($durationZendMime-$durationImap_8bit, 4) . " seconds faster than Zend_Mime!\n";
echo "It is " . round($durationZendMime/$durationImap_8bit, 2) . " times faster\n\n";

echo "quoted_printable_encode() is " . round($durationZendMime-$durationQPE, 4) . " seconds faster than Zend_Mime!\n";
echo "It is " . round($durationZendMime/$durationQPE, 2) . " times faster\n\n";

