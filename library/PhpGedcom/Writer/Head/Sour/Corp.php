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

namespace PhpGedcom\Writer\Head\Sour;

/**
 *
 */
class Corp
{
    /**
     * @param \PhpGedcom\Record\Head\Sour\Corp $corp
     * @param string $format
     * @param int $level
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Head\Sour\Corp &$corp, $format = self::GEDCOM55, $level = 2)
    {
        $output = "{$level} CORP " . $corp->corp . "\n" .
            \PhpGedcom\Writer\Addr::convert($corp->addr, $format, $level + 1);
        
        foreach ($corp->phon as $phon) {
            $output .= \PhpGedcom\Writer\Phon::convert($phon, $format, $level + 1);
        }

        return $output;
    }
}
