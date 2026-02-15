<?php

/**
 * Test script for UID parsing and writing across all record types
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Gedcom\Parser;
use Gedcom\Writer;

echo "Testing UID support for all GEDCOM record types...\n\n";

// Parse the test GEDCOM file
$parser = new Parser();
$gedcom = $parser->parse(__DIR__ . '/test_uid_all_records.ged');

// Test INDI records
echo "=== Testing INDI Records ===\n";
$individuals = $gedcom->getIndi();
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

// Test FAM records
echo "=== Testing FAM Records ===\n";
$families = $gedcom->getFam();
foreach ($families as $fam) {
    echo "Family: " . $fam->getId() . "\n";
    
    $uids = $fam->getAllUid();
    if (!empty($uids)) {
        echo "  _UID values: " . implode(', ', $uids) . "\n";
    }
    
    $uids7 = $fam->getAllUid7();
    if (!empty($uids7)) {
        echo "  UID values: " . implode(', ', $uids7) . "\n";
    }
    echo "\n";
}

// Test SOUR records
echo "=== Testing SOUR Records ===\n";
$sources = $gedcom->getSour();
foreach ($sources as $sour) {
    echo "Source: " . $sour->getSour() . "\n";
    
    $uids = $sour->getAllUid();
    if (!empty($uids)) {
        echo "  _UID values: " . implode(', ', $uids) . "\n";
    }
    
    $uids7 = $sour->getAllUid7();
    if (!empty($uids7)) {
        echo "  UID values: " . implode(', ', $uids7) . "\n";
    }
    echo "\n";
}

// Test REPO records
echo "=== Testing REPO Records ===\n";
$repos = $gedcom->getRepo();
foreach ($repos as $repo) {
    echo "Repository: " . $repo->getRepo() . "\n";
    
    $uids = $repo->getAllUid();
    if (!empty($uids)) {
        echo "  _UID values: " . implode(', ', $uids) . "\n";
    }
    
    $uids7 = $repo->getAllUid7();
    if (!empty($uids7)) {
        echo "  UID values: " . implode(', ', $uids7) . "\n";
    }
    echo "\n";
}

// Test SUBM records
echo "=== Testing SUBM Records ===\n";
$subms = $gedcom->getSubm();
foreach ($subms as $subm) {
    echo "Submitter: " . $subm->getSubm() . "\n";
    
    $uids = $subm->getAllUid();
    if (!empty($uids)) {
        echo "  _UID values: " . implode(', ', $uids) . "\n";
    }
    
    $uids7 = $subm->getAllUid7();
    if (!empty($uids7)) {
        echo "  UID values: " . implode(', ', $uids7) . "\n";
    }
    echo "\n";
}

// Test OBJE records
echo "=== Testing OBJE Records ===\n";
$objes = $gedcom->getObje();
foreach ($objes as $obje) {
    echo "Object: " . $obje->getId() . "\n";
    
    $uids = $obje->getAllUid();
    if (!empty($uids)) {
        echo "  _UID values: " . implode(', ', $uids) . "\n";
    }
    
    $uids7 = $obje->getAllUid7();
    if (!empty($uids7)) {
        echo "  UID values: " . implode(', ', $uids7) . "\n";
    }
    echo "\n";
}

// Test writing back to GEDCOM
echo "=== Testing Write Back to GEDCOM ===\n";
$writer = new Writer();
$output = $writer->convert($gedcom);
echo "Written GEDCOM (first 60 lines):\n";
$lines = explode("\n", $output);
for ($i = 0; $i < min(60, count($lines)); $i++) {
    echo $lines[$i] . "\n";
}
echo "\n";

// Verify UIDs are in the output
echo "=== Verifying UIDs in output ===\n";
$uidCount = substr_count($output, '_UID');
$uid7Count = substr_count($output, '1 UID ');
echo "Found " . $uidCount . " _UID tags\n";
echo "Found " . $uid7Count . " UID tags\n";

if ($uidCount >= 6 && $uid7Count >= 6) {
    echo "✓ All UIDs successfully written\n";
} else {
    echo "✗ Some UIDs missing in output\n";
}

echo "\n=== Test Complete ===\n";
