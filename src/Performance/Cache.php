<?php

declare(strict_types=1);

namespace Gedcom\Performance;

/**
 * High-performance caching system for GEDCOM parsing (PHP 8.3+ Compatible)
 * 
 * Features:
 * - Memory-efficient LRU cache with lazy initialization
 * - File-based persistent cache with optimized serialization
 * - Automatic cache invalidation based on file modification time
 * - Configurable cache size limits and TTL
 */
class Cache
{
    private array $memoryCache = [];
    private array $accessOrder = [];
    private int $maxMemoryItems;
    private string $cacheDir;
    private int $defaultTtl;

    // Lazy-initialized file hashes for modification tracking
    private ?array $fileHashes = null;

    public function __construct(
        int $maxMemoryItems = 1000,
        string $cacheDir = null,
        int $defaultTtl = 3600
    ) {
        $this->maxMemoryItems = $maxMemoryItems;
        $this->cacheDir = $cacheDir ?? sys_get_temp_dir() . '/gedcom_cache';
        $this->defaultTtl = $defaultTtl;

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Get file hashes with lazy initialization
     */
    private function getFileHashes(): array
    {
        if ($this->fileHashes === null) {
            $this->fileHashes = $this->loadFileHashes();
        }
        return $this->fileHashes;
    }

    /**
     * Get cached data with automatic invalidation
     */
    public function get(string $key, string $sourceFile = null): mixed
    {
        // Check if source file has been modified
        if ($sourceFile && $this->isFileModified($sourceFile, $key)) {
            $this->delete($key);
            return null;
        }

        // Try memory cache first (fastest)
        if (isset($this->memoryCache[$key])) {
            $this->updateAccessOrder($key);
            return $this->memoryCache[$key]['data'];
        }

        // Try file cache
        $fileCacheData = $this->getFromFileCache($key);
        if ($fileCacheData !== null) {
            // Store in memory cache for faster subsequent access
            $this->setInMemoryCache($key, $fileCacheData);
            return $fileCacheData;
        }

        return null;
    }

    /**
     * Store data in cache with automatic cleanup
     */
    public function set(string $key, mixed $data, string $sourceFile = null, int $ttl = null): void
    {
        $ttl = $ttl ?? $this->defaultTtl;

        // Store file hash for invalidation
        if ($sourceFile) {
            $hashes = $this->getFileHashes();
            $hashes[$key] = [
                'hash' => md5_file($sourceFile),
                'mtime' => filemtime($sourceFile)
            ];
            $this->fileHashes = $hashes;
            $this->saveFileHashes();
        }

        // Store in memory cache
        $this->setInMemoryCache($key, $data);

        // Store in file cache for persistence
        $this->setInFileCache($key, $data, $ttl);
    }

    /**
     * Delete cached data
     */
    public function delete(string $key): void
    {
        unset($this->memoryCache[$key]);
        unset($this->accessOrder[$key]);
        
        if ($this->fileHashes !== null) {
            unset($this->fileHashes[$key]);
        }

        $filePath = $this->getCacheFilePath($key);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Clear all cache data
     */
    public function clear(): void
    {
        $this->memoryCache = [];
        $this->accessOrder = [];
        $this->fileHashes = [];

        // Clear file cache
        $files = glob($this->cacheDir . '/*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }

    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        $fileCacheSize = 0;
        $fileCacheCount = 0;

        $files = glob($this->cacheDir . '/*.cache');
        foreach ($files as $file) {
            $fileCacheSize += filesize($file);
            $fileCacheCount++;
        }

        return [
            'memory_items' => count($this->memoryCache),
            'memory_size_bytes' => $this->getMemoryCacheSize(),
            'file_items' => $fileCacheCount,
            'file_size_bytes' => $fileCacheSize,
            'cache_dir' => $this->cacheDir,
            'max_memory_items' => $this->maxMemoryItems
        ];
    }

    /**
     * Memory cache operations with LRU eviction
     */
    private function setInMemoryCache(string $key, mixed $data): void
    {
        // Remove if already exists to update position
        if (isset($this->memoryCache[$key])) {
            unset($this->accessOrder[$key]);
        }

        // Add to cache
        $this->memoryCache[$key] = [
            'data' => $data,
            'timestamp' => time()
        ];

        $this->accessOrder[$key] = time();

        // Evict oldest items if cache is full
        if (count($this->memoryCache) > $this->maxMemoryItems) {
            $this->evictLeastRecentlyUsed();
        }
    }

    private function updateAccessOrder(string $key): void
    {
        $this->accessOrder[$key] = time();
    }

    private function evictLeastRecentlyUsed(): void
    {
        // Sort by access time and remove oldest
        asort($this->accessOrder);
        $oldestKey = array_key_first($this->accessOrder);

        if ($oldestKey !== null) {
            unset($this->memoryCache[$oldestKey]);
            unset($this->accessOrder[$oldestKey]);
        }
    }

    /**
     * File cache operations with optimized serialization
     */
    private function getFromFileCache(string $key): mixed
    {
        $filePath = $this->getCacheFilePath($key);

        if (!file_exists($filePath)) {
            return null;
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }

        $cacheData = unserialize($content);

        // Check TTL
        if ($cacheData['expires'] < time()) {
            unlink($filePath);
            return null;
        }

        return $cacheData['data'];
    }

    private function setInFileCache(string $key, mixed $data, int $ttl): void
    {
        $filePath = $this->getCacheFilePath($key);

        $cacheData = [
            'data' => $data,
            'expires' => time() + $ttl,
            'created' => time()
        ];

        // Use optimized serialization for better performance
        $serialized = serialize($cacheData);
        file_put_contents($filePath, $serialized, LOCK_EX);
    }

    private function getCacheFilePath(string $key): string
    {
        $safeKey = preg_replace('/[^a-zA-Z0-9_-]/', '_', $key);
        return $this->cacheDir . '/' . $safeKey . '.cache';
    }

    /**
     * File modification tracking
     */
    private function isFileModified(string $sourceFile, string $key): bool
    {
        $hashes = $this->getFileHashes();
        if (!isset($hashes[$key])) {
            return true;
        }

        $currentHash = md5_file($sourceFile);
        $currentMtime = filemtime($sourceFile);

        $cached = $hashes[$key];

        return $cached['hash'] !== $currentHash || $cached['mtime'] !== $currentMtime;
    }

    private function loadFileHashes(): array
    {
        $hashFile = $this->cacheDir . '/file_hashes.json';

        if (!file_exists($hashFile)) {
            return [];
        }

        $content = file_get_contents($hashFile);
        return $content ? json_decode($content, true) : [];
    }

    private function saveFileHashes(): void
    {
        $hashFile = $this->cacheDir . '/file_hashes.json';
        $hashes = $this->fileHashes ?? [];
        file_put_contents($hashFile, json_encode($hashes), LOCK_EX);
    }

    private function getMemoryCacheSize(): int
    {
        return strlen(serialize($this->memoryCache));
    }

    /**
     * Cleanup expired cache files
     */
    public function cleanup(): int
    {
        $cleaned = 0;
        $files = glob($this->cacheDir . '/*.cache');

        foreach ($files as $file) {
            $content = file_get_contents($file);
            if ($content === false) {
                continue;
            }

            $cacheData = unserialize($content);
            if ($cacheData['expires'] < time()) {
                unlink($file);
                $cleaned++;
            }
        }

        return $cleaned;
    }

    /**
     * Generate cache key from multiple parameters
     */
    public static function generateKey(string ...$parts): string
    {
        return md5(implode('|', $parts));
    }
}