<?php

use PHPUnit\Framework\TestCase;
use Gedcom\FormatInformation;

class FormatInformationTest extends TestCase
{
    public function testAddFormatInformationReturnsExpectedString()
    {
        $format = 'GEDCOM5.5';
        $expected = 'Format information for GEDCOM5.5';
        $result = FormatInformation::addFormatInformation($format);
        $this->assertEquals($expected, $result);
    }

    public function testAddFormatInformationWithUnknownFormat()
    {
        $format = 'UNKNOWN_FORMAT';
        $expected = 'Unknown format';
        $result = FormatInformation::addFormatInformation($format);
        $this->assertEquals($expected, $result);
    }

    public function testAddFormatInformationWithEmptyString()
    {
        $format = '';
        $expected = 'No format provided';
        $result = FormatInformation::addFormatInformation($format);
        $this->assertEquals($expected, $result);
    }
}
