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

namespace Writer;

class Note
{
    /**
     * @param \Record\Note $sour
     * @param int                    $level
     *
     * @return string
     */
    public static function convert(\Record\Note &$note)
    {
        $level = 0;
        $output = '';
        $id = $note->getId();
        if (!empty($id)) {
            $output .= $level.' '.$id.' '." NOTE \n";
        } else {
            return $output;
        }

        // Level Up
        $level++;
        // RIN
        $rin = $note->getRin();
        if ($rin) {
            $output .= $level.' RIN '.$rin."\n";
        }

        // cont
        $cont = $note->getNote();
        if ($cont) {
            $output .= $level.' CONT '.$cont."\n";
        }

        // REFN
        $refn = $note->getRefn();
        if (!empty($refn) && count($refn) > 0) {
            foreach ($refn as $item) {
                if ($item) {
                    $_convert = \Writer\Refn::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }
        // CHAN
        $chan = $note->getChan();
        if ($chan) {
            $_convert = \Writer\Chan::convert($chan, $level);
            $output .= $_convert;
        }

        // SOUR array
        $sour = $note->getSour();
        if (!empty($sour) && count($sour) > 0) {
            foreach ($sour as $item) {
                if ($item) {
                    $_convert = \Writer\SourRef::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        return $output;
    }
}
