<?php

namespace Gedcom\Tests\Utils;

use Gedcom\Utils\DataOptimizer;
use PHPUnit\Framework\TestCase;

class DataOptimizerTest extends TestCase
{
    public function testTrimString()
    {
        $this->assertEquals('', DataOptimizer::trimString(''));
        $this->assertEquals('', DataOptimizer::trimString('    '));
        $this->assertEquals('Hello, World', DataOptimizer::trimString('  Hello, World  '));
    }

    public function testNormalizeIdentifier()
    {
        $this->assertEquals('ID123', DataOptimizer::normalizeIdentifier('@ID123@'));
        $this->assertEquals('ID123', DataOptimizer::normalizeIdentifier('ID123'));
        $this->assertEquals('', DataOptimizer::normalizeIdentifier('@@'));
        $this->assertEquals('', DataOptimizer::normalizeIdentifier(''));
    }

    public function testConcatenateWithSeparator()
    {
        $this->assertEquals('', DataOptimizer::concatenateWithSeparator([]));
        $this->assertEquals('Hello', DataOptimizer::concatenateWithSeparator(['Hello']));
        $this->assertEquals('Hello, World', DataOptimizer::concatenateWithSeparator(['Hello', 'World'], ', '));
        $this->assertEquals('One Two Three', DataOptimizer::concatenateWithSeparator([' One', 'Two ', ' Three ']));
    }
}
