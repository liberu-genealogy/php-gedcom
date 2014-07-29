<?php

namespace PhpGedcomTest;

use PhpGedcom\Parser;

/**
 * Class Issue00018Test
 * @package PhpGedcomTest
 */
class Issue00018Test extends \PHPUnit_Framework_TestCase
{
    /**
     * Test handling an empty note.
     */
    public function testEmptyNote()
    {
        $sample = realpath(__DIR__ . '/files/issue00018.ged');

        $parser = new Parser();
        $gedcom = $parser->parse($sample);

        $sour = current($gedcom->getSour());

        $this->assertCount(0, $sour->getNote());
        $this->assertCount(1, $parser->getErrors());
    }
}
