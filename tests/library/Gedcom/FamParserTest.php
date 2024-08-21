<?php

namespace GedcomTest;

use Gedcom\Parser;

/**
 * Class FamParserTest.
 */
class FamParserTest extends \PHPUnit\Framework\TestCase
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

    /**
     * Test a family event type with no type is parsed.
     */
    public function testFamilyEventWithNoTypeIsParsed()
    {
        $this->gedcom = $this->parser->parse(\TEST_DIR . '/stresstestfiles/family/family_event_no_type.ged');

        $fam = $this->gedcom->getFam('F1');

        $events = $fam['F1']->getAllEven();
        $this->assertCount(1, $events);

        $eventType = array_keys($events)[0];
        $event = $events[$eventType];

        $this->assertEquals('MARR', $eventType);
        $this->assertEquals('MARR', $event->getType());
        $this->assertEquals('2007-02-11', $event->getDate());
    }

    /**
     * Test a family event type with a type is parsed.
     */
    public function testFamilyEventWithTypeIsParsed()
    {
        $this->gedcom = $this->parser->parse(\TEST_DIR . '/stresstestfiles/family/family_event_with_type.ged');

        $fam = $this->gedcom->getFam('F1');

        $events = $fam['F1']->getAllEven();
        $this->assertCount(1, $events);

        $eventType = array_keys($events)[0];
        $event = $events[$eventType];

        $this->assertEquals('MARR', $eventType);
        $this->assertEquals('Civil marriage', $event->getType());
        $this->assertEquals('2007-02-11', $event->getDate());
    }
}
