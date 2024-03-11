<?php

use PHPUnit\Framework\TestCase;
use Gedcom\Writer;
use Gedcom\FormatInformation;
use Gedcom\Gedcom;

class WriterTest extends TestCase
{
    public function testConvertWithDifferentFormats()
    {
        $gedcomMock = $this->createMock(Gedcom::class);
        $formats = ['GEDCOM5.5', 'GEDCOM7', 'UNKNOWN_FORMAT'];
        $expectedResults = [
            'GEDCOM5.5' => 'Format information for GEDCOM5.5',
            'GEDCOM7' => 'Format information for GEDCOM7',
            'UNKNOWN_FORMAT' => 'Unknown format'
        ];

        foreach ($formats as $format) {
            $result = Writer::convert($gedcomMock, $format);
            $this->assertStringContainsString($expectedResults[$format], $result);
        }
    }

    public function testConvertWithUnexpectedFormatInformationResults()
    {
        $gedcomMock = $this->createMock(Gedcom::class);
        $unexpectedResults = ['', null];
        $expectedOutput = 'No format provided';

        foreach ($unexpectedResults as $unexpectedResult) {
            FormatInformation::method('addFormatInformation')->willReturn($unexpectedResult);
            $result = Writer::convert($gedcomMock, 'any_format');
            $this->assertStringContainsString($expectedOutput, $result);
        }
    }
}
