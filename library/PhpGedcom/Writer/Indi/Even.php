<?php
/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 5.3+.
 *
 * @author          Xiang Ming <wenqiangliu344@gmail.com>
 * @copyright       Copyright (c) 2010-2013, Xiang Ming
 * @license         MIT
 *
 * @link            http://github.com/mrkrstphr/php-gedcom
 */

namespace PhpGedcom\Writer\Indi;

class Even
{
    /**
     * @param \PhpGedcom\Record\Indi\Even $even
     * @param int                         $level
     *
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Indi\Even &$even, $level = 0)
    {
        $output = '';

        // $_attr;
        $attr = $even->getAttr();
        if (!empty($attr)) {
            $output .= $level.' EVEN '.$attr."\n";
        } else {
            $output = $level." EVEN\n";
        }
        $level++;

        // $type;
        $type = $even->getType();
        if (!empty($type)) {
            $output .= $level.' TYPE '.$type."\n";
        }

        // $date;
        $date = $even->getDate();
        if (!empty($date)) {
            $output .= $level.' DATE '.$date."\n";
        }

        // Plac
        $plac = $even->getPlac();
        if (!empty($plac)) {
            $_convert = \PhpGedcom\Writer\Indi\Even\Plac::convert($plac, $level);
            $output .= $_convert;
        }

        // $caus;
        $caus = $even->getCaus();
        if (!empty($caus)) {
            $output .= $level.' CAUS '.$caus."\n";
        }

        // $age;
        $age = $even->getAge();
        if (!empty($age)) {
            $output .= $level.' AGE '.$age."\n";
        }

        // $addr
        $addr = $even->getAddr();
        if (!empty($addr)) {
            $_convert = \PhpGedcom\Writer\Addr::convert($addr, $level);
            $output .= $_convert;
        }

        // $phon = array()
        $phon = $even->getPhon();
        if (!empty($phon) && count($phon) > 0) {
            foreach ($phon as $item) {
                $_convert = \PhpGedcom\Writer\Phon::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $agnc
        $agnc = $even->getAgnc();
        if (!empty($agnc)) {
            $output .= $level.' AGNC '.$agnc."\n";
        }

        // $ref = array();
        // This is not in parser

        // $obje = array();
        $obje = $even->getObje();
        if (!empty($obje) && count($obje) > 0) {
            foreach ($obje as $item) {
                $_convert = \PhpGedcom\Writer\ObjeRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $sour = array();
        $sour = $even->getSour();
        if (!empty($sour) && count($sour) > 0) {
            foreach ($sour as $item) {
                $_convert = \PhpGedcom\Writer\SourRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // $note = array();
        $note = $even->getSour();
        if (!empty($note) && count($note) > 0) {
            foreach ($note as $item) {
                $_convert = \PhpGedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }
        // Record\Chan
        $chan = $even->getChan();
        if (!empty($chan)) {
            $_convert = \PhpGedcom\Writer\Chan::convert($item, $level);
            $output .= $_convert;
        }

        return $output;
    }
}
