<?php

/**
 * Simple test to verify Writer format detection without needing the full parser
 * This tests only the Writer class changes for GEDCOM 7.0 support
 */

// Minimal autoloader for just the Writer class and its dependencies
spl_autoload_register(function ($class) {
    $prefix = 'Gedcom\\';
    $baseDir = __DIR__ . '/../src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

echo "Testing GEDCOM Writer Version Support\n";
echo "=====================================\n\n";

// Test 1: Check constants exist
echo "Test 1: Checking GEDCOM format constants\n";
echo "-----------------------------------------\n";
try {
    $gedcom55 = \Gedcom\Writer::GEDCOM55;
    $gedcom70 = \Gedcom\Writer::GEDCOM70;
    echo "✓ GEDCOM55 constant: " . $gedcom55 . "\n";
    echo "✓ GEDCOM70 constant: " . $gedcom70 . "\n";
} catch (Exception $e) {
    echo "✗ Failed to access constants: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check format helper methods exist
echo "Test 2: Checking format helper methods\n";
echo "---------------------------------------\n";
try {
    // We can't directly test these without calling convert(), but we can check they exist
    $methods = get_class_methods(\Gedcom\Writer::class);
    $requiredMethods = ['getCurrentFormat', 'isGedcom70', 'isGedcom55'];
    
    foreach ($requiredMethods as $method) {
        if (in_array($method, $methods)) {
            echo "✓ Method exists: " . $method . "\n";
        } else {
            echo "✗ Method missing: " . $method . "\n";
        }
    }
} catch (Exception $e) {
    echo "✗ Failed to check methods: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Verify format constants are different
echo "Test 3: Verifying format constants are unique\n";
echo "----------------------------------------------\n";
if (\Gedcom\Writer::GEDCOM55 !== \Gedcom\Writer::GEDCOM70) {
    echo "✓ GEDCOM55 and GEDCOM70 constants are different\n";
    echo "  GEDCOM55: " . \Gedcom\Writer::GEDCOM55 . "\n";
    echo "  GEDCOM70: " . \Gedcom\Writer::GEDCOM70 . "\n";
} else {
    echo "✗ GEDCOM55 and GEDCOM70 constants are the same!\n";
}

echo "\n";

// Note about full testing
echo "Note: Full integration tests require PHP 8.4+ due to property hooks in Parser.php\n";
echo "Once PHP 8.4 is available, run test_gedcom70.php for complete validation.\n";

echo "\n";
echo "=====================================\n";
echo "Writer tests completed!\n";
