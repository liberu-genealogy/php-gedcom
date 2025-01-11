

<?php

namespace GedcomTest;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getTestFilePath(string $filename): string 
    {
        return \TEST_DIR . '/stresstestfiles/' . $filename;
    }
}