<?php
/**
 * php-gedcom.
 *
 * php-gedcom is a library for parsing, manipulating, importing and exporting
 * GEDCOM 5.5 files in PHP 8.3.
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
    final public const GEDCOM55 = 'gedcom5.5';

    protected $_output;

    /**
     * @param        $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     *
     * @return string The contents of the document in the converted format
     */
    /**
     * Convert the GEDCOM object to the specified format.
     *
     * @param Gedcom $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     * @return string The contents of the document in the converted format
     */
    public static function convert(Gedcom $gedcom): string
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

        $output = '';

        // head
        if ($head) {
            $output = Head::convert($head, $format);
        }

        // subn
        if ($subn) {
            $output .= Subn::convert($subn);
        }

        // subms
        if (!empty($subms) && $subms !== []) {
            foreach ($subms as $item) {
                if ($item) {
                    $output .= Subm::convert($item);
                }
            }
        }

        // sours
        if (!empty($sours) && $sours !== []) {
            foreach ($sours as $item) {
                if ($item) {
                    $output .= Sour::convert($item, 0);
                }
            }
        }

        // indis
        if (!empty($indis) && $indis !== []) {
            foreach ($indis as $item) {
                if ($item) {
                    $output .= Indi::convert($item);
                }
            }
        }

        // fams
        if (!empty($fams) && $fams !== []) {
            foreach ($fams as $item) {
                if ($item) {
                    $output .= Fam::convert($item);
                }
            }
        }
        // notes
        if (!empty($notes) && $notes !== []) {
            foreach ($notes as $item) {
                if ($item) {
                    $output .= Note::convert($item);
                }
            }
        }

        // repos
        if (!empty($repos) && $repos !== []) {
            foreach ($repos as $item) {
                if ($item) {
                    $output .= Repo::convert($item);
                }
            }
        }
        // Objes
        if (!empty($objes) && $objes !== []) {
            foreach ($objes as $item) {
                if ($item) {
                    $output .= Obje::convert($item);
                }
            }
        }
        // EOF
        $output .= "0 TRLR\n";

     * Example:
     * ```
     * $gedcom = new Gedcom();
     * $gedcom->parse('gedcom_file.ged');
     *
     * $converted = Writer::convert($gedcom);
     * echo $converted;
     * ```
     *
     * @return string The contents of the document in the converted format
     */

        return $output;
    }
}
