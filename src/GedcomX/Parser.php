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
 * GedcomX Parser - Parses Gedcom X JSON format files
 * 
 * Gedcom X is a modern genealogical data format that uses JSON
 * and follows RESTful principles for genealogical data exchange.
 */
class Parser
{
    private Gedcom $gedcom;
    private array $gedcomxData;
    private array $personMap = [];
    private array $relationshipMap = [];

    public function parse(string $fileName): ?Gedcom
    {
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException("File not found: $fileName");
        }

        $jsonContent = file_get_contents($fileName);
        if ($jsonContent === false) {
            throw new InvalidArgumentException("Could not read file: $fileName");
        }

        $this->gedcomxData = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Invalid JSON in file: $fileName - " . json_last_error_msg());
        }

        $this->gedcom = new Gedcom();
        $this->personMap = [];
        $this->relationshipMap = [];

        $this->parseGedcomXData();

        return $this->gedcom;
    }

    private function parseGedcomXData(): void
    {
        // Parse persons first
        if (isset($this->gedcomxData['persons'])) {
            foreach ($this->gedcomxData['persons'] as $person) {
                $this->parsePerson($person);
            }
        }

        // Parse relationships (families)
        if (isset($this->gedcomxData['relationships'])) {
            foreach ($this->gedcomxData['relationships'] as $relationship) {
                $this->parseRelationship($relationship);
            }
        }

        // Parse source descriptions
        if (isset($this->gedcomxData['sourceDescriptions'])) {
            foreach ($this->gedcomxData['sourceDescriptions'] as $sourceDesc) {
                $this->parseSourceDescription($sourceDesc);
            }
        }
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
        switch ($genderType) {
            case 'http://gedcomx.org/Male':
                return 'M';
            case 'http://gedcomx.org/Female':
                return 'F';
            default:
                return 'U'; // Unknown
        }
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
        $mapping = [
            'http://gedcomx.org/Birth' => 'BIRT',
            'http://gedcomx.org/Death' => 'DEAT',
            'http://gedcomx.org/Marriage' => 'MARR',
            'http://gedcomx.org/Divorce' => 'DIV',
            'http://gedcomx.org/Baptism' => 'BAPM',
            'http://gedcomx.org/Burial' => 'BURI',
            'http://gedcomx.org/Christening' => 'CHR',
            'http://gedcomx.org/Residence' => 'RESI',
            'http://gedcomx.org/Occupation' => 'OCCU',
        ];

        return $mapping[$factType] ?? null;
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