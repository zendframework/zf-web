<?php
define('BENCHMARK_ITERATIONS', 10000);

function isAssocCallback($a, $b) {
	return $a === $b ? $a + 1 : 0;
}

$value = array(
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
	uniqid(), uniqid(), uniqid(), uniqid(), uniqid(), uniqid(),
);

// Make sure initialization does not change result
$start = 0;
$a = 0;

$start = microtime(true);
for ($a = 0; $a < BENCHMARK_ITERATIONS; ++$a) {
	$result = (count($value) === array_reduce(array_keys($value), 'isAssocCallback', 0));
	assert($result);
}
$end = microtime(true);
echo "array_reduce(): " . ($end - $start) . "\n";

$start = microtime(true);
for ($a = 0; $a < BENCHMARK_ITERATIONS; ++$a) {
	$result = (array_keys($value) === range(0, count($value) -1));
	assert($result);
}
$end = microtime(true);
echo "count()/range(): " . ($end - $start) . "\n";
