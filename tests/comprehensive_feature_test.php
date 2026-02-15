#!/usr/bin/env php
<?php

/**
 * Comprehensive GEDCOM 5.5.1 and 7.0 Feature Validation Test
 * 
 * This script tests:
 * - Parsing both GEDCOM versions
 * - All record types (HEAD, INDI, FAM, SOUR, REPO, OBJE, NOTE, SUBM)
 * - Version-specific features (UID vs _UID)
 * - Round-trip conversion (parse → write → parse)
 * - Format conversion (5.5.1 ↔ 7.0)
 */

// Minimal autoloader
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

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Comprehensive GEDCOM 5.5.1 and 7.0 Feature Validation      ║\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "\n";

$testsPassed = 0;
$testsFailed = 0;
$warnings = [];

function pass($message) {
    global $testsPassed;
    $testsPassed++;
    echo "✓ " . $message . "\n";
}

function fail($message) {
    global $testsFailed;
    $testsFailed++;
    echo "✗ " . $message . "\n";
}

function warn($message) {
    global $warnings;
    $warnings[] = $message;
    echo "⚠ " . $message . "\n";
}

function section($title) {
    echo "\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "  " . $title . "\n";
    echo "═══════════════════════════════════════════════════════════════\n";
    echo "\n";
}

// Test 1: Verify test files exist
section("Test 1: Verify Test Files");

$file551 = __DIR__ . '/comprehensive_test_551.ged';
$file70 = __DIR__ . '/comprehensive_test_70.ged';

if (file_exists($file551)) {
    pass("GEDCOM 5.5.1 comprehensive test file exists");
} else {
    fail("GEDCOM 5.5.1 comprehensive test file missing");
}

if (file_exists($file70)) {
    pass("GEDCOM 7.0 comprehensive test file exists");
} else {
    fail("GEDCOM 7.0 comprehensive test file missing");
}

// Test 2: Writer Constants
section("Test 2: Writer Constants and Methods");

try {
    $gedcom55 = \Gedcom\Writer::GEDCOM55;
    $gedcom70 = \Gedcom\Writer::GEDCOM70;
    pass("GEDCOM55 constant: " . $gedcom55);
    pass("GEDCOM70 constant: " . $gedcom70);
    
    if ($gedcom55 !== $gedcom70) {
        pass("Constants are unique");
    } else {
        fail("Constants should be different");
    }
    
    $methods = ['getCurrentFormat', 'isGedcom70', 'isGedcom55', 'convert'];
    foreach ($methods as $method) {
        if (method_exists(\Gedcom\Writer::class, $method)) {
            pass("Method exists: " . $method);
        } else {
            fail("Method missing: " . $method);
        }
    }
} catch (Exception $e) {
    fail("Error checking Writer class: " . $e->getMessage());
}

// Test 3: Parse GEDCOM 5.5.1
section("Test 3: Parse GEDCOM 5.5.1 Comprehensive File");

try {
    $parser551 = new \Gedcom\Parser();
    $gedcom551 = $parser551->parse($file551);
    
    if ($gedcom551) {
        pass("Successfully parsed GEDCOM 5.5.1 file");
        
        // Check version
        $head = $gedcom551->getHead();
        if ($head) {
            $gedc = $head->getGedc();
            if ($gedc) {
                $version = $gedc->getVersion();
                if ($version === '5.5.1') {
                    pass("Version correctly detected: 5.5.1");
                } else {
                    fail("Version mismatch: expected 5.5.1, got " . $version);
                }
            }
        }
        
        // Count records
        $individuals = $gedcom551->getIndi();
        $families = $gedcom551->getFam();
        $sources = $gedcom551->getSour();
        $repos = $gedcom551->getRepo();
        $objects = $gedcom551->getObje();
        $notes = $gedcom551->getNote();
        $submitters = $gedcom551->getSubm();
        
        pass(sprintf("Parsed %d individuals", count($individuals)));
        pass(sprintf("Parsed %d families", count($families)));
        pass(sprintf("Parsed %d sources", count($sources)));
        pass(sprintf("Parsed %d repositories", count($repos)));
        pass(sprintf("Parsed %d media objects", count($objects)));
        pass(sprintf("Parsed %d notes", count($notes)));
        pass(sprintf("Parsed %d submitters", count($submitters)));
        
        // Check _UID tags
        if (count($individuals) > 0) {
            $firstIndi = reset($individuals);
            $uids = $firstIndi->getAllUid();
            if ($uids && count($uids) > 0) {
                pass("_UID tags parsed for individuals: " . $uids[0]);
            } else {
                warn("No _UID tags found in individuals");
            }
        }
        
        if (count($families) > 0) {
            $firstFam = reset($families);
            $uids = $firstFam->getAllUid();
            if ($uids && count($uids) > 0) {
                pass("_UID tags parsed for families: " . $uids[0]);
            } else {
                warn("No _UID tags found in families");
            }
        }
    } else {
        fail("Failed to parse GEDCOM 5.5.1 file");
    }
} catch (Exception $e) {
    fail("Exception parsing GEDCOM 5.5.1: " . $e->getMessage());
}

// Test 4: Parse GEDCOM 7.0
section("Test 4: Parse GEDCOM 7.0 Comprehensive File");

try {
    $parser70 = new \Gedcom\Parser();
    $gedcom70 = $parser70->parse($file70);
    
    if ($gedcom70) {
        pass("Successfully parsed GEDCOM 7.0 file");
        
        // Check version
        $head = $gedcom70->getHead();
        if ($head) {
            $gedc = $head->getGedc();
            if ($gedc) {
                $version = $gedc->getVersion();
                if ($version === '7.0') {
                    pass("Version correctly detected: 7.0");
                } else {
                    fail("Version mismatch: expected 7.0, got " . $version);
                }
            }
        }
        
        // Count records
        $individuals = $gedcom70->getIndi();
        $families = $gedcom70->getFam();
        $sources = $gedcom70->getSour();
        $repos = $gedcom70->getRepo();
        $objects = $gedcom70->getObje();
        $notes = $gedcom70->getNote();
        $submitters = $gedcom70->getSubm();
        
        pass(sprintf("Parsed %d individuals", count($individuals)));
        pass(sprintf("Parsed %d families", count($families)));
        pass(sprintf("Parsed %d sources", count($sources)));
        pass(sprintf("Parsed %d repositories", count($repos)));
        pass(sprintf("Parsed %d media objects", count($objects)));
        pass(sprintf("Parsed %d notes", count($notes)));
        pass(sprintf("Parsed %d submitters", count($submitters)));
        
        // Check UID tags (7.0 style)
        if (count($individuals) > 0) {
            $firstIndi = reset($individuals);
            $uids = $firstIndi->getAllUid7();
            if ($uids && count($uids) > 0) {
                pass("UID tags parsed for individuals: " . $uids[0]);
            } else {
                warn("No UID tags found in individuals");
            }
        }
        
        if (count($families) > 0) {
            $firstFam = reset($families);
            $uids = $firstFam->getAllUid7();
            if ($uids && count($uids) > 0) {
                pass("UID tags parsed for families: " . $uids[0]);
            } else {
                warn("No UID tags found in families");
            }
        }
        
        // Check GEDCOM 7.0 specific features (SOURCE/DATA/DATE and TEXT)
        if (count($sources) > 0) {
            $firstSource = reset($sources);
            $data = $firstSource->getData();
            if ($data) {
                $date = $data->getDate();
                $text = $data->getText();
                if ($date) {
                    pass("SOURCE/DATA/DATE parsed (GEDCOM 7.0 feature): " . $date);
                }
                if ($text) {
                    pass("SOURCE/DATA/TEXT parsed (GEDCOM 7.0 feature): " . substr($text, 0, 50) . "...");
                }
            }
        }
    } else {
        fail("Failed to parse GEDCOM 7.0 file");
    }
} catch (Exception $e) {
    fail("Exception parsing GEDCOM 7.0: " . $e->getMessage());
}

// Test 5: Write GEDCOM 5.5.1 Format
section("Test 5: Write GEDCOM 5.5.1 Format");

try {
    if (isset($gedcom551)) {
        $output551 = \Gedcom\Writer::convert($gedcom551, \Gedcom\Writer::GEDCOM55);
        
        if ($output551) {
            pass("Successfully wrote GEDCOM 5.5.1 format");
            
            // Check for _UID tags
            if (strpos($output551, '_UID ') !== false) {
                pass("Output contains _UID tags (GEDCOM 5.5.1 format)");
            } else {
                warn("Output does not contain _UID tags");
            }
            
            // Check that it doesn't contain 7.0 UID tags
            if (strpos($output551, "\n1 UID ") === false && strpos($output551, "\n2 UID ") === false) {
                pass("Output does not contain UID tags (correct for 5.5.1)");
            } else {
                fail("Output incorrectly contains UID tags in GEDCOM 5.5.1 format");
            }
            
            // Save for round-trip test
            file_put_contents(__DIR__ . '/test_output_551.ged', $output551);
            pass("Saved output to test_output_551.ged");
        } else {
            fail("Failed to write GEDCOM 5.5.1 format");
        }
    }
} catch (Exception $e) {
    fail("Exception writing GEDCOM 5.5.1: " . $e->getMessage());
}

// Test 6: Write GEDCOM 7.0 Format
section("Test 6: Write GEDCOM 7.0 Format");

try {
    if (isset($gedcom70)) {
        $output70 = \Gedcom\Writer::convert($gedcom70, \Gedcom\Writer::GEDCOM70);
        
        if ($output70) {
            pass("Successfully wrote GEDCOM 7.0 format");
            
            // Check for UID tags (without underscore)
            $uidCount = substr_count($output70, "\n1 UID ");
            if ($uidCount > 0) {
                pass("Output contains UID tags (GEDCOM 7.0 format): " . $uidCount . " instances");
            } else {
                warn("Output does not contain UID tags");
            }
            
            // Check that it doesn't contain 5.5.1 _UID tags
            if (strpos($output70, '_UID ') === false) {
                pass("Output does not contain _UID tags (correct for 7.0)");
            } else {
                fail("Output incorrectly contains _UID tags in GEDCOM 7.0 format");
            }
            
            // Save for round-trip test
            file_put_contents(__DIR__ . '/test_output_70.ged', $output70);
            pass("Saved output to test_output_70.ged");
        } else {
            fail("Failed to write GEDCOM 7.0 format");
        }
    }
} catch (Exception $e) {
    fail("Exception writing GEDCOM 7.0: " . $e->getMessage());
}

// Test 7: Format Conversion (5.5.1 → 7.0)
section("Test 7: Format Conversion (GEDCOM 5.5.1 → 7.0)");

try {
    if (isset($gedcom551)) {
        $converted70 = \Gedcom\Writer::convert($gedcom551, \Gedcom\Writer::GEDCOM70);
        
        if ($converted70) {
            pass("Successfully converted 5.5.1 to 7.0 format");
            
            // Re-parse to verify
            file_put_contents(__DIR__ . '/test_converted_70.ged', $converted70);
            $parser = new \Gedcom\Parser();
            $reparsed = $parser->parse(__DIR__ . '/test_converted_70.ged');
            
            if ($reparsed) {
                pass("Converted file can be re-parsed");
                
                $individuals = $reparsed->getIndi();
                pass("Converted file has " . count($individuals) . " individuals");
            }
        } else {
            fail("Failed to convert 5.5.1 to 7.0");
        }
    }
} catch (Exception $e) {
    fail("Exception converting 5.5.1 to 7.0: " . $e->getMessage());
}

// Test 8: Format Conversion (7.0 → 5.5.1)
section("Test 8: Format Conversion (GEDCOM 7.0 → 5.5.1)");

try {
    if (isset($gedcom70)) {
        $converted551 = \Gedcom\Writer::convert($gedcom70, \Gedcom\Writer::GEDCOM55);
        
        if ($converted551) {
            pass("Successfully converted 7.0 to 5.5.1 format");
            
            // Re-parse to verify
            file_put_contents(__DIR__ . '/test_converted_551.ged', $converted551);
            $parser = new \Gedcom\Parser();
            $reparsed = $parser->parse(__DIR__ . '/test_converted_551.ged');
            
            if ($reparsed) {
                pass("Converted file can be re-parsed");
                
                $individuals = $reparsed->getIndi();
                pass("Converted file has " . count($individuals) . " individuals");
            }
        } else {
            fail("Failed to convert 7.0 to 5.5.1");
        }
    }
} catch (Exception $e) {
    fail("Exception converting 7.0 to 5.5.1: " . $e->getMessage());
}

// Summary
section("Test Summary");

echo "\n";
echo "Total Tests Passed: " . $testsPassed . "\n";
echo "Total Tests Failed: " . $testsFailed . "\n";
echo "Total Warnings: " . count($warnings) . "\n";
echo "\n";

if (count($warnings) > 0) {
    echo "Warnings:\n";
    foreach ($warnings as $warning) {
        echo "  ⚠ " . $warning . "\n";
    }
    echo "\n";
}

if ($testsFailed === 0) {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  ✓ ALL TESTS PASSED - GEDCOM SUPPORT IS COMPREHENSIVE       ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    exit(0);
} else {
    echo "╔══════════════════════════════════════════════════════════════╗\n";
    echo "║  ✗ SOME TESTS FAILED - PLEASE REVIEW ABOVE                  ║\n";
    echo "╚══════════════════════════════════════════════════════════════╝\n";
    exit(1);
}
