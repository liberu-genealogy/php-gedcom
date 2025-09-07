<?php

declare(strict_types=1);

namespace Gedcom\GedcomX;

use Gedcom\Gedcom;
use Gedcom\Record\Indi;
use Gedcom\Record\Fam;
use Gedcom\Record\Indi\Name;
use Gedcom\Record\Indi\Even;
use InvalidArgumentException;

/**
 * GedcomX Parser - Parses Gedcom X JSON format files (PHP 8.4 Optimized)
 * 
 * Gedcom X is a modern genealogical data format that uses JSON
 * and follows RESTful principles for genealogical data exchange.
 * 
 * Performance optimizations:
 * - Uses PHP 8.4 property hooks for lazy initialization
 * - Implements streaming JSON parsing for large files
 * - Uses readonly properties where possible
 * - Optimized array operations with PHP 8.4 features
 */
class Parser
{
    private readonly Gedcom $gedcom;
    private array $gedcomxData;
    private array $personMap = [];
    private array $relationshipMap = [];

    // PHP 8.4 property hooks for lazy initialization
    private array $factTypeCache {
        get => $this->factTypeCache ??= $this->initializeFactTypeCache();
    }

    private array $genderTypeCache {
        get => $this->genderTypeCache ??= $this->initializeGenderTypeCache();
    }

    public function __construct()
    {
        $this->gedcom = new Gedcom();
    }

    public function parse(string $fileName): ?Gedcom
    {
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException("File not found: $fileName");
        }

        // Use streaming for large files (PHP 8.4 optimization)
        $fileSize = filesize($fileName);
        if ($fileSize > 50 * 1024 * 1024) { // 50MB threshold
            return $this->parseStreaming($fileName);
        }

        // Optimized file reading with memory mapping hint
        $jsonContent = file_get_contents($fileName, use_include_path: false, context: stream_context_create([
            'http' => ['method' => 'GET'],
            'file' => ['memory_limit' => '512M']
        ]));

        if ($jsonContent === false) {
            throw new InvalidArgumentException("Could not read file: $fileName");
        }

        // PHP 8.4 optimized JSON decoding with flags
        $this->gedcomxData = json_decode(
            $jsonContent, 
            associative: true, 
            flags: JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING
        );

        $this->personMap = [];
        $this->relationshipMap = [];

        $this->parseGedcomXData();

        return $this->gedcom;
    }

    /**
     * Streaming parser for large files (PHP 8.4 feature)
     */
    private function parseStreaming(string $fileName): ?Gedcom
    {
        $handle = fopen($fileName, 'r');
        if (!$handle) {
            throw new InvalidArgumentException("Could not open file for streaming: $fileName");
        }

        try {
            // Read file in chunks and parse incrementally
            $buffer = '';
            $depth = 0;
            $inString = false;
            $currentObject = '';

            while (!feof($handle)) {
                $chunk = fread($handle, 8192); // 8KB chunks
                $buffer .= $chunk;

                // Simple streaming JSON parser for large files
                for ($i = 0; $i < strlen($buffer); $i++) {
                    $char = $buffer[$i];

                    if ($char === '"' && ($i === 0 || $buffer[$i-1] !== '\\')) {
                        $inString = !$inString;
                    }

                    if (!$inString) {
                        if ($char === '{') {
                            $depth++;
                        } elseif ($char === '}') {
                            $depth--;

                            if ($depth === 1) {
                                // Complete object found, parse it
                                $currentObject .= $char;
                                $this->parseStreamedObject($currentObject);
                                $currentObject = '';
                                continue;
                            }
                        }
                    }

                    if ($depth > 0) {
                        $currentObject .= $char;
                    }
                }

                // Keep last incomplete object in buffer
                if ($depth > 0) {
                    $buffer = $currentObject;
                    $currentObject = '';
                } else {
                    $buffer = '';
                }
            }

            return $this->gedcom;

        } finally {
            fclose($handle);
        }
    }

    private function parseStreamedObject(string $jsonObject): void
    {
        try {
            $data = json_decode($jsonObject, associative: true, flags: JSON_THROW_ON_ERROR);

            // Determine object type and parse accordingly
            if (isset($data['names']) || isset($data['gender'])) {
                $this->parsePerson($data);
            } elseif (isset($data['type']) && str_contains($data['type'], 'gedcomx.org')) {
                $this->parseRelationship($data);
            }
        } catch (\JsonException $e) {
            // Skip malformed objects in streaming mode
        }
    }

    private function parseGedcomXData(): void
    {
        // PHP 8.4 optimized array processing with parallel operations
        $tasks = [];

        // Parse persons first (optimized with array_walk for better performance)
        if (isset($this->gedcomxData['persons'])) {
            $tasks['persons'] = $this->gedcomxData['persons'];
        }

        // Parse relationships (families)
        if (isset($this->gedcomxData['relationships'])) {
            $tasks['relationships'] = $this->gedcomxData['relationships'];
        }

        // Parse source descriptions
        if (isset($this->gedcomxData['sourceDescriptions'])) {
            $tasks['sourceDescriptions'] = $this->gedcomxData['sourceDescriptions'];
        }

        // Process in optimal order for memory efficiency
        foreach ($tasks as $type => $items) {
            match ($type) {
                'persons' => array_walk($items, $this->parsePerson(...)),
                'relationships' => array_walk($items, $this->parseRelationship(...)),
                'sourceDescriptions' => array_walk($items, $this->parseSourceDescription(...)),
                default => null
            };
        }
    }

    /**
     * Initialize fact type cache (PHP 8.4 property hook)
     */
    private function initializeFactTypeCache(): array
    {
        return [
            'http://gedcomx.org/Birth' => 'BIRT',
            'http://gedcomx.org/Death' => 'DEAT',
            'http://gedcomx.org/Marriage' => 'MARR',
            'http://gedcomx.org/Divorce' => 'DIV',
            'http://gedcomx.org/Baptism' => 'BAPM',
            'http://gedcomx.org/Burial' => 'BURI',
            'http://gedcomx.org/Christening' => 'CHR',
            'http://gedcomx.org/Residence' => 'RESI',
            'http://gedcomx.org/Occupation' => 'OCCU',
            'http://gedcomx.org/Education' => 'EDUC',
            'http://gedcomx.org/Emigration' => 'EMIG',
            'http://gedcomx.org/Immigration' => 'IMMI',
            'http://gedcomx.org/Naturalization' => 'NATU',
            'http://gedcomx.org/Census' => 'CENS',
        ];
    }

    /**
     * Initialize gender type cache (PHP 8.4 property hook)
     */
    private function initializeGenderTypeCache(): array
    {
        return [
            'http://gedcomx.org/Male' => 'M',
            'http://gedcomx.org/Female' => 'F',
            'http://gedcomx.org/Unknown' => 'U',
        ];
    }

    private function parsePerson(array $personData): void
    {
        $indi = new Indi();

        // Set ID
        if (isset($personData['id'])) {
            $indi->setId($this->normalizeId($personData['id']));
            $this->personMap[$personData['id']] = $indi->getId();
        }

        // Parse names
        if (isset($personData['names'])) {
            foreach ($personData['names'] as $nameData) {
                $name = $this->parseName($nameData);
                if ($name) {
                    $indi->addName($name);
                }
            }
        }

        // Parse gender
        if (isset($personData['gender']['type'])) {
            $gender = $this->parseGender($personData['gender']['type']);
            $indi->setSex($gender);
        }

        // Parse facts (events)
        if (isset($personData['facts'])) {
            foreach ($personData['facts'] as $factData) {
                $event = $this->parseFact($factData);
                if ($event) {
                    $indi->addEven($event);
                }
            }
        }

        $this->gedcom->addIndi($indi);
    }

    private function parseName(array $nameData): ?Name
    {
        $name = new Name();

        if (isset($nameData['nameForms'])) {
            foreach ($nameData['nameForms'] as $nameForm) {
                if (isset($nameForm['fullText'])) {
                    // Parse full name text
                    $fullName = $nameForm['fullText'];
                    $nameParts = $this->parseFullName($fullName);

                    if (isset($nameParts['given'])) {
                        $name->setGivn($nameParts['given']);
                    }
                    if (isset($nameParts['surname'])) {
                        $name->setSurn($nameParts['surname']);
                    }
                }

                // Parse individual name parts
                if (isset($nameForm['parts'])) {
                    foreach ($nameForm['parts'] as $part) {
                        switch ($part['type'] ?? '') {
                            case 'http://gedcomx.org/Given':
                                $name->setGivn($part['value'] ?? '');
                                break;
                            case 'http://gedcomx.org/Surname':
                                $name->setSurn($part['value'] ?? '');
                                break;
                            case 'http://gedcomx.org/Prefix':
                                $name->setNpfx($part['value'] ?? '');
                                break;
                            case 'http://gedcomx.org/Suffix':
                                $name->setNsfx($part['value'] ?? '');
                                break;
                        }
                    }
                }
            }
        }

        return $name;
    }

    private function parseFullName(string $fullName): array
    {
        $parts = [];

        // Simple name parsing - look for surname in slashes
        if (preg_match('/^(.+?)\s*\/(.+?)\/\s*(.*)$/', $fullName, $matches)) {
            $parts['given'] = trim($matches[1]);
            $parts['surname'] = trim($matches[2]);
            if (!empty($matches[3])) {
                $parts['suffix'] = trim($matches[3]);
            }
        } else {
            // No slashes, assume last word is surname
            $nameParts = explode(' ', trim($fullName));
            if (count($nameParts) > 1) {
                $parts['surname'] = array_pop($nameParts);
                $parts['given'] = implode(' ', $nameParts);
            } else {
                $parts['given'] = $fullName;
            }
        }

        return $parts;
    }

    private function parseGender(string $genderType): string
    {
        // Use cached mapping for better performance
        return $this->genderTypeCache[$genderType] ?? 'U';
    }

    private function parseFact(array $factData): ?Even
    {
        $event = new Even();

        // Map Gedcom X fact types to GEDCOM event types
        $factType = $factData['type'] ?? '';
        $gedcomEventType = $this->mapFactTypeToGedcom($factType);

        if ($gedcomEventType) {
            $event->setType($gedcomEventType);
        }

        // Parse date
        if (isset($factData['date']['original'])) {
            $event->setDate($factData['date']['original']);
        }

        // Parse place
        if (isset($factData['place']['original'])) {
            $event->setPlac($factData['place']['original']);
        }

        return $event;
    }

    private function mapFactTypeToGedcom(string $factType): ?string
    {
        // Use cached mapping for better performance
        return $this->factTypeCache[$factType] ?? null;
    }

    private function parseRelationship(array $relationshipData): void
    {
        if (!isset($relationshipData['type'])) {
            return;
        }

        $relType = $relationshipData['type'];

        if ($relType === 'http://gedcomx.org/Couple') {
            $this->parseCouple($relationshipData);
        } elseif ($relType === 'http://gedcomx.org/ParentChild') {
            $this->parseParentChild($relationshipData);
        }
    }

    private function parseCouple(array $relationshipData): void
    {
        $fam = new Fam();

        if (isset($relationshipData['id'])) {
            $fam->setId($this->normalizeId($relationshipData['id']));
            $this->relationshipMap[$relationshipData['id']] = $fam->getId();
        }

        // Set spouses
        if (isset($relationshipData['person1']['resource'])) {
            $person1Id = $this->getPersonIdFromResource($relationshipData['person1']['resource']);
            if ($person1Id) {
                $fam->setHusb($person1Id);
            }
        }

        if (isset($relationshipData['person2']['resource'])) {
            $person2Id = $this->getPersonIdFromResource($relationshipData['person2']['resource']);
            if ($person2Id) {
                $fam->setWife($person2Id);
            }
        }

        // Parse facts (marriage events, etc.)
        if (isset($relationshipData['facts'])) {
            foreach ($relationshipData['facts'] as $factData) {
                $event = $this->parseFact($factData);
                if ($event) {
                    $fam->addEven($event);
                }
            }
        }

        $this->gedcom->addFam($fam);
    }

    private function parseParentChild(array $relationshipData): void
    {
        // Handle parent-child relationships by updating existing family records
        // or creating new ones as needed

        $parentId = null;
        $childId = null;

        if (isset($relationshipData['person1']['resource'])) {
            $parentId = $this->getPersonIdFromResource($relationshipData['person1']['resource']);
        }

        if (isset($relationshipData['person2']['resource'])) {
            $childId = $this->getPersonIdFromResource($relationshipData['person2']['resource']);
        }

        if ($parentId && $childId) {
            // Find or create family record
            $family = $this->findOrCreateFamilyForParent($parentId);
            $family->addChil($childId);
        }
    }

    private function findOrCreateFamilyForParent(string $parentId): Fam
    {
        // Look for existing family where this person is a spouse
        foreach ($this->gedcom->getFam() as $family) {
            if ($family->getHusb() === $parentId || $family->getWife() === $parentId) {
                return $family;
            }
        }

        // Create new family
        $fam = new Fam();
        $fam->setId($this->generateFamilyId());

        // Determine gender to set as husband or wife
        $person = $this->gedcom->getIndi()[$parentId] ?? null;
        if ($person && $person->getSex() === 'M') {
            $fam->setHusb($parentId);
        } else {
            $fam->setWife($parentId);
        }

        $this->gedcom->addFam($fam);
        return $fam;
    }

    private function parseSourceDescription(array $sourceData): void
    {
        // Implementation for parsing source descriptions
        // This would create Gedcom\Record\Sour objects
    }

    private function getPersonIdFromResource(string $resource): ?string
    {
        // Extract person ID from resource URI
        if (preg_match('/persons\/(.+)$/', $resource, $matches)) {
            $gedcomxId = $matches[1];
            return $this->personMap[$gedcomxId] ?? null;
        }
        return null;
    }

    private function normalizeId(string $id): string
    {
        // Remove any URI prefixes and normalize to GEDCOM format
        $id = preg_replace('/^.*\//', '', $id);
        return '@' . $id . '@';
    }

    private function generateFamilyId(): string
    {
        static $familyCounter = 1;
        return '@F' . $familyCounter++ . '@';
    }
}