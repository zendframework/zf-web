<?php
if (!preg_match('#/issues/browse/(ZF\d?-\d+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    return false;
}

echo include(__DIR__ . '/issues/browse/' . $matches[1] . '.phtml');
