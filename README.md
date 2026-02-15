# php-gedcom
 ![Latest Stable Version](https://img.shields.io/github/release/liberu-genealogy/php-gedcom.svg)
[![Tests](https://github.com/liberu-genealogy/php-gedcom/actions/workflows/run-tests.yml/badge.svg)](https://github.com/liberu-genealogy/php-gedcom/actions/workflows/run-tests.yml)




## Requirements

* php-gedcom 2.0+ requires PHP 8.3 (or later). GEDCOM 5.5.1 only
* php-gedcom 3.0+ requires PHP 8.4 (or later). GEDCOM 5.5.1 only
* php-gedcom 4.0+ requires PHP 8.4 (or later). GEDCOM 5.5.1, GEDCOM 7.0 and GEDCOM X with performance optimizations

## Installation

There are two ways of installing php-gedcom.

### Composer

To install php-gedcom in your project using composer, simply add the following require line to your project's `composer.json` file:

    {
        "require": {
            "liberu-genealogy/php-gedcom": "2.0.*"
        }
    }

### Download and __autoload

If you are not using composer, you can download an archive of the source from GitHub and extract it into your project. You'll need to setup an autoloader for the files, unless you go through the painstaking process if requiring all the needed files one-by-one. Something like the following should suffice:

```php
spl_autoload_register(function ($class) {
    $pathToGedcom = __DIR__ . '/library/'; // TODO FIXME

    if (!substr(ltrim($class, '\\'), 0, 7) == 'Gedcom\\') {
        return;
    }

    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($pathToGedcom . $class)) {
        require_once($pathToGedcom . $class);
    }
});
```

## Performance Optimizations (PHP 8.4+)

php-gedcom 4.0+ includes significant performance improvements leveraging PHP 8.4 features:

### Key Optimizations

- **Streaming Parsers**: Automatic streaming for large files (>100MB) to reduce memory usage
- **Intelligent Caching**: LRU cache with file modification tracking and automatic invalidation
- **Property Hooks**: Lazy initialization of parsers and generators using PHP 8.4 property hooks
- **Optimized JSON Processing**: Enhanced JSON parsing with streaming support for large Gedcom X files
- **Memory Efficiency**: Reduced memory footprint through optimized data structures

### Performance Features

- **Caching System**: Automatic caching of parsed files with configurable TTL and size limits
- **Large File Support**: Streaming parsers handle files of any size without memory exhaustion
- **Format Detection**: Fast file format detection with content analysis
- **Batch Operations**: Optimized array operations using PHP 8.4 features

### Benchmarking

Run performance benchmarks to measure improvements:

```bash
# Basic benchmark
php examples/cli/performance-benchmark.php sample.ged

# Full benchmark with streaming and report
php examples/cli/performance-benchmark.php large.ged --streaming --report

# Save baseline for comparison
php examples/cli/performance-benchmark.php test.ged --baseline

# Compare with baseline
php examples/cli/performance-benchmark.php test.ged --compare
```

### Cache Configuration

```php
use Gedcom\GedcomResource;

// Enable caching with custom configuration
$resource = new GedcomResource(
    cacheEnabled: true,
    cacheConfig: [
        'memory_items' => 2000,           // Max items in memory cache
        'cache_dir' => '/tmp/gedcom',     // Cache directory
        'ttl' => 7200                     // Cache TTL in seconds
    ]
);

// Get cache statistics
$stats = $resource->getCacheStats();
echo "Memory items: " . $stats['memory_items'] . "\n";

// Clear cache when needed
$resource->clearCache();
```

### GEDCOM Format Support

php-gedcom 4.0+ supports both GEDCOM 5.5.1 and GEDCOM 7.0 formats. The library automatically detects the version when parsing and can write to either format.

#### Parsing GEDCOM Files

The parser automatically handles both GEDCOM 5.5.1 and 7.0 formats:

```php
$parser = new \Gedcom\Parser();

// Parse a GEDCOM 5.5.1 file
$gedcom551 = $parser->parse('family_tree_551.ged');

// Parse a GEDCOM 7.0 file
$gedcom70 = $parser->parse('family_tree_70.ged');

// Check the version
$head = $gedcom70->getHead();
$gedc = $head->getGedc();
$version = $gedc->getVersion(); // Returns "7.0" or "5.5.1"
```

#### Writing GEDCOM Files

You can export to either format by specifying the format constant:

```php
use Gedcom\Writer;

// Write as GEDCOM 5.5.1 (default)
$output551 = Writer::convert($gedcom, Writer::GEDCOM55);
file_put_contents('output_551.ged', $output551);

// Write as GEDCOM 7.0
$output70 = Writer::convert($gedcom, Writer::GEDCOM70);
file_put_contents('output_70.ged', $output70);
```

#### Version-Specific Features

The library handles version-specific features automatically:

| Feature | GEDCOM 5.5.1 | GEDCOM 7.0 |
|---------|--------------|------------|
| Unique Identifier | `_UID` (custom tag) | `UID` (standard tag) |
| Source Data Date | Not supported | `DATE` subfield |
| Source Data Text | Not supported | `TEXT` subfield |

When writing to a specific format:
- **GEDCOM 5.5.1**: Outputs `_UID` tags for unique identifiers
- **GEDCOM 7.0**: Outputs `UID` tags for unique identifiers

The parser reads both tag types, ensuring compatibility when converting between versions.

### Usage

To parse a GEDCOM file and load it into a collection of PHP Objects, simply instantiate a new Parser object and pass it the file name to parse. The resulting Gedcom object will contain all the information stored within the supplied GEDCOM file:

```php
$parser = new \Gedcom\Parser();
$gedcom = $parser->parse('tmp.ged');

foreach ($gedcom->getIndi() as $individual) {
    $names = $individual->getName();
    if (!empty($names)) {
        $name = reset($names); // Get the first name object from the array
        echo $individual->getId() . ': ' . $name->getSurn() . ', ' . $name->getGivn() . PHP_EOL;
    }
}
```
## Contributing 

Pull requests are welcome, as are issues.


## License

MIT License (see License.md). This means you must retain the copyright and permission notice is all copies, or substantial portions of this software. 

## Contributors

<a href = "https://github.com/liberu-genealogy/php-gedcom/graphs/contributors">
  <img src = "https://contrib.rocks/image?repo=liberu-genealogy/php-gedcom"/>
</a>
