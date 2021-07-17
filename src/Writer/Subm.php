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

namespace Gedcom\Writer;

class Subm
{
    /**
     * @param \Record\Subm $note
     * @param int                    $level
     *
     * @return string
     */
    public static function convert(\Record\Subm &$subm)
    {
        $level = 0;
        $output = '';
        $_subm = $subm->getSubn();
        if (empty($_subm)) {
            return $output;
        } else {
            $output .= $level.' '.$_subm.' SUBM '."\n";
        }
        // level up
        $level++;

        // NAME
        $name = $subm->getName();
        if (!empty($name)) {
            $output .= $level.' NAME '.$name."\n";
        }
        // $chan
        $chan = $subm->getChan();
        if ($chan) {
            $_convert = \Writer\Chan::convert($chan, $level);
            $output .= $_convert;
        }

        // $addr
        $addr = $subm->getAddr();
        if ($addr) {
            $_convert = \Writer\Addr::convert($addr, $level);
            $output .= $_convert;
        }

        // $rin
        $rin = $subm->getRin();
        if (!empty($rin)) {
            $output .= $level.' RIN '.$rin."\n";
        }

        // $rfn
        $rfn = $subm->getRfn();
        if (!empty($rfn)) {
            $output .= $level.' RFN '.$rfn."\n";
        }

        // $lang = array()
        $langs = $subm->getLang();
        if (!empty($langs) && count($langs) > 0) {
            foreach ($langs as $item) {
                if ($item) {
                    $_convert = $level.' LANG '.$item."\n";
                    $output .= $_convert;
                }
            }
        }

        // $phon = array()
        $phon = $subm->getLang();
        if (!empty($phon) && count($phon) > 0) {
            foreach ($phon as $item) {
                if ($item) {
                    $_convert = \Writer\Phon::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        // $obje = array()
        $obje = $subm->getObje();
        if (!empty($obje) && count($obje) > 0) {
            foreach ($obje as $item) {
                $_convert = \Writer\ObjeRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // note
        $note = $subm->getNote();
        if (!empty($note) && count($note) > 0) {
            foreach ($note as $item) {
                $_convert = \Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
