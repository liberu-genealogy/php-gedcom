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
        // Add assertions for the basic functionality of the convert method

    }

    public function testConvertWithMultipleFamProperties()
    {

        $gedcom = $this->createMockGedcom(['Fam' => [$fam1, $fam2]]);
        $output = Writer::convert($gedcom);
        $this->assertConvertOutput($output, 'expected_output');
        // Add assertions for the basic functionality of the convert method

    }

    public function testConvertWithCustomFormat()
    {
        $gedcom = $this->createMockGedcom();
        $output = Writer::convert($gedcom, 'custom_format');
        $this->assertConvertOutput($output, 'expected_output'); {
            $sour1 = $this->createMock(Sour::class);
            $sour2 = $this->createMock(Sour::class);
            $gedcom = $this->createMockGedcom(['Sour' => [$sour1, $sour2]]);
            $output = Writer::convert($gedcom);
            $this->assertConvertOutput($output, 'expected_output');
        }
    }

    public function testConvertWithSubnProperty()
    {
        $subn = $this->createMock(Subn::class);
        $gedcom = $this->createMockGedcom(['Subn' => $subn]);
        $output = Writer::convert($gedcom);
        $this->assertConvertOutput($output, 'expected_output');

        // Assert the correctness of the output
        $this->assertEquals('expected_output', $output);
        // Add assertions for the basic functionality of the convert method

    }
    // public function testConvertWithCustomFormat()
    // {
    //     // Create a mock Gedcom object
    //     $gedcom = $this->createMock(Gedcom::class);

    //     // Set up expectations for the mock Gedcom object


    //     // Call the convert method with a custom format
    //     $output = Writer::convert($gedcom, 'custom_format');

    //     // Assert the correctness of the output
    //     $this->assertEquals('expected_output', $output);
    //     // Add assertions for the basic functionality of the convert method

    // }

    // public function testConvertWithMultipleNoteProperties()
    // {
    //     // Create a mock Gedcom object with a Head property
    //     $gedcom = $this->createMock(Gedcom::class);
    //     $head = $this->createMock(Head::class);
    //     $gedcom->expects($this->once())
    //         ->method('getHead')
    //         ->willReturn($head);

    //     // Set up expectations for the mock Gedcom object with multiple Repo properties with multiple Repo properties


    //     // Call the convert method
    //     $output = Writer::convertHeadHead($gedcom);

    //     // Assert the correctness of the output
    //     $this->assertEquals('expected_output', $output);
    //     // Add assertions for the basic functionality of the convert method

    // }

    // public function testConvertWithMultipleNoteProperties()
    // {
    //     // Create a mock Gedcom object with multiple Note properties
    //     $gedcom = $this->createMock(Gedcom::class);
    //     $head = $this->createMock(Head::class);
    //     $gedcom->expects($this->once())
    //         ->method('getHead')
    //         ->willReturn([$note1, $note2]);

    //     // Set up expectations for the mock Gedcom object


    //     // Call the convert method
    //     $output = Writer::convert($gedcom);

    //     // Assert the correctness of the output for multiple Note properties
    //     $this->assertEquals('expected_output', $output);
    //     // Add assertions for the basic functionality of the convert method

    // }

    public function testConvertWithHeadProperty()
    {
        // Create a mock Gedcom object with a Head property
        $gedcom = $this->createMock(Gedcom::class);
        $head = $this->createMock(Head::class);
        $gedcom->expects($this->once())
            ->method('getHead')
            ->willReturn($head);

        // Set up expectations for the mock Gedcom object


        // Call the convert method
        $output = Writer::convert($gedcom);

        // Assert the correctness of the output
        $this->assertEquals('expected_output', $output);
        // Add assertions for the basic functionality of the convert method

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

        // Add more test cases as needed to cover different scenarios and edge cases
    }
}
