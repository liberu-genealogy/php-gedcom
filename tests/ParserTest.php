<?php

use PHPUnit\Framework\TestCase;
use Gedcom\Parser;

class ParserTest extends TestCase
{
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
