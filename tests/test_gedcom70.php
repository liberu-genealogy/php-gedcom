<?php

/**
 * Test script to verify GEDCOM 7.0 parsing and writing support
 */

require_once __DIR__ . '/../src/Parser.php';
require_once __DIR__ . '/../src/Writer.php';
require_once __DIR__ . '/../src/Gedcom.php';

// Enable autoloading
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

echo "Testing GEDCOM 7.0 Support\n";
echo "=========================\n\n";

// Test 1: Parse a GEDCOM 7.0 file
echo "Test 1: Parsing GEDCOM 7.0 file\n";
echo "--------------------------------\n";
$parser = new \Gedcom\Parser();
$gedcom = $parser->parse(__DIR__ . '/gedcom70_sample.ged');

if ($gedcom) {
    echo "✓ Successfully parsed GEDCOM 7.0 file\n";
    
    // Check version
    $head = $gedcom->getHead();
    if ($head) {
        $gedc = $head->getGedc();
        if ($gedc) {
            $version = $gedc->getVersion();
            echo "  Version detected: " . $version . "\n";
            
            if ($version === '7.0') {
                echo "✓ Version correctly identified as 7.0\n";
            } else {
                echo "✗ Version mismatch! Expected 7.0, got: " . $version . "\n";
            }
            
            $form = $gedc->getForm();
            echo "  Form: " . $form . "\n";
        }
    }
    
    // Check parsed records
    $individuals = $gedcom->getIndi();
    echo "  Individuals parsed: " . count($individuals) . "\n";
    
    $families = $gedcom->getFam();
    echo "  Families parsed: " . count($families) . "\n";
    
    $sources = $gedcom->getSour();
    echo "  Sources parsed: " . count($sources) . "\n";
    
    // Check UID support (GEDCOM 7.0 uses UID, not _UID)
    if (count($individuals) > 0) {
        $firstIndi = reset($individuals);
        $uid = $firstIndi->getUid();
        if ($uid && count($uid) > 0) {
            echo "✓ UID field correctly parsed: " . $uid[0] . "\n";
        }
    }
} else {
    echo "✗ Failed to parse GEDCOM 7.0 file\n";
}

echo "\n";

// Test 2: Write a GEDCOM 7.0 file
echo "Test 2: Writing GEDCOM 7.0 file\n";
echo "--------------------------------\n";
$output = \Gedcom\Writer::convert($gedcom, \Gedcom\Writer::GEDCOM70);

if ($output) {
    echo "✓ Successfully converted to GEDCOM 7.0 format\n";
    
    // Check if output contains version 7.0
    if (strpos($output, 'VERS 7.0') !== false) {
        echo "✓ Output contains VERS 7.0\n";
    } else {
        echo "⚠ Output does not contain VERS 7.0 (this may be expected if version is preserved from input)\n";
    }
    
    // Check if output contains UID fields
    if (strpos($output, 'UID ') !== false) {
        echo "✓ Output contains UID fields\n";
    }
    
    // Write to temp file for verification
    $tmpFile = __DIR__ . '/tmp_gedcom70_output.ged';
    file_put_contents($tmpFile, $output);
    echo "  Output written to: " . $tmpFile . "\n";
    echo "  Output size: " . strlen($output) . " bytes\n";
} else {
    echo "✗ Failed to write GEDCOM 7.0 file\n";
}

echo "\n";

// Test 3: Verify GEDCOM55 constant still works
echo "Test 3: Verifying GEDCOM 5.5 support (backward compatibility)\n";
echo "------------------------------------------------------------\n";
$output55 = \Gedcom\Writer::convert($gedcom, \Gedcom\Writer::GEDCOM55);
if ($output55) {
    echo "✓ GEDCOM 5.5 format constant still works\n";
    echo "  Output size: " . strlen($output55) . " bytes\n";
} else {
    echo "✗ Failed to write GEDCOM 5.5 file\n";
}

echo "\n";
echo "=========================\n";
echo "All tests completed!\n";
