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

namespace PhpGedcom\Writer\Sour;

class Data
{
    /**
     * @param \PhpGedcom\Record\Sour\Data $data
     * @param int                         $level
     *
     * @return string
     */
    public static function convert(\PhpGedcom\Record\Sour\Data &$data, $level = 0)
    {
        $output = '';

        $output = $level." DATA\n";
        $level++;

        // $_date;
        $date = $data->getDate();
        if (!empty($date)) {
            $output .= $level.' DATE '.$date."\n";
        }

        // $_agnc AGNC
        $_agnc = $data->getAgnc();
        if (!empty($_agnc)) {
            $output .= $level.' AGNC '.$_agnc."\n";
        }

        // $_text
        $_text = $data->getText();
        if (!empty($_text)) {
            $output .= $level.' TEXT '.$_text."\n";
        }

        // $_note
        $note = $data->getNote();
        if ($note && count($note) > 0) {
            foreach ($note as $item) {
                $_convert = \PhpGedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // $_even
        $_even = $data->getEven();
        if ($_even && count($_even) > 0) {
            foreach ($_even as $item) {
                $_convert = \PhpGedcom\Writer\Sour\Data\Even::convert($item, $level);
                $output .= $_convert;
            }
        }

        return $output;
    }
}
