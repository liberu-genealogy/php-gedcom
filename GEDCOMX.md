# Gedcom X Support

This library now supports the modern Gedcom X format alongside traditional GEDCOM 5.5. Gedcom X is a JSON-based genealogical data format designed for web applications and APIs.

## Features

- **Import Gedcom X files** - Parse JSON-based Gedcom X files into internal data structures
- **Export to Gedcom X** - Convert internal data to Gedcom X JSON format
- **Bidirectional conversion** - Convert between GEDCOM 5.5 and Gedcom X formats
- **Data transformation** - Clean API for transforming data between formats
- **Validation** - Validate both GEDCOM and Gedcom X files
- **CLI tools** - Command-line utilities for batch processing
- **Web interface** - Simple web form for file conversion

## Quick Start

### Basic Usage

```php
use Gedcom\GedcomResource;

$resource = new GedcomResource();

// Import any supported format (auto-detects)
$gedcom = $resource->import('family.json');  // Gedcom X
$gedcom = $resource->import('family.ged');   // GEDCOM 5.5

// Export to any format
$resource->export($gedcom, 'output.json', GedcomResource::FORMAT_GEDCOMX);
$resource->export($gedcom, 'output.ged', GedcomResource::FORMAT_GEDCOM);

// Convert between formats
$resource->convert('input.ged', 'output.json', GedcomResource::FORMAT_GEDCOMX);
```

### Gedcom X Parser

```php
use Gedcom\GedcomX\Parser;

$parser = new Parser();
$gedcom = $parser->parse('family.json');

foreach ($gedcom->getIndi() as $individual) {
    $names = $individual->getName();
    if (!empty($names)) {
        $name = reset($names);
        echo $individual->getId() . ': ' . $name->getSurn() . ', ' . $name->getGivn() . PHP_EOL;
    }
}
```

### Gedcom X Generator

```php
use Gedcom\GedcomX\Generator;

$generator = new Generator();

// Generate JSON string
$json = $generator->generate($gedcom);

// Save to file
$generator->generateToFile($gedcom, 'output.json');
```

### Data Transformation

```php
use Gedcom\GedcomX\Transformer;

$transformer = new Transformer();

// Convert files
$transformer->gedcomToGedcomX('input.ged', 'output.json');
$transformer->gedcomXToGedcom('input.json', 'output.ged');

// Transform data structures
$personData = $transformer->transformPersonData($gedcomxPerson);
$nameData = $transformer->transformNameData($gedcomxName);
```

## Command Line Tools

### Import Gedcom X Files

```bash
php examples/cli/gedcomx-import.php family.json
php examples/cli/gedcomx-import.php family.json family.ged
```

### Export to Gedcom X

```bash
php examples/cli/gedcomx-export.php family.ged family.json
```

### Universal Format Converter

```bash
php examples/cli/format-converter.php input.ged output.json
php examples/cli/format-converter.php input.json output.ged --validate --stats
```

## Web Interface

A simple web interface is provided for file conversion:

```bash
# Serve the web interface
php -S localhost:8000 examples/web/gedcomx-interface.php
```

Then open http://localhost:8000 in your browser to upload and convert files.

## Gedcom X Format Overview

Gedcom X uses JSON and follows these key principles:

- **Persons** - Individual people with names, gender, and facts
- **Relationships** - Connections between persons (couples, parent-child)
- **Facts** - Events and attributes (birth, death, marriage, etc.)
- **Sources** - Documentation and evidence
- **URIs** - Standardized identifiers for types and references

### Example Gedcom X Structure

```json
{
  "persons": [
    {
      "id": "p1",
      "names": [
        {
          "nameForms": [
            {
              "fullText": "John /Smith/",
              "parts": [
                {
                  "type": "http://gedcomx.org/Given",
                  "value": "John"
                },
                {
                  "type": "http://gedcomx.org/Surname",
                  "value": "Smith"
                }
              ]
            }
          ]
        }
      ],
      "gender": {
        "type": "http://gedcomx.org/Male"
      },
      "facts": [
        {
          "type": "http://gedcomx.org/Birth",
          "date": {
            "original": "1 Jan 1950"
          },
          "place": {
            "original": "New York, NY"
          }
        }
      ]
    }
  ],
  "relationships": [
    {
      "id": "r1",
      "type": "http://gedcomx.org/ParentChild",
      "person1": {
        "resource": "#persons/p1"
      },
      "person2": {
        "resource": "#persons/p2"
      }
    }
  ]
}
```

## Supported Data Mappings

### Person Data
- **Names** - Given names, surnames, prefixes, suffixes
- **Gender** - Male, Female, Unknown
- **Facts/Events** - Birth, death, marriage, residence, occupation, etc.

### Relationships
- **Couple** - Marriage relationships
- **Parent-Child** - Family relationships

### Events/Facts
- Birth → `http://gedcomx.org/Birth`
- Death → `http://gedcomx.org/Death`
- Marriage → `http://gedcomx.org/Marriage`
- Baptism → `http://gedcomx.org/Baptism`
- Burial → `http://gedcomx.org/Burial`
- Residence → `http://gedcomx.org/Residence`
- Occupation → `http://gedcomx.org/Occupation`

## Validation

Both formats can be validated:

```php
$resource = new GedcomResource();

// Validate any supported file
$errors = $resource->validate('family.json');
if (empty($errors)) {
    echo "File is valid!";
} else {
    foreach ($errors as $error) {
        echo "Error: $error\n";
    }
}
```

## Error Handling

All classes throw appropriate exceptions for error conditions:

```php
try {
    $gedcom = $resource->import('nonexistent.json');
} catch (InvalidArgumentException $e) {
    echo "File error: " . $e->getMessage();
} catch (Exception $e) {
    echo "General error: " . $e->getMessage();
}
```

## Performance Considerations

- **Large files** - The parser loads entire files into memory
- **Memory usage** - Consider memory limits for very large genealogical databases
- **Validation** - Optional validation can be skipped for better performance

## Contributing

When adding new Gedcom X features:

1. Follow the [Gedcom X specification](http://www.gedcomx.org/)
2. Add appropriate unit tests
3. Update documentation
4. Ensure backward compatibility with existing GEDCOM functionality

## Resources

- [Gedcom X Specification](http://www.gedcomx.org/)
- [Gedcom X GitHub](https://github.com/FamilySearch/gedcomx)
- [GEDCOM 5.5 Specification](https://www.familysearch.org/developers/docs/gedcom/)