<?php

declare(strict_types=1);

namespace Gedcom\GedcomX;

use Gedcom\Gedcom;
use Gedcom\Record\Indi;
use Gedcom\Record\Fam;
use Gedcom\Record\Indi\Name;
use Gedcom\Record\Indi\Even;

/**
 * GedcomX Transformer - Provides data transformation methods between internal structures and Gedcom X
 * 
 * This class serves as a bridge between the internal GEDCOM data structures
 * and the Gedcom X format, providing bidirectional transformation capabilities.
 */
class Transformer
{
    private Parser $parser;
    private Generator $generator;

    public function __construct()
    {
        $this->parser = new Parser();
        $this->generator = new Generator();
    }

    /**
     * Transform a GEDCOM file to Gedcom X format
     */
    public function gedcomToGedcomX(string $gedcomFile, string $gedcomxFile): bool
    {
        try {
            // Parse GEDCOM file
            $gedcom = $this->parseGedcomFile($gedcomFile);
            if (!$gedcom) {
                return false;
            }

            // Generate Gedcom X
            return $this->generator->generateToFile($gedcom, $gedcomxFile);
        } catch (\Exception $e) {
            error_log("Error transforming GEDCOM to Gedcom X: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Transform a Gedcom X file to GEDCOM format
     */
    public function gedcomXToGedcom(string $gedcomxFile, string $gedcomFile): bool
    {
        try {
            // Parse Gedcom X file
            $gedcom = $this->parser->parse($gedcomxFile);
            if (!$gedcom) {
                return false;
            }

            // Generate GEDCOM
            return $this->generateGedcomFile($gedcom, $gedcomFile);
        } catch (\Exception $e) {
            error_log("Error transforming Gedcom X to GEDCOM: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Convert internal Gedcom object to Gedcom X JSON string
     */
    public function gedcomObjectToGedcomXJson(Gedcom $gedcom): string
    {
        return $this->generator->generate($gedcom);
    }

    /**
     * Convert Gedcom X JSON string to internal Gedcom object
     */
    public function gedcomXJsonToGedcomObject(string $json): ?Gedcom
    {
        try {
            $tempFile = tempnam(sys_get_temp_dir(), 'gedcomx_');
            file_put_contents($tempFile, $json);

            $gedcom = $this->parser->parse($tempFile);

            unlink($tempFile);

            return $gedcom;
        } catch (\Exception $e) {
            error_log("Error converting Gedcom X JSON to Gedcom object: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Transform individual person data between formats
     */
    public function transformPersonData(array $gedcomxPerson): array
    {
        $transformedData = [
            'id' => $gedcomxPerson['id'] ?? '',
            'names' => [],
            'gender' => null,
            'facts' => []
        ];

        // Transform names
        if (isset($gedcomxPerson['names'])) {
            foreach ($gedcomxPerson['names'] as $name) {
                $transformedData['names'][] = $this->transformNameData($name);
            }
        }

        // Transform gender
        if (isset($gedcomxPerson['gender']['type'])) {
            $transformedData['gender'] = $this->transformGenderData($gedcomxPerson['gender']['type']);
        }

        // Transform facts
        if (isset($gedcomxPerson['facts'])) {
            foreach ($gedcomxPerson['facts'] as $fact) {
                $transformedData['facts'][] = $this->transformFactData($fact);
            }
        }

        return $transformedData;
    }

    /**
     * Transform name data between formats
     */
    public function transformNameData(array $gedcomxName): array
    {
        $transformedName = [
            'given' => '',
            'surname' => '',
            'prefix' => '',
            'suffix' => '',
            'fullText' => ''
        ];

        if (isset($gedcomxName['nameForms'])) {
            foreach ($gedcomxName['nameForms'] as $nameForm) {
                if (isset($nameForm['fullText'])) {
                    $transformedName['fullText'] = $nameForm['fullText'];
                }

                if (isset($nameForm['parts'])) {
                    foreach ($nameForm['parts'] as $part) {
                        switch ($part['type'] ?? '') {
                            case 'http://gedcomx.org/Given':
                                $transformedName['given'] = $part['value'] ?? '';
                                break;
                            case 'http://gedcomx.org/Surname':
                                $transformedName['surname'] = $part['value'] ?? '';
                                break;
                            case 'http://gedcomx.org/Prefix':
                                $transformedName['prefix'] = $part['value'] ?? '';
                                break;
                            case 'http://gedcomx.org/Suffix':
                                $transformedName['suffix'] = $part['value'] ?? '';
                                break;
                        }
                    }
                }
            }
        }

        return $transformedName;
    }

    /**
     * Transform gender data between formats
     */
    public function transformGenderData(string $gedcomxGender): string
    {
        switch ($gedcomxGender) {
            case 'http://gedcomx.org/Male':
                return 'M';
            case 'http://gedcomx.org/Female':
                return 'F';
            default:
                return 'U';
        }
    }

    /**
     * Transform fact/event data between formats
     */
    public function transformFactData(array $gedcomxFact): array
    {
        $transformedFact = [
            'type' => '',
            'date' => '',
            'place' => '',
            'description' => ''
        ];

        // Transform type
        if (isset($gedcomxFact['type'])) {
            $transformedFact['type'] = $this->transformFactType($gedcomxFact['type']);
        }

        // Transform date
        if (isset($gedcomxFact['date']['original'])) {
            $transformedFact['date'] = $gedcomxFact['date']['original'];
        }

        // Transform place
        if (isset($gedcomxFact['place']['original'])) {
            $transformedFact['place'] = $gedcomxFact['place']['original'];
        }

        // Transform description
        if (isset($gedcomxFact['value'])) {
            $transformedFact['description'] = $gedcomxFact['value'];
        }

        return $transformedFact;
    }

    /**
     * Transform fact type from Gedcom X to GEDCOM
     */
    public function transformFactType(string $gedcomxFactType): string
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
            'http://gedcomx.org/Education' => 'EDUC',
            'http://gedcomx.org/Emigration' => 'EMIG',
            'http://gedcomx.org/Immigration' => 'IMMI',
            'http://gedcomx.org/Naturalization' => 'NATU',
            'http://gedcomx.org/Census' => 'CENS',
        ];

        return $mapping[$gedcomxFactType] ?? 'EVEN';
    }

    /**
     * Transform relationship data between formats
     */
    public function transformRelationshipData(array $gedcomxRelationship): array
    {
        $transformedRelationship = [
            'id' => $gedcomxRelationship['id'] ?? '',
            'type' => '',
            'person1' => '',
            'person2' => '',
            'facts' => []
        ];

        // Transform type
        if (isset($gedcomxRelationship['type'])) {
            $transformedRelationship['type'] = $this->transformRelationshipType($gedcomxRelationship['type']);
        }

        // Transform person references
        if (isset($gedcomxRelationship['person1']['resource'])) {
            $transformedRelationship['person1'] = $this->extractPersonIdFromResource($gedcomxRelationship['person1']['resource']);
        }

        if (isset($gedcomxRelationship['person2']['resource'])) {
            $transformedRelationship['person2'] = $this->extractPersonIdFromResource($gedcomxRelationship['person2']['resource']);
        }

        // Transform facts
        if (isset($gedcomxRelationship['facts'])) {
            foreach ($gedcomxRelationship['facts'] as $fact) {
                $transformedRelationship['facts'][] = $this->transformFactData($fact);
            }
        }

        return $transformedRelationship;
    }

    /**
     * Transform relationship type from Gedcom X to GEDCOM
     */
    public function transformRelationshipType(string $gedcomxRelType): string
    {
        switch ($gedcomxRelType) {
            case 'http://gedcomx.org/Couple':
                return 'COUPLE';
            case 'http://gedcomx.org/ParentChild':
                return 'PARENT_CHILD';
            default:
                return 'UNKNOWN';
        }
    }

    /**
     * Extract person ID from Gedcom X resource URI
     */
    private function extractPersonIdFromResource(string $resource): string
    {
        if (preg_match('/persons\/(.+)$/', $resource, $matches)) {
            return $matches[1];
        }
        return '';
    }

    /**
     * Parse a GEDCOM file using the existing parser
     */
    private function parseGedcomFile(string $fileName): ?Gedcom
    {
        $parser = new \Gedcom\Parser();
        return $parser->parse($fileName);
    }

    /**
     * Generate a GEDCOM file using the existing writer
     */
    private function generateGedcomFile(Gedcom $gedcom, string $fileName): bool
    {
        $gedcomContent = \Gedcom\Writer::convert($gedcom);
        return file_put_contents($fileName, $gedcomContent) !== false;
    }

    /**
     * Validate Gedcom X data structure
     */
    public function validateGedcomXData(array $data): array
    {
        $errors = [];

        // Check required top-level elements
        if (!isset($data['persons']) && !isset($data['relationships'])) {
            $errors[] = "Gedcom X data must contain either 'persons' or 'relationships' array";
        }

        // Validate persons
        if (isset($data['persons'])) {
            foreach ($data['persons'] as $index => $person) {
                $personErrors = $this->validatePersonData($person, $index);
                $errors = array_merge($errors, $personErrors);
            }
        }

        // Validate relationships
        if (isset($data['relationships'])) {
            foreach ($data['relationships'] as $index => $relationship) {
                $relationshipErrors = $this->validateRelationshipData($relationship, $index);
                $errors = array_merge($errors, $relationshipErrors);
            }
        }

        return $errors;
    }

    /**
     * Validate person data structure
     */
    private function validatePersonData(array $person, int $index): array
    {
        $errors = [];

        if (!isset($person['id'])) {
            $errors[] = "Person at index $index is missing required 'id' field";
        }

        return $errors;
    }

    /**
     * Validate relationship data structure
     */
    private function validateRelationshipData(array $relationship, int $index): array
    {
        $errors = [];

        if (!isset($relationship['id'])) {
            $errors[] = "Relationship at index $index is missing required 'id' field";
        }

        if (!isset($relationship['type'])) {
            $errors[] = "Relationship at index $index is missing required 'type' field";
        }

        return $errors;
    }
}