<?php

declare(strict_types=1);

namespace Gedcom\GedcomX;

use Gedcom\Gedcom;
use Gedcom\Record\Indi;
use Gedcom\Record\Fam;
use Gedcom\Record\Indi\Name;
use Gedcom\Record\Indi\Even;

/**
 * GedcomX Generator - Converts internal GEDCOM data structures to Gedcom X JSON format
 * 
 * Generates Gedcom X compliant JSON from parsed GEDCOM data
 */
class Generator
{
    private Gedcom $gedcom;
    private array $gedcomxData;
    private array $personIdMap = [];
    private array $relationshipIdMap = [];
    private int $personCounter = 1;
    private int $relationshipCounter = 1;

    public function generate(Gedcom $gedcom): string
    {
        $this->gedcom = $gedcom;
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

        $this->generatePersons();
        $this->generateRelationships();
        $this->generateSourceDescriptions();

        return json_encode($this->gedcomxData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function generatePersons(): void
    {
        foreach ($this->gedcom->getIndi() as $indi) {
            $person = $this->convertIndividualToPerson($indi);
            if ($person) {
                $this->gedcomxData['persons'][] = $person;
            }
        }
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

        // Convert events/facts
        foreach ($indi->getEven() as $eventType => $events) {
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

        if ($name->getSurn()) {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Surname',
                'value' => $name->getSurn()
            ];
            $fullTextParts[] = '/' . $name->getSurn() . '/';
        }

        if ($name->getNsfx()) {
            $nameForm['parts'][] = [
                'type' => 'http://gedcomx.org/Suffix',
                'value' => $name->getNsfx()
            ];
            $fullTextParts[] = $name->getNsfx();
        }

        // Set full text
        $nameForm['fullText'] = implode(' ', $fullTextParts);

        $gedcomxName['nameForms'][] = $nameForm;

        return $gedcomxName;
    }

    private function convertGenderToGedcomX(string $sex): string
    {
        switch (strtoupper($sex)) {
            case 'M':
                return 'http://gedcomx.org/Male';
            case 'F':
                return 'http://gedcomx.org/Female';
            default:
                return 'http://gedcomx.org/Unknown';
        }
    }

    private function convertEventToGedcomX(Even $event, string $eventType): ?array
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
        if ($event->getPlac()) {
            $fact['place'] = [
                'original' => $event->getPlac()
            ];
        }

        return $fact;
    }

    private function mapGedcomEventTypeToFactType(string $eventType): ?string
    {
        $mapping = [
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

        return $mapping[$eventType] ?? null;
    }

    private function generateRelationships(): void
    {
        foreach ($this->gedcom->getFam() as $family) {
            $relationships = $this->convertFamilyToRelationships($family);
            foreach ($relationships as $relationship) {
                $this->gedcomxData['relationships'][] = $relationship;
            }
        }
    }

    private function convertFamilyToRelationships(Fam $family): array
    {
        $relationships = [];

        // Create couple relationship if both spouses exist
        if ($family->getHusb() && $family->getWife()) {
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
            foreach ($family->getEven() as $eventType => $events) {
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

    private function generateSourceDescriptions(): void
    {
        foreach ($this->gedcom->getSour() as $source) {
            $sourceDescription = $this->convertSourceToGedcomX($source);
            if ($sourceDescription) {
                $this->gedcomxData['sourceDescriptions'][] = $sourceDescription;
            }
        }
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
        return file_put_contents($fileName, $json) !== false;
    }
}