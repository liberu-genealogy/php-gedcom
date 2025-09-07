<?php

declare(strict_types=1);

namespace Gedcom;

use Gedcom\GedcomX\Parser as GedcomXParser;
use Gedcom\GedcomX\Generator as GedcomXGenerator;
use Gedcom\GedcomX\Transformer;
use InvalidArgumentException;

/**
 * GedcomResource - Unified interface for handling GEDCOM and Gedcom X files
 * 
 * This class provides a single entry point for importing and exporting
 * genealogical data in both traditional GEDCOM 5.5 and modern Gedcom X formats.
 */
class GedcomResource
{
    public const FORMAT_GEDCOM = 'gedcom';
    public const FORMAT_GEDCOMX = 'gedcomx';

    private Parser $gedcomParser;
    private GedcomXParser $gedcomxParser;
    private GedcomXGenerator $gedcomxGenerator;
    private Transformer $transformer;

    public function __construct()
    {
        $this->gedcomParser = new Parser();
        $this->gedcomxParser = new GedcomXParser();
        $this->gedcomxGenerator = new GedcomXGenerator();
        $this->transformer = new Transformer();
    }

    /**
     * Import genealogical data from a file (auto-detects format)
     */
    public function import(string $fileName): ?Gedcom
    {
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException("File not found: $fileName");
        }

        $format = $this->detectFileFormat($fileName);

        switch ($format) {
            case self::FORMAT_GEDCOM:
                return $this->importGedcom($fileName);
            case self::FORMAT_GEDCOMX:
                return $this->importGedcomX($fileName);
            default:
                throw new InvalidArgumentException("Unsupported file format: $fileName");
        }
    }

    /**
     * Import traditional GEDCOM file
     */
    public function importGedcom(string $fileName): ?Gedcom
    {
        return $this->gedcomParser->parse($fileName);
    }

    /**
     * Import Gedcom X file
     */
    public function importGedcomX(string $fileName): ?Gedcom
    {
        return $this->gedcomxParser->parse($fileName);
    }

    /**
     * Export genealogical data to a file in specified format
     */
    public function export(Gedcom $gedcom, string $fileName, string $format = self::FORMAT_GEDCOM): bool
    {
        switch ($format) {
            case self::FORMAT_GEDCOM:
                return $this->exportGedcom($gedcom, $fileName);
            case self::FORMAT_GEDCOMX:
                return $this->exportGedcomX($gedcom, $fileName);
            default:
                throw new InvalidArgumentException("Unsupported export format: $format");
        }
    }

    /**
     * Export to traditional GEDCOM format
     */
    public function exportGedcom(Gedcom $gedcom, string $fileName): bool
    {
        $gedcomContent = Writer::convert($gedcom);
        return file_put_contents($fileName, $gedcomContent) !== false;
    }

    /**
     * Export to Gedcom X format
     */
    public function exportGedcomX(Gedcom $gedcom, string $fileName): bool
    {
        return $this->gedcomxGenerator->generateToFile($gedcom, $fileName);
    }

    /**
     * Convert between formats
     */
    public function convert(string $inputFile, string $outputFile, string $outputFormat): bool
    {
        $gedcom = $this->import($inputFile);
        if (!$gedcom) {
            return false;
        }

        return $this->export($gedcom, $outputFile, $outputFormat);
    }

    /**
     * Convert GEDCOM to Gedcom X
     */
    public function convertGedcomToGedcomX(string $gedcomFile, string $gedcomxFile): bool
    {
        return $this->transformer->gedcomToGedcomX($gedcomFile, $gedcomxFile);
    }

    /**
     * Convert Gedcom X to GEDCOM
     */
    public function convertGedcomXToGedcom(string $gedcomxFile, string $gedcomFile): bool
    {
        return $this->transformer->gedcomXToGedcom($gedcomxFile, $gedcomFile);
    }

    /**
     * Detect file format based on content and extension
     */
    public function detectFileFormat(string $fileName): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Check by extension first
        if (in_array($extension, ['json', 'gedcomx'])) {
            return self::FORMAT_GEDCOMX;
        }

        if (in_array($extension, ['ged', 'gedcom'])) {
            return self::FORMAT_GEDCOM;
        }

        // Check by content
        $content = file_get_contents($fileName, false, null, 0, 1024);
        if ($content === false) {
            throw new InvalidArgumentException("Cannot read file: $fileName");
        }

        // Check if it's JSON (Gedcom X)
        $trimmedContent = trim($content);
        if (str_starts_with($trimmedContent, '{') || str_starts_with($trimmedContent, '[')) {
            $decoded = json_decode($trimmedContent, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // Check for Gedcom X specific structure
                if (isset($decoded['persons']) || isset($decoded['relationships']) || isset($decoded['sourceDescriptions'])) {
                    return self::FORMAT_GEDCOMX;
                }
            }
        }

        // Check if it's traditional GEDCOM
        if (preg_match('/^0\s+HEAD/', $content)) {
            return self::FORMAT_GEDCOM;
        }

        throw new InvalidArgumentException("Cannot determine file format: $fileName");
    }

    /**
     * Validate file format and structure
     */
    public function validate(string $fileName): array
    {
        $errors = [];

        try {
            $format = $this->detectFileFormat($fileName);

            switch ($format) {
                case self::FORMAT_GEDCOM:
                    $errors = $this->validateGedcom($fileName);
                    break;
                case self::FORMAT_GEDCOMX:
                    $errors = $this->validateGedcomX($fileName);
                    break;
            }
        } catch (\Exception $e) {
            $errors[] = "Validation error: " . $e->getMessage();
        }

        return $errors;
    }

    /**
     * Validate GEDCOM file
     */
    private function validateGedcom(string $fileName): array
    {
        $errors = [];

        try {
            $gedcom = $this->gedcomParser->parse($fileName);
            if (!$gedcom) {
                $errors[] = "Failed to parse GEDCOM file";
            }

            // Get parser errors if available
            if (method_exists($this->gedcomParser, 'getErrors')) {
                $parserErrors = $this->gedcomParser->getErrors();
                $errors = array_merge($errors, $parserErrors);
            }
        } catch (\Exception $e) {
            $errors[] = "GEDCOM validation error: " . $e->getMessage();
        }

        return $errors;
    }

    /**
     * Validate Gedcom X file
     */
    private function validateGedcomX(string $fileName): array
    {
        $errors = [];

        try {
            $content = file_get_contents($fileName);
            if ($content === false) {
                $errors[] = "Cannot read Gedcom X file";
                return $errors;
            }

            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = "Invalid JSON in Gedcom X file: " . json_last_error_msg();
                return $errors;
            }

            // Validate structure using transformer
            $validationErrors = $this->transformer->validateGedcomXData($data);
            $errors = array_merge($errors, $validationErrors);

        } catch (\Exception $e) {
            $errors[] = "Gedcom X validation error: " . $e->getMessage();
        }

        return $errors;
    }

    /**
     * Get supported file formats
     */
    public function getSupportedFormats(): array
    {
        return [
            self::FORMAT_GEDCOM => [
                'name' => 'GEDCOM 5.5',
                'description' => 'Traditional genealogical data format',
                'extensions' => ['ged', 'gedcom'],
                'mimeType' => 'application/x-gedcom'
            ],
            self::FORMAT_GEDCOMX => [
                'name' => 'Gedcom X',
                'description' => 'Modern JSON-based genealogical data format',
                'extensions' => ['json', 'gedcomx'],
                'mimeType' => 'application/json'
            ]
        ];
    }

    /**
     * Get file format information
     */
    public function getFormatInfo(string $format): ?array
    {
        $formats = $this->getSupportedFormats();
        return $formats[$format] ?? null;
    }

    /**
     * Check if a format is supported
     */
    public function isFormatSupported(string $format): bool
    {
        return array_key_exists($format, $this->getSupportedFormats());
    }

    /**
     * Get statistics about the genealogical data
     */
    public function getStatistics(Gedcom $gedcom): array
    {
        return [
            'individuals' => count($gedcom->getIndi()),
            'families' => count($gedcom->getFam()),
            'sources' => count($gedcom->getSour()),
            'repositories' => count($gedcom->getRepo()),
            'notes' => count($gedcom->getNote()),
            'media_objects' => count($gedcom->getObje()),
            'submitters' => count($gedcom->getSubm())
        ];
    }

    /**
     * Create a new empty Gedcom object
     */
    public function createNew(): Gedcom
    {
        return new Gedcom();
    }

    /**
     * Merge multiple Gedcom objects
     */
    public function merge(array $gedcoms): Gedcom
    {
        $merged = new Gedcom();

        foreach ($gedcoms as $gedcom) {
            if (!$gedcom instanceof Gedcom) {
                continue;
            }

            // Merge individuals
            foreach ($gedcom->getIndi() as $indi) {
                $merged->addIndi($indi);
            }

            // Merge families
            foreach ($gedcom->getFam() as $fam) {
                $merged->addFam($fam);
            }

            // Merge sources
            foreach ($gedcom->getSour() as $sour) {
                $merged->addSour($sour);
            }

            // Merge repositories
            foreach ($gedcom->getRepo() as $repo) {
                $merged->addRepo($repo);
            }

            // Merge notes
            foreach ($gedcom->getNote() as $note) {
                $merged->addNote($note);
            }

            // Merge media objects
            foreach ($gedcom->getObje() as $obje) {
                $merged->addObje($obje);
            }

            // Merge submitters
            foreach ($gedcom->getSubm() as $subm) {
                $merged->addSubm($subm);
            }
        }

        return $merged;
    }
}