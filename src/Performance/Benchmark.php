<?php

declare(strict_types=1);

namespace Gedcom\Performance;

use Gedcom\GedcomResource;
use Gedcom\Gedcom;

/**
 * Performance Benchmark Utility (PHP 8.4 Optimized)
 * 
 * Provides comprehensive benchmarking capabilities for GEDCOM operations
 * including parsing, generation, conversion, and caching performance.
 */
class Benchmark
{
    private array $results = [];
    private float $startTime;
    private int $startMemory;

    public function __construct()
    {
        // Ensure optimal PHP settings for benchmarking
        ini_set('memory_limit', '2G');
        gc_disable(); // Disable garbage collection during benchmarks
    }

    public function __destruct()
    {
        gc_enable(); // Re-enable garbage collection
    }

    /**
     * Run comprehensive benchmark suite
     */
    public function runBenchmarkSuite(string $testFile): array
    {
        echo "Starting Performance Benchmark Suite\n";
        echo "====================================\n\n";

        $this->results = [];

        // Test file parsing performance
        $this->benchmarkParsing($testFile);

        // Test caching performance
        $this->benchmarkCaching($testFile);

        // Test conversion performance
        $this->benchmarkConversion($testFile);

        // Test memory usage
        $this->benchmarkMemoryUsage($testFile);

        return $this->results;
    }

    /**
     * Benchmark parsing performance
     */
    public function benchmarkParsing(string $testFile): void
    {
        echo "Benchmarking Parsing Performance...\n";

        $resource = new GedcomResource(cacheEnabled: false);

        // Cold parse (no cache)
        $this->startBenchmark();
        $gedcom = $resource->import($testFile);
        $coldParseResult = $this->endBenchmark();

        $this->results['parsing'] = [
            'cold_parse' => $coldParseResult,
            'file_size' => filesize($testFile),
            'records_parsed' => $this->countRecords($gedcom)
        ];

        echo sprintf(
            "  Cold parse: %.2fms, Memory: %s, Records: %d\n",
            $coldParseResult['time_ms'],
            $this->formatBytes($coldParseResult['memory_peak']),
            $this->results['parsing']['records_parsed']
        );
    }

    /**
     * Benchmark caching performance
     */
    public function benchmarkCaching(string $testFile): void
    {
        echo "\nBenchmarking Caching Performance...\n";

        $resource = new GedcomResource(cacheEnabled: true);

        // First parse (cache miss)
        $this->startBenchmark();
        $gedcom1 = $resource->import($testFile);
        $cacheMissResult = $this->endBenchmark();

        // Second parse (cache hit)
        $this->startBenchmark();
        $gedcom2 = $resource->import($testFile);
        $cacheHitResult = $this->endBenchmark();

        $speedup = $cacheMissResult['time_ms'] / $cacheHitResult['time_ms'];

        $this->results['caching'] = [
            'cache_miss' => $cacheMissResult,
            'cache_hit' => $cacheHitResult,
            'speedup_factor' => $speedup,
            'cache_stats' => $resource->getCacheStats()
        ];

        echo sprintf(
            "  Cache miss: %.2fms, Cache hit: %.2fms, Speedup: %.1fx\n",
            $cacheMissResult['time_ms'],
            $cacheHitResult['time_ms'],
            $speedup
        );
    }

    /**
     * Benchmark format conversion performance
     */
    public function benchmarkConversion(string $testFile): void
    {
        echo "\nBenchmarking Conversion Performance...\n";

        $resource = new GedcomResource(cacheEnabled: false);
        $gedcom = $resource->import($testFile);

        // GEDCOM to Gedcom X conversion
        $this->startBenchmark();
        $tempGedcomX = tempnam(sys_get_temp_dir(), 'gedcomx_');
        $resource->exportGedcomX($gedcom, $tempGedcomX);
        $toGedcomXResult = $this->endBenchmark();

        // Gedcom X to GEDCOM conversion
        $this->startBenchmark();
        $tempGedcom = tempnam(sys_get_temp_dir(), 'gedcom_');
        $resource->exportGedcom($gedcom, $tempGedcom);
        $toGedcomResult = $this->endBenchmark();

        $this->results['conversion'] = [
            'to_gedcomx' => $toGedcomXResult,
            'to_gedcom' => $toGedcomResult,
            'gedcomx_file_size' => filesize($tempGedcomX),
            'gedcom_file_size' => filesize($tempGedcom)
        ];

        echo sprintf(
            "  To Gedcom X: %.2fms (%s), To GEDCOM: %.2fms (%s)\n",
            $toGedcomXResult['time_ms'],
            $this->formatBytes($this->results['conversion']['gedcomx_file_size']),
            $toGedcomResult['time_ms'],
            $this->formatBytes($this->results['conversion']['gedcom_file_size'])
        );

        // Cleanup
        unlink($tempGedcomX);
        unlink($tempGedcom);
    }

    /**
     * Benchmark memory usage patterns
     */
    public function benchmarkMemoryUsage(string $testFile): void
    {
        echo "\nBenchmarking Memory Usage...\n";

        $baseMemory = memory_get_usage(true);

        // Test with caching disabled
        $resource1 = new GedcomResource(cacheEnabled: false);
        $gedcom1 = $resource1->import($testFile);
        $noCacheMemory = memory_get_usage(true) - $baseMemory;

        // Reset
        unset($resource1, $gedcom1);
        gc_collect_cycles();

        // Test with caching enabled
        $resource2 = new GedcomResource(cacheEnabled: true);
        $gedcom2 = $resource2->import($testFile);
        $withCacheMemory = memory_get_usage(true) - $baseMemory;

        $this->results['memory'] = [
            'base_memory' => $baseMemory,
            'no_cache_memory' => $noCacheMemory,
            'with_cache_memory' => $withCacheMemory,
            'cache_overhead' => $withCacheMemory - $noCacheMemory
        ];

        echo sprintf(
            "  No cache: %s, With cache: %s, Overhead: %s\n",
            $this->formatBytes($noCacheMemory),
            $this->formatBytes($withCacheMemory),
            $this->formatBytes($this->results['memory']['cache_overhead'])
        );
    }

    /**
     * Benchmark large file streaming
     */
    public function benchmarkStreaming(string $largeFile): void
    {
        if (!file_exists($largeFile) || filesize($largeFile) < 100 * 1024 * 1024) {
            echo "Skipping streaming benchmark (requires file > 100MB)\n";
            return;
        }

        echo "\nBenchmarking Streaming Performance...\n";

        $resource = new GedcomResource(cacheEnabled: false);

        $this->startBenchmark();
        $gedcom = $resource->import($largeFile);
        $streamingResult = $this->endBenchmark();

        $this->results['streaming'] = [
            'file_size' => filesize($largeFile),
            'streaming_parse' => $streamingResult,
            'records_parsed' => $this->countRecords($gedcom)
        ];

        echo sprintf(
            "  Streaming parse: %.2fms, Memory peak: %s, File: %s\n",
            $streamingResult['time_ms'],
            $this->formatBytes($streamingResult['memory_peak']),
            $this->formatBytes($this->results['streaming']['file_size'])
        );
    }

    /**
     * Generate performance report
     */
    public function generateReport(): string
    {
        $report = "\nPerformance Benchmark Report\n";
        $report .= "============================\n\n";

        foreach ($this->results as $category => $data) {
            $report .= ucfirst($category) . " Performance:\n";
            $report .= str_repeat('-', strlen($category) + 13) . "\n";

            $report .= $this->formatCategoryReport($category, $data);
            $report .= "\n";
        }

        $report .= "PHP Version: " . PHP_VERSION . "\n";
        $report .= "Memory Limit: " . ini_get('memory_limit') . "\n";
        $report .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";

        return $report;
    }

    /**
     * Compare with baseline performance
     */
    public function compareWithBaseline(array $baseline): array
    {
        $comparison = [];

        foreach ($this->results as $category => $data) {
            if (!isset($baseline[$category])) {
                continue;
            }

            $comparison[$category] = $this->calculateImprovement($baseline[$category], $data);
        }

        return $comparison;
    }

    private function startBenchmark(): void
    {
        gc_collect_cycles(); // Clean up before measurement
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage(true);
    }

    private function endBenchmark(): array
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);

        return [
            'time_ms' => ($endTime - $this->startTime) * 1000,
            'memory_used' => $endMemory - $this->startMemory,
            'memory_peak' => $peakMemory,
            'timestamp' => time()
        ];
    }

    private function countRecords(?Gedcom $gedcom): int
    {
        if (!$gedcom) {
            return 0;
        }

        return count($gedcom->getIndi()) + 
               count($gedcom->getFam()) + 
               count($gedcom->getSour()) + 
               count($gedcom->getRepo()) + 
               count($gedcom->getNote()) + 
               count($gedcom->getObje());
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function formatCategoryReport(string $category, array $data): string
    {
        $report = '';

        match ($category) {
            'parsing' => $report = sprintf(
                "  Parse time: %.2fms\n  Memory peak: %s\n  Records: %d\n  Throughput: %.0f records/sec\n",
                $data['cold_parse']['time_ms'],
                $this->formatBytes($data['cold_parse']['memory_peak']),
                $data['records_parsed'],
                $data['records_parsed'] / ($data['cold_parse']['time_ms'] / 1000)
            ),
            'caching' => $report = sprintf(
                "  Cache miss: %.2fms\n  Cache hit: %.2fms\n  Speedup: %.1fx\n  Cache efficiency: %.1f%%\n",
                $data['cache_miss']['time_ms'],
                $data['cache_hit']['time_ms'],
                $data['speedup_factor'],
                (1 - $data['cache_hit']['time_ms'] / $data['cache_miss']['time_ms']) * 100
            ),
            'conversion' => $report = sprintf(
                "  To Gedcom X: %.2fms\n  To GEDCOM: %.2fms\n  Size ratio: %.2f\n",
                $data['to_gedcomx']['time_ms'],
                $data['to_gedcom']['time_ms'],
                $data['gedcomx_file_size'] / $data['gedcom_file_size']
            ),
            'memory' => $report = sprintf(
                "  Base usage: %s\n  With cache: %s\n  Overhead: %s (%.1f%%)\n",
                $this->formatBytes($data['no_cache_memory']),
                $this->formatBytes($data['with_cache_memory']),
                $this->formatBytes($data['cache_overhead']),
                ($data['cache_overhead'] / $data['no_cache_memory']) * 100
            ),
            default => $report = "  Data available\n"
        };

        return $report;
    }

    private function calculateImprovement(array $baseline, array $current): array
    {
        $improvement = [];

        // Calculate time improvements
        if (isset($baseline['time_ms']) && isset($current['time_ms'])) {
            $improvement['time_improvement'] = ($baseline['time_ms'] - $current['time_ms']) / $baseline['time_ms'] * 100;
        }

        // Calculate memory improvements
        if (isset($baseline['memory_peak']) && isset($current['memory_peak'])) {
            $improvement['memory_improvement'] = ($baseline['memory_peak'] - $current['memory_peak']) / $baseline['memory_peak'] * 100;
        }

        return $improvement;
    }
}