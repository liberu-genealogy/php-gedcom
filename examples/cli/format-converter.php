<?php

/**
 * Universal format converter for genealogical data
 * 
 * Usage: php format-converter.php <input-file> <output-file> [--validate]
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Gedcom\GedcomResource;

function showUsage(): void
{
    echo "Universal Genealogical Data Format Converter\n";
    echo "============================================\n\n";
    echo "Usage: php format-converter.php <input-file> <output-file> [options]\n";
    echo "\n";
    echo "Arguments:\n";
    echo "  input-file   Path to the input file (GEDCOM or Gedcom X)\n";
    echo "  output-file  Path for the output file\n";
    echo "\n";
    echo "Options:\n";
    echo "  --validate   Validate the input file before conversion\n";
    echo "  --stats      Show detailed statistics after conversion\n";
    echo "  --help       Show this help message\n";
    echo "\n";
    echo "Supported Formats:\n";
    echo "  Input:  .ged, .gedcom (GEDCOM 5.5), .json, .gedcomx (Gedcom X)\n";
    echo "  Output: .ged, .gedcom (GEDCOM 5.5), .json, .gedcomx (Gedcom X)\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php format-converter.php family.ged family.json\n";
    echo "  php format-converter.php data.json output.gedcom --validate\n";
    echo "  php format-converter.php input.gedcomx output.ged --stats\n";
    echo "\n";
}

function detectOutputFormat(string $fileName): string
{
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($extension, ['json', 'gedcomx'])) {
        return GedcomResource::FORMAT_GEDCOMX;
    }

    return GedcomResource::FORMAT_GEDCOM;
}

function formatFileSize(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, 2) . ' ' . $units[$pow];
}

function showStatistics(array $stats): void
{
    echo "\nFile Statistics:\n";
    echo "================\n";
    printf("%-15s: %s\n", "Individuals", number_format($stats['individuals']));
    printf("%-15s: %s\n", "Families", number_format($stats['families']));
    printf("%-15s: %s\n", "Sources", number_format($stats['sources']));
    printf("%-15s: %s\n", "Repositories", number_format($stats['repositories']));
    printf("%-15s: %s\n", "Notes", number_format($stats['notes']));
    printf("%-15s: %s\n", "Media Objects", number_format($stats['media_objects']));
    printf("%-15s: %s\n", "Submitters", number_format($stats['submitters']));
}

function main(array $argv): int
{
    $options = [
        'validate' => false,
        'stats' => false,
        'help' => false
    ];

    $files = [];

    // Parse arguments
    for ($i = 1; $i < count($argv); $i++) {
        $arg = $argv[$i];

        if (str_starts_with($arg, '--')) {
            $option = substr($arg, 2);
            if (array_key_exists($option, $options)) {
                $options[$option] = true;
            } else {
                echo "Unknown option: $arg\n";
                return 1;
            }
        } else {
            $files[] = $arg;
        }
    }

    if ($options['help'] || count($files) < 2) {
        showUsage();
        return $options['help'] ? 0 : 1;
    }

    $inputFile = $files[0];
    $outputFile = $files[1];

    if (!file_exists($inputFile)) {
        echo "Error: Input file not found: $inputFile\n";
        return 1;
    }

    try {
        $resource = new GedcomResource();

        echo "Genealogical Data Format Converter\n";
        echo "==================================\n\n";

        // Detect formats
        $inputFormat = $resource->detectFileFormat($inputFile);
        $outputFormat = detectOutputFormat($outputFile);

        $formatInfo = $resource->getSupportedFormats();

        echo "Input File:   $inputFile\n";
        echo "Input Format: {$formatInfo[$inputFormat]['name']}\n";
        echo "Output File:  $outputFile\n";
        echo "Output Format: {$formatInfo[$outputFormat]['name']}\n\n";

        // Validate if requested
        if ($options['validate']) {
            echo "Validating input file...\n";
            $errors = $resource->validate($inputFile);

            if (!empty($errors)) {
                echo "Validation errors found:\n";
                foreach ($errors as $error) {
                    echo "  ⚠ $error\n";
                }
                echo "\nContinuing with conversion despite errors...\n\n";
            } else {
                echo "✓ File validation passed\n\n";
            }
        }

        // Show input file info
        $inputSize = filesize($inputFile);
        echo "Input file size: " . formatFileSize($inputSize) . "\n";

        // Perform conversion
        echo "Converting...\n";
        $startTime = microtime(true);

        $success = $resource->convert($inputFile, $outputFile, $outputFormat);

        $endTime = microtime(true);
        $conversionTime = round(($endTime - $startTime) * 1000, 2);

        if ($success) {
            echo "✓ Conversion completed successfully!\n";

            $outputSize = filesize($outputFile);
            echo "Output file size: " . formatFileSize($outputSize) . "\n";
            echo "Conversion time: {$conversionTime}ms\n";

            // Show statistics if requested
            if ($options['stats']) {
                $gedcom = $resource->import($inputFile);
                if ($gedcom) {
                    $stats = $resource->getStatistics($gedcom);
                    showStatistics($stats);
                }
            }

            echo "\nConversion Summary:\n";
            echo "==================\n";
            echo "✓ Successfully converted from {$formatInfo[$inputFormat]['name']} to {$formatInfo[$outputFormat]['name']}\n";
            echo "✓ Output saved to: $outputFile\n";

        } else {
            echo "✗ Conversion failed\n";
            return 1;
        }

        return 0;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return 1;
    }
}

exit(main($argv));