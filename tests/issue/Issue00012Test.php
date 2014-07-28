<?php

namespace PhpGedcomTest;

use PhpGedcom\Parser;

/**
 * Class Issue00012Test
 */
class Issue00012Test extends \PHPUnit_Framework_TestCase
{
    public function testBirthDate()
    {
        $sample = realpath(__DIR__ . '/files/issue00012.ged');

        $parser = new Parser();
        $gedcom = $parser->parse($sample);

        $indi = current($gedcom->getIndi());
        $birt = current($indi->getEven());

        $this->assertEquals('01.01.1970', $birt->getDate());
    }
}
