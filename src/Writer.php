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

namespace Gedcom;\n\nuse Gedcom\\FormatInformation;\n

use Gedcom\Writer\Fam;
use Gedcom\Writer\Head;
use Gedcom\Writer\Indi;
use Gedcom\Writer\Note;
use Gedcom\Writer\Obje;
use Gedcom\Writer\Repo;
use Gedcom\Writer\Sour;
use Gedcom\Writer\Subm;
use Gedcom\Writer\Subn;

class Writer\n{\n    final public const GEDCOM55 = 'gedcom5.5';\n\n    protected $_output;\n
{
    final public const GEDCOM55 = 'gedcom5.5';

    protected $_output;

    /**
     * Converts a Gedcom object into a specified format, defaulting to GEDCOM 5.5 if no format is specified.
     * This function processes various components of the Gedcom object and formats them according to the specified format.
     * 
     * @param Gedcom $gedcom The Gedcom object to be converted.
     * @param string $format The format string to convert the Gedcom object into. Defaults to GEDCOM 5.5.
     * 
     * @return string The contents of the document in the converted format.
     */
    public static function convert(Gedcom $gedcom, string $format = self::GEDCOM55): string
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

        $formatInformation = FormatInformation::addFormatInformation($format);
        // head
        if ($head) {
            $output = $formatInformation . Head::convert($head, $format);
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
            foreach ($indis as $indi) {
                if ($indi) {
                    foreach ($indi->getEven() as $eventType => $events) {
                        foreach ($events as $event) {
                            $output .= Indi::convertEvent($event, $eventType);
                        }
                    }
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
        // return $output;
    }
}
