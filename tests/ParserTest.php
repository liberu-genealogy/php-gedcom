/**
 * Tests for the Gedcom Parser.
 *
 * This file contains the test cases for testing the Gedcom file parsing functionality provided by the Parser class.
 */
<?php

use PHPUnit\Framework\TestCase;
use Gedcom\Parser;

class ParserTest extends TestCase
{
    /**
     * Test parsing functionality with an empty names array.
     *
     * This test ensures that the parser correctly handles Gedcom files with individuals that have no names defined.
     * It verifies that no output is generated for such individuals.
     */
    public function testParseWithEmptyNamesArray()
    {
        $parser = new Parser();
        $gedcom = $parser->parse('empty_names.ged');
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

        $this->assertEmpty($output);
    }

    public function testParseWithNonEmptyNamesArray()
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
    /**
     * Test parsing functionality with a non-empty names array.
     *
     * This test checks the parser's ability to correctly handle Gedcom files with individuals that have names defined.
     * It verifies that the output correctly lists the individuals' IDs along with their surname and given names.
     */
