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
        $event = $events[$eventType][0];

        $this->assertEquals('MARR', $eventType);
        $this->assertEquals('MARR', $event->getType());
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
        $event = $events[$eventType][0];

        $this->assertEquals('MARR', $eventType);
        $this->assertEquals('Civil marriage', $event->getType());
    }

    /**
     * Test multiple events of the same type are kept.
     */
    public function testMultipleEventsOfTheSameTypeAreKept()
    {
        $this->gedcom = $this->parser->parse(\TEST_DIR . '/stresstestfiles/family/family_multiple_events.ged');
        $fam = $this->gedcom->getFam('F1');

        $events = $fam['F1']->getAllEven();
        $this->assertCount(2, $events['MARR']);

        $eventTypes = array_keys($events);
        $this->assertEquals('MARR', $eventTypes[0]);

        $event1 = $events['MARR'][0];
        $event2 = $events['MARR'][1];

        $this->assertEquals('First civil marriage', $event1->getType());
        $this->assertEquals('Second civil marriage', $event2->getType());
    }

    /**
     * Test get even returns a single event.
     */
    public function testGetEvenReturnsASingleEvent()
    {
        $this->gedcom = $this->parser->parse(\TEST_DIR . '/stresstestfiles/family/family_event_with_type.ged');

        $fam = $this->gedcom->getFam('F1');

        $event = $fam['F1']->getEven('MARR');
        $this->assertInstanceOf(\Gedcom\Record\Fam\Even::class, $event);

        $this->assertEquals('Civil marriage', $event->getType());
    }

    /**
     * Test get even returns multiple events.
     */
    public function testGetEvenReturnsMultipleEvents()
    {
        $this->gedcom = $this->parser->parse(\TEST_DIR . '/stresstestfiles/family/family_multiple_events.ged');

        $fam = $this->gedcom->getFam('F1');

        $events = $fam['F1']->getEven('MARR');
        $this->assertCount(2, $events);

        $event1 = $events[0];
        $event2 = $events[1];
        $this->assertInstanceOf(\Gedcom\Record\Fam\Even::class, $event1);
        $this->assertInstanceOf(\Gedcom\Record\Fam\Even::class, $event2);

        $this->assertEquals('First civil marriage', $event1->getType());
        $this->assertEquals('Second civil marriage', $event2->getType());
    }

    /**
     * Test a family event with a custom tag is parsed.
     */
    public function testFamilyEventWithExtensionTagIsParsed()
    {
        $this->gedcom = $this->parser->parse(\TEST_DIR . '/stresstestfiles/family/family_with_extension_tag.ged');

        $fam = $this->gedcom->getFam('F1');

        $extensionTag = $fam['F1']->getExtensionTag('NAME');
        $this->assertEquals('A random family', $extensionTag);
    }
}
