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

namespace Gedcom;

use Gedcom\Writer\Fam;
use Gedcom\Writer\Head;
use Gedcom\Writer\Indi;
use Gedcom\Writer\Note;
use Gedcom\Writer\Obje;
use Gedcom\Writer\Repo;
use Gedcom\Writer\Sour;
use Gedcom\Writer\Subm;
use Gedcom\Writer\Subn;

class Writer
{
    const GEDCOM55 = 'gedcom5.5';

    protected $_output = null;

    /**
     * @param \Gedcom\Gedcom $gedcom The GEDCOM object
     * @param string            $format The format to convert the GEDCOM object to
     *
     * @return string The contents of the document in the converted format
     */
    public static function convert(Gedcom $gedcom, $format = self::GEDCOM55)
    {
        $head = $gedcom->getHead();
        $subn = $gedcom->getSubn();
        $subms = $gedcom->getSubm();    // array()
        $sours = $gedcom->getSour();    // array()
        $indis = $gedcom->getIndi();    // array()
        $fams = $gedcom->getFam();      // array()
        $notes = $gedcom->getNote();    // array()
        $repos = $gedcom->getRepo();    // array()
        $objes = $gedcom->getObje();    // array()

        $output = '0 FORMAT '.$format."\n";

        // head
        if ($head) {
            $output = Head::convert($head, $format);
        }

        // subn
        if ($subn) {
            $output .= Subn::convert($subn);
        }

        // subms
        if (!empty($subms) && count($subms) > 0) {
            foreach ($subms as $item) {
                if ($item) {
                    $output .= Subm::convert($item);
                }
            }
        }

        // sours
        if (!empty($sours) && count($sours) > 0) {
            foreach ($sours as $item) {
                if ($item) {
                    $output .= Sour::convert($item);
                }
            }
        }

        // indis
        if (!empty($indis) && count($indis) > 0) {
            foreach ($indis as $item) {
                if ($item) {
                    $output .= Indi::convert($item);
                }
            }
        }

        // fams
        if (!empty($fams) && count($fams) > 0) {
            foreach ($fams as $item) {
                if ($item) {
                    $output .= Fam::convert($item);
                }
            }
        }
        // notes
        if (!empty($notes) && count($notes) > 0) {
            foreach ($notes as $item) {
                if ($item) {
                    $output .= Note::convert($item);
                }
            }
        }

        // repos
        if (!empty($repos) && count($repos) > 0) {
            foreach ($repos as $item) {
                if ($item) {
                    $output .= Repo::convert($item);
                }
            }
        }
        // Objes
        if (!empty($objes) && count($objes) > 0) {
            foreach ($objes as $item) {
                if ($item) {
                    $output .= Obje::convert($item);
                }
            }
        }
        // EOF
        $output .= "0 TRLR\n";

        return $output;
    }
}
