<?php

/**
 * Unit test for UID support in all record types
 */

require_once __DIR__ . '/../vendor/autoload.php';

echo "Testing UID support for all record types (without Parser dependency)...\n\n";

// Test INDI
echo "=== Testing Indi Record ===\n";
$indi = new \Gedcom\Record\Indi();
$indi->setId('@I1@');
$indi->addUid('550e8400-e29b-41d4-a716-446655440000');
$indi->addUid('another-uid');
$indi->addUid7('2c1b2a8a-6e7b-4ef6-9d8a-2a3db6c9b8a1');

$uids = $indi->getAllUid();
$uids7 = $indi->getAllUid7();
echo "  _UID count: " . count($uids) . "\n";
echo "  _UID values: " . implode(', ', $uids) . "\n";
echo "  UID count: " . count($uids7) . "\n";
echo "  UID values: " . implode(', ', $uids7) . "\n";
assert(count($uids) === 2, "Indi should have 2 _UID values");
assert(count($uids7) === 1, "Indi should have 1 UID value");
echo "  ✓ Indi tests passed\n\n";

// Test FAM
echo "=== Testing Fam Record ===\n";
$fam = new \Gedcom\Record\Fam();
$fam->setId('@F1@');
$fam->addUid('fam-uid-1');
$fam->addUid7('fam-uid7-1');

$uids = $fam->getAllUid();
$uids7 = $fam->getAllUid7();
echo "  _UID count: " . count($uids) . "\n";
echo "  _UID values: " . implode(', ', $uids) . "\n";
echo "  UID count: " . count($uids7) . "\n";
echo "  UID values: " . implode(', ', $uids7) . "\n";
assert(count($uids) === 1, "Fam should have 1 _UID value");
assert(count($uids7) === 1, "Fam should have 1 UID value");
echo "  ✓ Fam tests passed\n\n";

// Test OBJE
echo "=== Testing Obje Record ===\n";
$obje = new \Gedcom\Record\Obje();
$obje->setId('@O1@');
$obje->addUid('obje-uid-1');
$obje->addUid7('obje-uid7-1');

$uids = $obje->getAllUid();
$uids7 = $obje->getAllUid7();
echo "  _UID count: " . count($uids) . "\n";
echo "  _UID values: " . implode(', ', $uids) . "\n";
echo "  UID count: " . count($uids7) . "\n";
echo "  UID values: " . implode(', ', $uids7) . "\n";
assert(count($uids) === 1, "Obje should have 1 _UID value");
assert(count($uids7) === 1, "Obje should have 1 UID value");
echo "  ✓ Obje tests passed\n\n";

// Test SOUR
echo "=== Testing Sour Record ===\n";
$sour = new \Gedcom\Record\Sour();
$sour->setSour('@S1@');
$sour->addUid('sour-uid-1');
$sour->addUid7('sour-uid7-1');

$uids = $sour->getAllUid();
$uids7 = $sour->getAllUid7();
echo "  _UID count: " . count($uids) . "\n";
echo "  _UID values: " . implode(', ', $uids) . "\n";
echo "  UID count: " . count($uids7) . "\n";
echo "  UID values: " . implode(', ', $uids7) . "\n";
assert(count($uids) === 1, "Sour should have 1 _UID value");
assert(count($uids7) === 1, "Sour should have 1 UID value");
echo "  ✓ Sour tests passed\n\n";

// Test REPO
echo "=== Testing Repo Record ===\n";
$repo = new \Gedcom\Record\Repo();
$repo->setRepo('@R1@');
$repo->addUid('repo-uid-1');
$repo->addUid7('repo-uid7-1');

$uids = $repo->getAllUid();
$uids7 = $repo->getAllUid7();
echo "  _UID count: " . count($uids) . "\n";
echo "  _UID values: " . implode(', ', $uids) . "\n";
echo "  UID count: " . count($uids7) . "\n";
echo "  UID values: " . implode(', ', $uids7) . "\n";
assert(count($uids) === 1, "Repo should have 1 _UID value");
assert(count($uids7) === 1, "Repo should have 1 UID value");
echo "  ✓ Repo tests passed\n\n";

// Test SUBM
echo "=== Testing Subm Record ===\n";
$subm = new \Gedcom\Record\Subm();
$subm->setSubm('@U1@');
$subm->addUid('subm-uid-1');
$subm->addUid7('subm-uid7-1');

$uids = $subm->getAllUid();
$uids7 = $subm->getAllUid7();
echo "  _UID count: " . count($uids) . "\n";
echo "  _UID values: " . implode(', ', $uids) . "\n";
echo "  UID count: " . count($uids7) . "\n";
echo "  UID values: " . implode(', ', $uids7) . "\n";
assert(count($uids) === 1, "Subm should have 1 _UID value");
assert(count($uids7) === 1, "Subm should have 1 UID value");
echo "  ✓ Subm tests passed\n\n";

// Test Writer for Indi (since Writer classes don't depend on Parser)
echo "=== Testing Indi Writer ===\n";
$indi = new \Gedcom\Record\Indi();
$indi->setId('@I1@');
$indi->setGid('I1');
$indi->addUid('test-uid-1');
$indi->addUid('test-uid-2');
$indi->addUid7('test-uid7-1');

$name = new \Gedcom\Record\Indi\Name();
$name->setName('John /Doe/');
$indi->addName($name);

$output = \Gedcom\Writer\Indi::convert($indi);
echo substr($output, 0, 500) . "\n";

$uidCount = substr_count($output, '_UID');
$uid7Count = substr_count($output, '1 UID ');
echo "  _UID tags in output: " . $uidCount . "\n";
echo "  UID tags in output: " . $uid7Count . "\n";
assert($uidCount === 2, "Should have 2 _UID tags in output");
assert($uid7Count === 1, "Should have 1 UID tag in output");
echo "  ✓ Indi Writer tests passed\n\n";

// Test Writer for Fam
echo "=== Testing Fam Writer ===\n";
$fam = new \Gedcom\Record\Fam();
$fam->setId('F1');
$fam->addUid('fam-test-uid-1');
$fam->addUid7('fam-test-uid7-1');
$fam->setHusb('I1');
$fam->setWife('I2');

$output = \Gedcom\Writer\Fam::convert($fam);
echo substr($output, 0, 500) . "\n";

$uidCount = substr_count($output, '_UID');
$uid7Count = substr_count($output, '1 UID ');
echo "  _UID tags in output: " . $uidCount . "\n";
echo "  UID tags in output: " . $uid7Count . "\n";
assert($uidCount === 1, "Should have 1 _UID tag in output");
assert($uid7Count === 1, "Should have 1 UID tag in output");
echo "  ✓ Fam Writer tests passed\n\n";

echo "=== All Tests Passed! ===\n";
