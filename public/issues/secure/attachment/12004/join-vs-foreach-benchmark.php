<?php
define('BENCHMARK_ITERATIONS', 100);

$contentElements = array();

for ($i = 0; $i < 100; ++$i) {
    $contentElements['slot_' . $i] = str_repeat('x', 128);
}

$x = 0;
$start = 0;
$end = 0;


$start = microtime(true);
for ($x = 0; $x < BENCHMARK_ITERATIONS; ++$x) {
    echo join("", $contentElements);
}
$end = microtime(true);
fwrite(STDERR, "join(): " . ($end - $start) . "ms\n");

$start = microtime(true);
for ($x = 0; $x < BENCHMARK_ITERATIONS; ++$x) {
    foreach ($contentElements as $element) {
        echo $element;
    }
}
$end = microtime(true);
fwrite(STDERR, "foreach(): " . ($end - $start) . "ms\n");
