<?php

use Gedcom\FormatInformation;
use PHPUnit\Framework\TestCase;

class FormatInformationTest extends TestCase
{
    public function testAddFormatInformationReturnsXmlFormatString()
    {
        $format = 'XML';
        $expected = "<format>XML</format>\n";
        
        $result = FormatInformation::addFormatInformation($format);
        
        $this->assertEquals($expected, $result);
    }
    
    public function testAddFormatInformationReturnsJsonFormatString()
    {
        $format = 'JSON';
        $expected = "{ \"format\": \"JSON\" }\n";
        
        $result = FormatInformation::addFormatInformation($format);
        
        $this->assertEquals($expected, $result);
    }
    
    public function testAddFormatInformationReturnsDefaultFormatString()
    {
        $format = 'CSV';
        $expected = "Format: CSV\n";
        
        $result = FormatInformation::addFormatInformation($format);
        
        $this->assertEquals($expected, $result);
    }
}
