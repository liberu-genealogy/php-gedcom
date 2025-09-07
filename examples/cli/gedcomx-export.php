<?php

/**
 * CLI script for exporting to Gedcom X format
 * 
 * Usage: php gedcomx-export.php <input-file> <output-file>
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Gedcom\GedcomResource;

function showUsage(): void
{
    echo "Usage: php gedcomx-export.php <input-file> <output-file>\n";
    echo "\n";
    echo "Arguments:\n";
    echo "  input-file   Path to the GEDCOM file to export\n";
    echo "  output-file  Path to save the Gedcom X file\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php gedcomx-export.php family.ged family.json\n";
    echo "  php gedcomx-export.php data.gedcom output.gedcomx\n";
    echo "\n";
}

function main(array $argv): int
{
    if (count($argv) < 3) {
        showUsage();
        return 1;
    }

    $inputFile = $argv[1];
    $outputFile = $argv[2];

    if (!file_exists($inputFile)) {
        echo "Error: Input file not found: $inputFile\n";
        return 1;
    }

    try {
        $resource = new GedcomResource();

        echo "Exporting GEDCOM file to Gedcom X format\n";
        echo "Input: $inputFile\n";
        echo "Output: $outputFile\n\n";

        // Validate the input file first
        echo "Validating input file...\n";
        $errors = $resource->validate($inputFile);

        if (!empty($errors)) {
            echo "Validation errors found:\n";
            foreach ($errors as $error) {
                echo "  - $error\n";
            }
            echo "Continuing with export despite errors...\n";
        } else {
            echo "File validation passed.\n";
        }

        // Import the GEDCOM file
        echo "Importing GEDCOM file...\n";
        $gedcom = $resource->importGedcom($inputFile);

        if (!$gedcom) {
            echo "Error: Failed to import GEDCOM file\n";
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

        // Export to Gedcom X
        echo "\nExporting to Gedcom X format...\n";
        $success = $resource->exportGedcomX($gedcom, $outputFile);

        if ($success) {
            echo "Export successful!\n";
            echo "Gedcom X file saved to: $outputFile\n";

            // Show file size
            $fileSize = filesize($outputFile);
            echo "File size: " . number_format($fileSize) . " bytes\n";
        } else {
            echo "Error: Failed to export Gedcom X file\n";
            return 1;
        }

        return 0;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return 1;
    }
}

exit(main($argv));