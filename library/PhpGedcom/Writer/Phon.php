<?php
/**
 * php-gedcom
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2011, Kristopher Wilson
 * @package         php-gedcom 
 * @license         http://php-gedcom.kristopherwilson.com/license
 * @link            http://php-gedcom.kristopherwilson.com
 * @version         SVN: $Id$
 */

namespace PhpGedcom\Writer;

/**
 *
 */
class Phon
{
    /**
     * @param string $phon
     * @param string $format
     * @param int $level
     * @return string
     */
    public static function convert($phon, $format = self::GEDCOM55, $level = 1)
    {
        $output = "{$level} PHON " . $phon . "\n";
        
        return $output;
    }
}
