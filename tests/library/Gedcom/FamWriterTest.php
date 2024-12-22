<?php

namespace GedcomTest;

use Gedcom\Parser;
use Gedcom\Writer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class FamWriterTest.
 */
class FamWriterTest extends TestCase
{
    /**
     * @var \Gedcom\Parser
     */
    protected $parser = null;

    /**
     * @var \Gedcom\Gedcom
     */
    protected $gedcom = null;

    public function setUp(): void
    {
        $this->parser = new Parser();
    }

    #[DataProvider('families')]
    public function testFamilyEventIsConvertedToTheOriginal($gedcomFile)
    {
        $this->gedcom = $this->parser->parse($gedcomFile);

        $originalGedcom = file_get_contents($gedcomFile);
        $convertedGedcom = Writer::convert($this->gedcom);

        $this->assertEquals($originalGedcom, $convertedGedcom);
    }

    public static function families()
    {
        return [
            [\TEST_DIR . '/stresstestfiles/family/family_event_no_type.ged'],
            [\TEST_DIR . '/stresstestfiles/family/family_event_with_type.ged'],
            [\TEST_DIR . '/stresstestfiles/family/family_multiple_events.ged'],
            [\TEST_DIR . '/stresstestfiles/family/family_with_extension_tag.ged'],
        ];
    }
}
