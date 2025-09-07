<?php

/**
 * Performance Benchmark CLI Tool
 * 
 * Usage: php performance-benchmark.php <test-file> [options]
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Gedcom\Performance\Benchmark;

function showUsage(): void
{
    echo "Performance Benchmark Tool for php-gedcom\n";
    echo "=========================================\n\n";
    echo "Usage: php performance-benchmark.php <test-file> [options]\n";
    echo "\n";
    echo "Arguments:\n";
    echo "  test-file    Path to GEDCOM or Gedcom X file for testing\n";
    echo "\n";
    echo "Options:\n";
    echo "  --streaming  Also test streaming performance (requires large file)\n";
    echo "  --report     Generate detailed report file\n";
    echo "  --baseline   Save results as baseline for future comparisons\n";
    echo "  --compare    Compare with saved baseline\n";
    echo "  --help       Show this help message\n";
    echo "\n";
    echo "Examples:\n";
    echo "  php performance-benchmark.php sample.ged\n";
    echo "  php performance-benchmark.php large.ged --streaming --report\n";
    echo "  php performance-benchmark.php test.json --baseline\n";
    echo "  php performance-benchmark.php test.ged --compare\n";
    echo "\n";
}

function formatResults(array $results): void
{
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "PERFORMANCE BENCHMARK RESULTS\n";
    echo str_repeat("=", 60) . "\n";

    foreach ($results as $category => $data) {
        echo "\n" . strtoupper($category) . " PERFORMANCE:\n";
        echo str_repeat("-", 30) . "\n";

        switch ($category) {
            case 'parsing':
                echo sprintf("Parse Time:     %.2f ms\n", $data['cold_parse']['time_ms']);
                echo sprintf("Memory Peak:    %s\n", formatBytes($data['cold_parse']['memory_peak']));
                echo sprintf("Records:        %d\n", $data['records_parsed']);
                echo sprintf("Throughput:     %.0f records/sec\n", 
                    $data['records_parsed'] / ($data['cold_parse']['time_ms'] / 1000));
                break;

            case 'caching':
                echo sprintf("Cache Miss:     %.2f ms\n", $data['cache_miss']['time_ms']);
                echo sprintf("Cache Hit:      %.2f ms\n", $data['cache_hit']['time_ms']);
                echo sprintf("Speedup:        %.1fx\n", $data['speedup_factor']);
                echo sprintf("Efficiency:     %.1f%%\n", 
                    (1 - $data['cache_hit']['time_ms'] / $data['cache_miss']['time_ms']) * 100);
                break;

            case 'conversion':
                echo sprintf("To Gedcom X:    %.2f ms\n", $data['to_gedcomx']['time_ms']);
                echo sprintf("To GEDCOM:      %.2f ms\n", $data['to_gedcom']['time_ms']);
                echo sprintf("Size Ratio:     %.2f\n", 
                    $data['gedcomx_file_size'] / $data['gedcom_file_size']);
                break;

            case 'memory':
                echo sprintf("Base Memory:    %s\n", formatBytes($data['no_cache_memory']));
                echo sprintf("With Cache:     %s\n", formatBytes($data['with_cache_memory']));
                echo sprintf("Overhead:       %s (%.1f%%)\n", 
                    formatBytes($data['cache_overhead']),
                    ($data['cache_overhead'] / $data['no_cache_memory']) * 100);
                break;

            case 'streaming':
                echo sprintf("File Size:      %s\n", formatBytes($data['file_size']));
                echo sprintf("Parse Time:     %.2f ms\n", $data['streaming_parse']['time_ms']);
                echo sprintf("Memory Peak:    %s\n", formatBytes($data['streaming_parse']['memory_peak']));
                echo sprintf("Records:        %d\n", $data['records_parsed']);
                break;
        }
    }
}

function formatBytes(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, 2) . ' ' . $units[$pow];
}

function saveBaseline(array $results, string $testFile): void
{
    $baselineFile = __DIR__ . '/../../.benchmark_baseline.json';
    $baseline = [
        'timestamp' => time(),
        'test_file' => basename($testFile),
        'php_version' => PHP_VERSION,
        'results' => $results
    ];

    file_put_contents($baselineFile, json_encode($baseline, JSON_PRETTY_PRINT));
    echo "\nBaseline saved to: $baselineFile\n";
}

function loadBaseline(): ?array
{
    $baselineFile = __DIR__ . '/../../.benchmark_baseline.json';

    if (!file_exists($baselineFile)) {
        return null;
    }

    $content = file_get_contents($baselineFile);
    return $content ? json_decode($content, true) : null;
}

function compareWithBaseline(Benchmark $benchmark, array $baseline): void
{
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "COMPARISON WITH BASELINE\n";
    echo str_repeat("=", 60) . "\n";

    echo sprintf("Baseline from: %s (PHP %s)\n", 
        date('Y-m-d H:i:s', $baseline['timestamp']),
        $baseline['php_version']
    );

    $comparison = $benchmark->compareWithBaseline($baseline['results']);

    foreach ($comparison as $category => $improvements) {
        echo "\n" . strtoupper($category) . ":\n";

        if (isset($improvements['time_improvement'])) {
            $timeImprovement = $improvements['time_improvement'];
            $timeStatus = $timeImprovement > 0 ? '✓ FASTER' : '✗ SLOWER';
            echo sprintf("  Time: %+.1f%% %s\n", $timeImprovement, $timeStatus);
        }

        if (isset($improvements['memory_improvement'])) {
            $memoryImprovement = $improvements['memory_improvement'];
            $memoryStatus = $memoryImprovement > 0 ? '✓ LESS MEMORY' : '✗ MORE MEMORY';
            echo sprintf("  Memory: %+.1f%% %s\n", $memoryImprovement, $memoryStatus);
        }
    }
}

function main(array $argv): int
{
    $options = [
        'streaming' => false,
        'report' => false,
        'baseline' => false,
        'compare' => false,
        'help' => false
    ];

    $testFile = null;

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
            $testFile = $arg;
        }
    }

    if ($options['help'] || !$testFile) {
        showUsage();
        return $options['help'] ? 0 : 1;
    }

    if (!file_exists($testFile)) {
        echo "Error: Test file not found: $testFile\n";
        return 1;
    }

    try {
        echo "PHP GEDCOM Performance Benchmark\n";
        echo "PHP Version: " . PHP_VERSION . "\n";
        echo "Test File: $testFile (" . formatBytes(filesize($testFile)) . ")\n";
        echo "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";

        $benchmark = new Benchmark();

        // Run main benchmark suite
        $results = $benchmark->runBenchmarkSuite($testFile);

        // Run streaming benchmark if requested
        if ($options['streaming']) {
            $benchmark->benchmarkStreaming($testFile);
        }

        // Display results
        formatResults($results);

        // Save baseline if requested
        if ($options['baseline']) {
            saveBaseline($results, $testFile);
        }

        // Compare with baseline if requested
        if ($options['compare']) {
            $baseline = loadBaseline();
            if ($baseline) {
                compareWithBaseline($benchmark, $baseline);
            } else {
                echo "\nNo baseline found. Run with --baseline first.\n";
            }
        }

        // Generate report if requested
        if ($options['report']) {
            $reportFile = 'benchmark_report_' . date('Y-m-d_H-i-s') . '.txt';
            $report = $benchmark->generateReport();
            file_put_contents($reportFile, $report);
            echo "\nDetailed report saved to: $reportFile\n";
        }

        echo "\nBenchmark completed successfully!\n";
        return 0;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return 1;
    }
}

exit(main($argv));