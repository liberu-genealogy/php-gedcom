<?php

/**
 * Test script for UID parsing and GedcomX export
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Gedcom\Parser;
use Gedcom\GedcomX\Generator;
use Gedcom\Writer;

echo "Testing UID parsing and GedcomX export...\n\n";

// Parse the test GEDCOM file
$parser = new Parser();
$gedcom = $parser->parse(__DIR__ . '/test_uid.ged');

echo "=== Parsed GEDCOM ===\n";
$individuals = $gedcom->getIndi();
echo "Found " . count($individuals) . " individuals\n\n";

foreach ($individuals as $indi) {
    echo "Individual: " . $indi->getId() . "\n";
    $names = $indi->getName();
    if (!empty($names)) {
        echo "  Name: " . $names[0]->getName() . "\n";
    }
    
    $uids = $indi->getAllUid();
    if (!empty($uids)) {
        echo "  _UID values: " . implode(', ', $uids) . "\n";
    }
    
    $uids7 = $indi->getAllUid7();
    if (!empty($uids7)) {
        echo "  UID values: " . implode(', ', $uids7) . "\n";
    }
    
    echo "\n";
}

// Test backward compatibility
echo "=== Backward Compatibility Test ===\n";
$indi = $individuals[0];
echo "First individual getUid() (should return first _UID): " . $indi->getUid() . "\n\n";

// Generate GedcomX
echo "=== GedcomX Export ===\n";
$generator = new Generator($gedcom);
$json = $generator->generate();
$data = json_decode($json, true);

echo "Generated GedcomX JSON:\n";
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

// Check identifiers in persons
if (isset($data['persons'])) {
    foreach ($data['persons'] as $person) {
        echo "Person ID: " . $person['id'] . "\n";
        if (isset($person['identifiers'])) {
            echo "  Identifiers:\n";
            foreach ($person['identifiers'] as $type => $values) {
                echo "    $type:\n";
                foreach ($values as $value) {
                    echo "      - $value\n";
                }
            }
        } else {
            echo "  No identifiers\n";
        }
        echo "\n";
    }
}

// Test writing back to GEDCOM
echo "=== Write Back to GEDCOM ===\n";
$writer = new Writer();
$output = $writer->convert($gedcom);
echo "Written GEDCOM (first 20 lines):\n";
$lines = explode("\n", $output);
for ($i = 0; $i < min(20, count($lines)); $i++) {
    echo $lines[$i] . "\n";
}

echo "\n=== Test Complete ===\n";
