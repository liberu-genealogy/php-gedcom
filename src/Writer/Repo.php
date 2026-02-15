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

class Repo
{
    /**
     * @param \Gedcom\Record\Repo $sour
     * @param int                 $level
     *
     * @return string
     */
    public static function convert(\Gedcom\Record\Repo &$repo)
    {
        $level = 0;
        $output = '';
        $_repo = $repo->getRepo();
        if ($_repo !== '' && $_repo !== '0') {
            $output .= $level.' '.$_repo." REPO\n";
        } else {
            return $output;
        }

        // level up
        $level++;

        // UID handling - version-specific
        // GEDCOM 5.5.1 uses _UID (custom tag)
        // GEDCOM 7.0 uses UID (standard tag)
        if (\Gedcom\Writer::isGedcom55()) {
            // Output _UID for GEDCOM 5.5.1
            $uids = $repo->getAllUid();
            if (!empty($uids)) {
                foreach ($uids as $uid) {
                    if (!empty($uid)) {
                        $output .= $level.' _UID '.$uid."\n";
                    }
                }
            }
        }

        if (\Gedcom\Writer::isGedcom70()) {
            // Output UID for GEDCOM 7.0
            $uids7 = $repo->getAllUid7();
            if (!empty($uids7)) {
                foreach ($uids7 as $uid7) {
                    if (!empty($uid7)) {
                        $output .= $level.' UID '.$uid7."\n";
                    }
                }
            }
        }

        //NAME
        $name = $repo->getName();
        if ($name !== '' && $name !== '0') {
            $output .= $level.' NAME '.$name."\n";
        }

        // ADDR
        $addr = $repo->getAddr();
        if ($addr) {
            $_convert = \Gedcom\Writer\Addr::convert($addr, $level);
            $output .= $_convert;
        }

        // PHON
        $phon = $repo->getPhon();
        if ($phon !== []) {
            $_convert = \Gedcom\Writer\Phon::convert($phon, $level);
            $output .= $_convert;
        }

        // NOTE array
        $note = $repo->getNote();
        foreach ($note as $item) {
            if ($item) {
                $_convert = \Gedcom\Writer\NoteRef::convert($item, $level);
                $output .= $_convert;
            }
        }

        // REFN
        $refn = $repo->getRefn();
        if (!empty($refn) && $refn !== []) {
            foreach ($refn as $item) {
                if ($item) {
                    $_convert = \Gedcom\Writer\Refn::convert($item, $level);
                    $output .= $_convert;
                }
            }
        }

        // CHAN
        $chan = $repo->getChan();
        if ($chan) {
            $_convert = \Gedcom\Writer\Chan::convert($chan, $level);
            $output .= $_convert;
        }

        // RIN
        $rin = $repo->getRin();
        if ($rin !== '' && $rin !== '0') {
            $output .= $level.' RIN '.$rin."\n";
        }

        return $output;
    }
}
