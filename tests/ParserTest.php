<?php

use PHPUnit\Framework\TestCase;
use Gedcom\Parser;

class ParserTest extends TestCase
{
    public function testParseWithEmptyNamesArray()
    {
        $output = $this->generateOutputFromParsedGedcom('empty_names.ged');

        $this->assertEmpty($output);
    }

    public function testParseWithNonEmptyNamesArray()
/**
 * Tests for the Gedcom Parser.
 *
 * This file contains PHPUnit tests for testing the functionality of the Gedcom parser,
 * including parsing empty and non-empty names arrays from Gedcom files.
 */
    /**
     * Test parsing with an empty names array.
     *
     * This test ensures that the parser correctly handles Gedcom files with empty names arrays,
     * resulting in an empty output.
     */
    {
        $parser = new Parser();
        $gedcom = $parser->parse('non_empty_names.ged');
        $output = '';

        ob_start();
        foreach ($gedcom->getIndi() as $individual) {
            $names = $individual->getName();
            if (!empty($names)) {
                $name = reset($names);
                $output .= $individual->getId() . ': ' . $name->getSurn() . ', ' . $name->getGivn() . PHP_EOL;
            }
        }
        ob_end_clean();

        $this->assertNotEmpty($output);
        $this->assertStringContainsString('I1: Doe, John', $output);
    }
}
    private function generateOutputFromParsedGedcom($gedcomFileName)
    {
        $parser = new Parser();
        $gedcom = $parser->parse($gedcomFileName);
        $output = '';

        ob_start();
        foreach ($gedcom->getIndi() as $individual) {
            $names = $individual->getName();
            if (!empty($names)) {
                $name = reset($names);
                $output .= $individual->getId() . ': ' . $name->getSurn() . ', ' . $name->getGivn() . PHP_EOL;
            }
        }
        ob_end_clean();

        return $output;
    }
    /**
     * Test parsing with a non-empty names array.
     *
     * This test verifies that the parser correctly processes Gedcom files with non-empty names arrays,
     * producing a non-empty output that includes individual IDs and names.
     */
    /**
     * Generates output from parsed Gedcom file.
     *
     * This helper function parses a given Gedcom file and generates output containing
     * individual IDs and names, if available.
     *
     * @param string $gedcomFileName The name of the Gedcom file to parse.
     * @return string The generated output.
     */
