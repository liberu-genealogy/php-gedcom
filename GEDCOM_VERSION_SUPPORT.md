# GEDCOM Version Support

## Supported Versions

php-gedcom 4.0+ supports both GEDCOM 5.5.1 and GEDCOM 7.0 formats with full parsing and writing capabilities.

## Version Differences

### GEDCOM 5.5.1 vs GEDCOM 7.0

| Feature | GEDCOM 5.5.1 | GEDCOM 7.0 | Implementation Status |
|---------|--------------|------------|----------------------|
| **Unique Identifier Tag** | `_UID` (custom extension) | `UID` (standard tag) | ✅ Fully supported |
| **Source Data Date** | Not supported | `DATE` subfield under SOURCE/DATA | ✅ Parsed and stored |
| **Source Data Text** | Not supported | `TEXT` subfield under SOURCE/DATA | ✅ Parsed and stored |
| **Header Structure** | Standard GEDCOM 5.5.1 | GEDCOM 7.0 format | ✅ Auto-detected |
| **Character Encoding** | Various (UTF-8, ANSEL, etc.) | UTF-8 recommended | ✅ Supported |

## Usage

### Parsing Files

The parser automatically detects and handles both GEDCOM versions:

```php
use Gedcom\Parser;

$parser = new Parser();

// Parse GEDCOM 5.5.1 file
$gedcom551 = $parser->parse('family_tree_551.ged');

// Parse GEDCOM 7.0 file  
$gedcom70 = $parser->parse('family_tree_70.ged');

// Check the detected version
$head = $gedcom70->getHead();
$gedc = $head->getGedc();
$version = $gedc->getVersion(); // Returns "7.0" or "5.5.1"
```

### Writing Files

You can write to either format explicitly:

```php
use Gedcom\Writer;

// Write as GEDCOM 5.5.1
$output551 = Writer::convert($gedcom, Writer::GEDCOM55);
file_put_contents('output_551.ged', $output551);

// Write as GEDCOM 7.0
$output70 = Writer::convert($gedcom, Writer::GEDCOM70);
file_put_contents('output_70.ged', $output70);
```

## Format-Specific Behavior

### UID Tags

When writing GEDCOM files, the library uses the appropriate UID tag format:

- **GEDCOM 5.5.1**: Outputs `_UID` tags (custom extension with underscore prefix)
- **GEDCOM 7.0**: Outputs `UID` tags (standard tag without underscore)

Example GEDCOM 5.5.1 output:
```
0 @I1@ INDI
1 NAME John /Doe/
1 _UID 123e4567-e89b-12d3-a456-426614174000
```

Example GEDCOM 7.0 output:
```
0 @I1@ INDI
1 NAME John /Doe/
1 UID 123e4567-e89b-12d3-a456-426614174000
```

### Source Data Extensions

GEDCOM 7.0 supports additional source data fields:

```
0 @S1@ SOUR
1 DATA
2 DATE 2024
2 TEXT Sample source data text
```

These fields are parsed when present but only written in GEDCOM 7.0 format.

## Version Detection

The library detects the GEDCOM version from the HEAD record:

```php
$head = $gedcom->getHead();
$gedc = $head->getGedc();
$version = $gedc->getVersion();

if ($version === '7.0') {
    echo "This is a GEDCOM 7.0 file";
} elseif ($version === '5.5.1') {
    echo "This is a GEDCOM 5.5.1 file";
}
```

## Backward Compatibility

All GEDCOM 5.5.1 features remain fully supported. The library:

- ✅ Parses both `_UID` and `UID` tags
- ✅ Stores both variants separately in the data model
- ✅ Writes the appropriate variant based on target format
- ✅ Maintains all existing 5.5.1 functionality

## Migration Between Versions

You can easily convert between GEDCOM versions:

```php
use Gedcom\Parser;
use Gedcom\Writer;

$parser = new Parser();

// Read GEDCOM 5.5.1 file
$gedcom = $parser->parse('old_format_551.ged');

// Write as GEDCOM 7.0
$output70 = Writer::convert($gedcom, Writer::GEDCOM70);
file_put_contents('new_format_70.ged', $output70);
```

Note: The library preserves all data during conversion, but version-specific features (like `_UID` vs `UID`) will be converted to the target format's standard.

## Testing

Test files for both versions are available:

- `tests/gedcom551_sample.ged` - GEDCOM 5.5.1 sample
- `tests/gedcom70_sample.ged` - GEDCOM 7.0 sample
- `tests/test_writer_version.php` - Writer version tests
- `tests/test_gedcom70.php` - Comprehensive integration tests (requires PHP 8.4+)

Run tests:

```bash
# Test Writer version support (PHP 8.3+)
php tests/test_writer_version.php

# Full integration tests (PHP 8.4+)
php tests/test_gedcom70.php
```

## Known Limitations

1. **PHP Version**: Full integration tests require PHP 8.4+ due to property hooks in the Parser class
2. **Character Set**: While the library supports various encodings, UTF-8 is strongly recommended for GEDCOM 7.0
3. **Validation**: The library focuses on parsing and writing; full GEDCOM specification validation is not enforced

## Future Enhancements

Planned improvements for GEDCOM 7.0 support:

- [ ] Additional GEDCOM 7.0-specific tags and structures
- [ ] Validation against GEDCOM 7.0 specification
- [ ] Performance optimizations for large 7.0 files
- [ ] Enhanced error reporting for version-specific issues
