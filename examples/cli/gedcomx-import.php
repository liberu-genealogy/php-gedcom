<?php

/**
 * CLI script for importing Gedcom X files
 * 
 * Usage: php gedcomx-import.php <input-file> [output-file]
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Gedcom\GedcomResource;

function showUsage(): void
{
    echo "Usage: php gedcomx-import.php <input-file> [output-file]\n";
    echo "\n";
    echo "Arguments:\n";
    echo "  input-file   Path to the Gedcom X file to import\n";
    echo "  output-file  Optional: Path to save the converted GEDCOM file\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php gedcomx-import.php family.json\n";
    echo "  php gedcomx-import.php family.json family.ged\n";
    echo "\n";
}

function main(array $argv): int
{
    if (count($argv) < 2) {
        showUsage();
        return 1;
    }

    $inputFile = $argv[1];
    $outputFile = $argv[2] ?? null;

    if (!file_exists($inputFile)) {
        echo "Error: Input file not found: $inputFile\n";
        return 1;
    }

    try {
        $resource = new GedcomResource();

        echo "Importing Gedcom X file: $inputFile\n";

        // Validate the file first
        echo "Validating file...\n";
        $errors = $resource->validate($inputFile);

        if (!empty($errors)) {
            echo "Validation errors found:\n";
            foreach ($errors as $error) {
                echo "  - $error\n";
            }
            echo "Continuing with import despite errors...\n";
        } else {
            echo "File validation passed.\n";
        }

        // Import the file
        $gedcom = $resource->importGedcomX($inputFile);

        if (!$gedcom) {
            echo "Error: Failed to import Gedcom X file\n";
            return 1;
        }

        // Show statistics
        $stats = $resource->getStatistics($gedcom);
        echo "Import successful!\n";
        echo "Statistics:\n";
        echo "  - Individuals: {$stats['individuals']}\n";
        echo "  - Families: {$stats['families']}\n";
        echo "  - Sources: {$stats['sources']}\n";
        echo "  - Repositories: {$stats['repositories']}\n";
        echo "  - Notes: {$stats['notes']}\n";
        echo "  - Media Objects: {$stats['media_objects']}\n";
        echo "  - Submitters: {$stats['submitters']}\n";

        // Export to GEDCOM if output file specified
        if ($outputFile) {
            echo "\nExporting to GEDCOM format: $outputFile\n";

            $success = $resource->exportGedcom($gedcom, $outputFile);

            if ($success) {
                echo "Export successful!\n";
            } else {
                echo "Error: Failed to export GEDCOM file\n";
                return 1;
            }
        }

        return 0;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return 1;
    }
}

exit(main($argv));