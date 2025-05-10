<?php

use Gedcom\Gedcom;
use Gedcom\Writer;
use Gedcom\Writer\Head;
use Gedcom\Writer\Sour;
use Gedcom\Writer\Subn;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    private function createMockGedcom($properties = [])
    {
        $gedcom = $this->createMock(Gedcom::class);
        foreach ($properties as $property => $mock) {
            $gedcom->expects($this->once())
                ->method('get' . ucfirst($property))
                ->willReturn($mock);
        }
        return $gedcom;
    }

    private function assertConvertOutput($output, $expected)
    {
        $this->assertEquals($expected, $output);
    }

    public function testConvert()
    {
        $gedcom = $this->createMockGedcom();
        $output = Writer::convert($gedcom);
        $this->assertConvertOutput($output, 'expected_output');
    }

    public function testConvertWithMultipleFamProperties()
    {
        $fam1 = $this->createMock(\Gedcom\Record\Fam::class);
        $fam2 = $this->createMock(\Gedcom\Record\Fam::class);
        $gedcom = $this->createMockGedcom(['Fam' => [$fam1, $fam2]]);
        $output = Writer::convert($gedcom);
        $this->assertConvertOutput($output, 'expected_output');
    }

    public function testConvertWithSourProperties()
    {
        $sour1 = $this->createMock(Sour::class);
        $sour2 = $this->createMock(Sour::class);
        $gedcom = $this->createMockGedcom(['Sour' => [$sour1, $sour2]]);
        $output = Writer::convert($gedcom);
        $this->assertConvertOutput($output, 'expected_output');
    }

    public function testConvertWithSubnProperty()
    {
        $subn = $this->createMock(Subn::class);
        $gedcom = $this->createMockGedcom(['Subn' => $subn]);
        $output = Writer::convert($gedcom);
        $this->assertConvertOutput($output, 'expected_output');
    }

    public function testConvertWithHeadProperty()
    {
        $gedcom = $this->createMock(Gedcom::class);
        $head = $this->createMock(Head::class);
        $gedcom->expects($this->once())
            ->method('getHead')
            ->willReturn($head);

        $output = Writer::convert($gedcom);
        $this->assertConvertOutput($output, 'expected_output');
    }

    public function testSourConvert()
    {
        // Test case with title and multiple notes
        $sour1 = $this->createMock(Sour::class);
        $sour1->method('getTitl')->willReturn('Title 1');
        $sour1->method('getNote')->willReturn(['Note 1', 'Note 2']);
        $output1 = Sour::convert($sour1, 1);
        $this->assertEquals("1 Title 1 SOUR\n2 Note 1\n2 Note 2", $output1, 'Sour with title and multiple notes should format correctly.');

        // Test case without title and with one note
        $sour2 = $this->createMock(Sour::class);
        $sour2->method('getNote')->willReturn(['Note 1']);
        $output2 = Sour::convert($sour2, 1);
        $this->assertEquals("1 Note 1", $output2, 'Sour without title and with one note should format correctly.');

        // Test case with unusual values
        $sour3 = $this->createMock(Sour::class);
        $sour3->method('getTitl')->willReturn('');
        $sour3->method('getNote')->willReturn([]);
        $output3 = Sour::convert($sour3, 1);
        $this->assertEquals("", $output3, 'Sour with unusual values should return an empty string.');
    }
}
