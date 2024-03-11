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
 * PHPUnit tests for the Gedcom Parser in ParserTest.php.
 *
 * This file is dedicated to testing the functionality of the Gedcom parser,
 * specifically focusing on its ability to parse Gedcom files with varying contents
 * of names arrays, ensuring correct handling and output generation.
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
        $gedcom = $parser->parse('non_empty_names.ged');
        $output = '';

        ob_start();
        foreach ($gedcom->getIndi() as $individual) {
            $names = $individual->getName();
/**
 * Test parsing with an empty names array.
 *
 * Ensures that the parser correctly handles Gedcom files with no names,
 * resulting in an empty output.
 */
/**
 * PHPUnit tests for Gedcom Parser.
 *
 * This file tests the Gedcom Parser's ability to parse Gedcom files,
 * focusing on handling empty and non-empty names arrays.
 */
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
