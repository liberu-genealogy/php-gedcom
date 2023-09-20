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

class Sour
{
    /**
     * @param int $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Sour &$sour, $level)
    {
        $output = '';
        $_sour = $sour->getSour();
        if (empty($_sour)) {
            return $output;
        } else {
            $output .= $level.' '.$_sour.' SOUR '."\n";
        }
        // level up
        $level++;

        // TITL
        $titl = $sour->getType();
        if (!empty($type)) {
            $output .= $level.' TITL '.$titl."\n";
        }

        // RIN
        $rin = $sour->getRin();
        if (!empty($rin)) {
            $output .= $level.' RIN '.$rin."\n";
        }

        // AUTH
        $auth = $sour->getAuth();
        if (!empty($auth)) {
            $output .= $level.' AUTH '.$auth."\n";
        }

        // TEXT
        $text = $sour->getText();
        if (!empty($text)) {
            $output .= $level.' TEXT '.$text."\n";
        }

        // PUBL
        $publ = $sour->getPubl();
        if (!empty($publ)) {
            $output .= $level.' PUBL '.$publ."\n";
        }

        // ABBR
        $abbr = $sour->getAbbr();
        if (!empty($abbr)) {
            $output .= $level.' ABBR '.$abbr."\n";
        }

        // REPO
        $repo = $sour->getRepo();
        if (!empty($repo)) {
            $_convert = \Gedcom\Writer\RepoRef::convert($repo, $level);
            $output .= $_convert;
        }

        // NOTE array
        $note = $sour->getNote();
        if (!empty($note) && $note !== []) {
            foreach ($note as $item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // DATA
        $data = $sour->getData();
        if (!empty($data)) {
            $_convert = \Gedcom\Writer\Sour\Data::convert($data, $level);
            $output .= $_convert;
        }

        // OBJE array
        $obje = $sour->getObje();
        if (!empty($obje) && $obje !== []) {
            foreach ($obje as $item) {
                $_convert = \Gedcom\Writer\ObjeRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // REFN array
        $refn = $sour->getRefn();
        if (!empty($refn) && $refn !== []) {
            foreach ($refn as $item) {
                $_convert = \Gedcom\Writer\Refn::convert($item, $level);
                $output .= $_convert;
            }
        }

        // chan
        $chan = $sour->getChan();
        if (!empty($chan)) {
            $_convert = \Gedcom\Writer\Chan::convert($chan, $level);
            $output .= $_convert;
        }

        return $output;
    }
}
