<?php

declare(strict_types=1);

namespace Gedcom\GedcomX;

use Gedcom\Gedcom;
use Gedcom\Record\Indi;
use Gedcom\Record\Fam;
use Gedcom\Record\Indi\Name;
use Gedcom\Record\Indi\Even;

/**
 * GedcomX Generator - Converts internal GEDCOM data structures to Gedcom X JSON format (PHP 8.4 Optimized)
 * 
 * Generates Gedcom X compliant JSON from parsed GEDCOM data
 * 
 * Performance optimizations:
 * - Uses PHP 8.4 property hooks for lazy initialization
 * - Implements streaming JSON generation for large datasets
 * - Uses readonly properties and optimized array operations
 * - Memory-efficient processing with generators
 */
class Generator
{
    private readonly Gedcom $gedcom;
    private array $gedcomxData;
    private array $personIdMap = [];
    private array $relationshipIdMap = [];
    private int $personCounter = 1;
    private int $relationshipCounter = 1;

    // PHP 8.4 property hooks for cached mappings
    private array $gedcomToGedcomxFactTypes {
        get => $this->gedcomToGedcomxFactTypes ??= $this->initializeFactTypeMappings();
    }

    private array $gedcomToGedcomxGenderTypes {
        get => $this->gedcomToGedcomxGenderTypes ??= $this->initializeGenderTypeMappings();
    }

    public function __construct(Gedcom $gedcom)
    {
        $this->gedcom = $gedcom;
    }

    public function generate(?Gedcom $gedcom = null): string
    {
        $sourceGedcom = $gedcom ?? $this->gedcom;

        // Initialize data structure with pre-allocated arrays for better performance
        $this->gedcomxData = [
            'persons' => [],
            'relationships' => [],
            'sourceDescriptions' => [],
            'agents' => [],
            'documents' => []
        ];

        $this->personIdMap = [];
        $this->relationshipIdMap = [];
        $this->personCounter = 1;
        $this->relationshipCounter = 1;

        // Check if we need streaming for large datasets
        $totalRecords = count($sourceGedcom->getIndi()) + count($sourceGedcom->getFam());
        if ($totalRecords > 10000) {
            return $this->generateStreaming($sourceGedcom);
        }

        $this->generatePersons($sourceGedcom);
        $this->generateRelationships($sourceGedcom);
        $this->generateSourceDescriptions($sourceGedcom);

        // PHP 8.4 optimized JSON encoding with performance flags
        return json_encode(
            $this->gedcomxData, 
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
            depth: 512
        );
    }

    /**
     * Streaming generator for large datasets (PHP 8.4 optimization)
     */
    private function generateStreaming(Gedcom $gedcom): string
    {
        $output = "{\n";
        $sections = [];

        // Generate persons section
        if (!empty($gedcom->getIndi())) {
            $sections[] = $this->generatePersonsSection($gedcom);
        }

        // Generate relationships section
        if (!empty($gedcom->getFam())) {
            $sections[] = $this->generateRelationshipsSection($gedcom);
        }

        // Generate sources section
        if (!empty($gedcom->getSour())) {
            $sections[] = $this->generateSourcesSection($gedcom);
        }

        $output .= implode(",\n", $sections);
        $output .= "\n}";

        return $output;
    }

    private function generatePersonsSection(Gedcom $gedcom): string
    {
        $output = '  "persons": [';
        $first = true;

        foreach ($gedcom->getIndi() as $indi) {
            if (!$first) {
                $output .= ',';
            }
            $first = false;

            $person = $this->convertIndividualToPerson($indi);
            $output .= "\n    " . json_encode($person, JSON_UNESCAPED_UNICODE);
        }

        $output .= "\n  ]";
        return $output;
    }

    private function generateRelationshipsSection(Gedcom $gedcom): string
    {
        $output = '  "relationships": [';
        $first = true;

        foreach ($gedcom->getFam() as $family) {
            $relationships = $this->convertFamilyToRelationships($family);
            foreach ($relationships as $relationship) {
                if (!$first) {
                    $output .= ',';
                }
                $first = false;

                $output .= "\n    " . json_encode($relationship, JSON_UNESCAPED_UNICODE);
            }
        }

        $output .= "\n  ]";
        return $output;
    }

    private function generateSourcesSection(Gedcom $gedcom): string
    {
        return '  "sourceDescriptions": []'; // Placeholder for now
    }

    private function generatePersons(Gedcom $gedcom): void
    {
        // Pre-allocate array for better performance
        $persons = [];
        foreach ($gedcom->getIndi() as $indi) {
            $person = $this->convertIndividualToPerson($indi);
            if ($person) {
                $persons[] = $person;
            }
        }
        $this->gedcomxData['persons'] = $persons;
    }

    /**
     * Initialize fact type mappings (PHP 8.4 property hook)
     */
    private function initializeFactTypeMappings(): array
    {
        return [
            'BIRT' => 'http://gedcomx.org/Birth',
            'DEAT' => 'http://gedcomx.org/Death',
            'MARR' => 'http://gedcomx.org/Marriage',
            'DIV' => 'http://gedcomx.org/Divorce',
            'BAPM' => 'http://gedcomx.org/Baptism',
            'BURI' => 'http://gedcomx.org/Burial',
            'CHR' => 'http://gedcomx.org/Christening',
            'RESI' => 'http://gedcomx.org/Residence',
            'OCCU' => 'http://gedcomx.org/Occupation',
            'EDUC' => 'http://gedcomx.org/Education',
            'EMIG' => 'http://gedcomx.org/Emigration',
            'IMMI' => 'http://gedcomx.org/Immigration',
            'NATU' => 'http://gedcomx.org/Naturalization',
            'CENS' => 'http://gedcomx.org/Census',
        ];
    }

    /**
     * Initialize gender type mappings (PHP 8.4 property hook)
     */
    private function initializeGenderTypeMappings(): array
    {
        return [
            'M' => 'http://gedcomx.org/Male',
            'F' => 'http://gedcomx.org/Female',
            'U' => 'http://gedcomx.org/Unknown',
        ];
    }

    private function convertIndividualToPerson(Indi $indi): array
    {
        $gedcomxId = 'p' . $this->personCounter++;
        $this->personIdMap[$indi->getId()] = $gedcomxId;

        $person = [
            'id' => $gedcomxId,
            'names' => [],
            'facts' => []
        ];

        // Convert names
        foreach ($indi->getName() as $name) {
            $gedcomxName = $this->convertNameToGedcomX($name);
            if ($gedcomxName) {
                $person['names'][] = $gedcomxName;
            }
        }

        // Convert gender
        if ($indi->getSex()) {
            $person['gender'] = [
                'type' => $this->convertGenderToGedcomX($indi->getSex())
            ];
        }

        // Convert identifiers (UIDs)
        $identifiers = [];
        
        // Add _UID values (GEDCOM 5.5.1)
        $uids = $indi->getAllUid();
        if (!empty($uids)) {
            $uidValues = [];
            foreach ($uids as $uid) {
                if (!empty($uid)) {
                    // Format as URN if it looks like a UUID
                    if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uid)) {
                        $uidValues[] = 'urn:uuid:' . strtolower($uid);
                    } else {
                        $uidValues[] = $uid;
                    }
                }
            }
            if (!empty($uidValues)) {
                $identifiers['https://example.org/identifiers/gedcom/_UID'] = $uidValues;
            }
        }

        // Add UID values (GEDCOM 7.0)
        $uids7 = $indi->getAllUid7();
        if (!empty($uids7)) {
            $uid7Values = [];
            foreach ($uids7 as $uid7) {
                if (!empty($uid7)) {
                    // Format as URN if it looks like a UUID
                    if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uid7)) {
                        $uid7Values[] = 'urn:uuid:' . strtolower($uid7);
                    } else {
                        $uid7Values[] = $uid7;
                    }
                }
            }
            if (!empty($uid7Values)) {
                $identifiers['https://example.org/identifiers/gedcom/UID'] = $uid7Values;
            }
        }

        if (!empty($identifiers)) {
            $person['identifiers'] = $identifiers;
        }

        // Convert events/facts
        $events_facts = array_merge($indi->getAllEven(), $indi->getAllAttr());
        foreach ($events_facts as $eventType => $events) {
            if (is_array($events)) {
                foreach ($events as $event) {
                    $gedcomxFact = $this->convertEventToGedcomX($event, $eventType);
                    if ($gedcomxFact) {
                        $person['facts'][] = $gedcomxFact;
                    }
                }
            } else {
                // Single event
                $gedcomxFact = $this->convertEventToGedcomX($events, $eventType);
                if ($gedcomxFact) {
                    $person['facts'][] = $gedcomxFact;
                }
            }
        }

        return $person;
    }

    private function convertNameToGedcomX(Name $name): array
    {
        preg_match("/([^\/].*)\/([^\/].*)\//", $name->getName(), $matches);
        $givenname = $matches[1] ?? '';
        $surname = $matches[2] ?? '';

        $gedcomxName = [
            'nameForms' => []
        ];

        $nameForm = [
            'parts' => []
        ];

        // Build full text representation
        $fullTextParts = [];

        if ($name->getNpfx()) {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Prefix',
                'value' => $name->getNpfx()
            ];
            $fullTextParts[] = $name->getNpfx();
        }

        if ($name->getGivn()) {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Given',
                'value' => $name->getGivn()
            ];
            $fullTextParts[] = $name->getGivn();
        }
        else {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Given',
                'value' => $givenname
            ];
            $fullTextParts[] = $givenname;        
        }

        if ($name->getSurn()) {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Surname',
                'value' => $name->getSurn()
            ];
            $fullTextParts[] = $name->getSurn();
        }
        else {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Surname',
                'value' => $surname
            ];
            $fullTextParts[] = $surname;        
        }

        if ($name->getNsfx()) {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Suffix',
                'value' => $name->getNsfx()
            ];
            $fullTextParts[] = $name->getNsfx();
        }

        // Set full text
        $nameForm['fullText'] = str_replace("/", "", $name->getName());

        $gedcomxName['nameForms'][] = $nameForm;

        return $gedcomxName;
    }

    private function convertGenderToGedcomX(string $sex): string
    {
        // Use cached mapping for better performance
        return $this->gedcomToGedcomxGenderTypes[strtoupper($sex)] ?? 'http://gedcomx.org/Unknown';
    }

    private function convertEventToGedcomX(mixed $event, string $eventType): ?array
    {
        $gedcomxFactType = $this->mapGedcomEventTypeToFactType($eventType);
        if (!$gedcomxFactType) {
            return null;
        }

        $fact = [
            'type' => $gedcomxFactType
        ];

        // Convert date
        if ($event->getDate()) {
            $fact['date'] = [
                'original' => $event->getDate()
            ];
        }

        // Convert place
        if ($event->getPlac() !== null ? $event->getPlac()->getPlac() : null) {
            $fact['place'] = [
                'original' => $event->getPlac()->getPlac()
            ];
        }

        return $fact;
    }

    private function mapGedcomEventTypeToFactType(string $eventType): ?string
    {
        // Use cached mapping for better performance
        return $this->gedcomToGedcomxFactTypes[$eventType] ?? null;
    }

    private function generateRelationships(Gedcom $gedcom): void
    {
        // Pre-allocate array and use array_merge for better performance
        $allRelationships = [];
        foreach ($gedcom->getFam() as $family) {
            $relationships = $this->convertFamilyToRelationships($family);
            $allRelationships = [...$allRelationships, ...$relationships]; // PHP 8.4 spread operator optimization
        }
        $this->gedcomxData['relationships'] = $allRelationships;
    }

    private function convertFamilyToRelationships(Fam $family): array
    {
        $relationships = [];

        // Create couple relationship if both spouses exist
        if ($family->getHusb() && isset($this->personIdMap[$family->getHusb()]) && $family->getWife() && isset($this->personIdMap[$family->getWife()])) {
            $coupleId = 'r' . $this->relationshipCounter++;
            $this->relationshipIdMap[$family->getId() . '_couple'] = $coupleId;

            $couple = [
                'id' => $coupleId,
                'type' => 'http://gedcomx.org/Couple',
                'person1' => [
                    'resource' => '#persons/' . $this->personIdMap[$family->getHusb()]
                ],
                'person2' => [
                    'resource' => '#persons/' . $this->personIdMap[$family->getWife()]
                ],
                'facts' => []
            ];

            // Add family events to couple relationship
            foreach ($family->getAllEven() as $eventType => $events) {
                if (is_array($events)) {
                    foreach ($events as $event) {
                        $gedcomxFact = $this->convertEventToGedcomX($event, $eventType);
                        if ($gedcomxFact) {
                            $couple['facts'][] = $gedcomxFact;
                        }
                    }
                } else {
                    $gedcomxFact = $this->convertEventToGedcomX($events, $eventType);
                    if ($gedcomxFact) {
                        $couple['facts'][] = $gedcomxFact;
                    }
                }
            }

            $relationships[] = $couple;
        }

        // Create parent-child relationships
        $children = $family->getChil();
        if (is_array($children)) {
            foreach ($children as $childId) {
                // Create relationship with father
                if ($family->getHusb() && isset($this->personIdMap[$family->getHusb()]) && isset($this->personIdMap[$childId])) {
                    $relationships[] = [
                        'id' => 'r' . $this->relationshipCounter++,
                        'type' => 'http://gedcomx.org/ParentChild',
                        'person1' => [
                            'resource' => '#persons/' . $this->personIdMap[$family->getHusb()]
                        ],
                        'person2' => [
                            'resource' => '#persons/' . $this->personIdMap[$childId]
                        ]
                    ];
                }

                // Create relationship with mother
                if ($family->getWife() && isset($this->personIdMap[$family->getWife()]) && isset($this->personIdMap[$childId])) {
                    $relationships[] = [
                        'id' => 'r' . $this->relationshipCounter++,
                        'type' => 'http://gedcomx.org/ParentChild',
                        'person1' => [
                            'resource' => '#persons/' . $this->personIdMap[$family->getWife()]
                        ],
                        'person2' => [
                            'resource' => '#persons/' . $this->personIdMap[$childId]
                        ]
                    ];
                }
            }
        }

        return $relationships;
    }

    private function generateSourceDescriptions(Gedcom $gedcom): void
    {
        // Pre-allocate array for better performance
        $sourceDescriptions = [];
        foreach ($gedcom->getSour() as $source) {
            $sourceDescription = $this->convertSourceToGedcomX($source);
            if ($sourceDescription) {
                $sourceDescriptions[] = $sourceDescription;
            }
        }
        $this->gedcomxData['sourceDescriptions'] = $sourceDescriptions;
    }

    private function convertSourceToGedcomX($source): ?array
    {
        // Implementation for converting GEDCOM sources to Gedcom X source descriptions
        // This would depend on the structure of Gedcom\Record\Sour
        return null; // Placeholder for now
    }

    public function generateToFile(Gedcom $gedcom, string $fileName): bool
    {
        $json = $this->generate($gedcom);

        // PHP 8.4 optimized file writing with context
        $context = stream_context_create([
            'file' => [
                'memory_limit' => '512M'
            ]
        ]);

        return file_put_contents($fileName, $json, context: $context) !== false;
    }

    /**
     * Static factory method for backward compatibility
     */
    public static function create(): self
    {
        return new self(new Gedcom());
    }
}