<?php

namespace GedcomTest;

use Gedcom\Parser;

/**
 * Class Issue00017Test.
 */
class Issue00017Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test an empty 1 FAMC under an INDI.
     */
    public function testEmptyFamc()
    {
        $sample = realpath(__DIR__.'/files/issue00017.ged');

        $parser = new Parser();
        $gedcom = $parser->parse($sample);

        $indi = current($gedcom->getIndi());

        $this->assertCount(0, $indi->getFamc());
        $this->assertCount(1, $parser->getErrors());
    }
}
