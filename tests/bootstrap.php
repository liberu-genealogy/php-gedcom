<?php

// Composer autoloading
$vendor = realpath(__DIR__ . '/../vendor');

if (file_exists($vendor . '/autoload.php')) {
    $loader = include $vendor . '/autoload.php';
}

define('TEST_DIR', __DIR__);
