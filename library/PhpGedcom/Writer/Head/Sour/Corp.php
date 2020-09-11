<?php
/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Kristopher Wilson <kristopherwilson@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Kristopher Wilson
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Writer\Head\Sour;

class Corp
{
    /**
     * @param \PhpGedcom\Record\Head\Sour\Corp $corp
     * @param string                           $format
     * @param int                              $level
     *
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Head\Sour\Corp &$corp, $level)
    {
        $output = '';
        $_corp = $corp->getCorp();
        if ($_corp) {
            $output .= $level.' CORP '.$_corp."\n";
        } else {
            return $output;
        }

        // level up
        $level++;

        // ADDR
        $addr = $corp->getAddr();
        if ($addr) {
            $_convert = \PhpGedcom\Writer\Addr::convert($addr, $level);
            $output .= $_convert;
        }

        // phon
        $phon = $corp->getPhon();
        if ($phon && count($phon) > 0) {
            foreach ($phon as $item) {
                if ($item) {
                    $_convert = \PhpGedcom\Writer\Phon::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        return $output;
    }
}
