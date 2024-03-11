<?php
/**
 * php-gedcom is a library for parsing, manipulating, importing, and exporting GEDCOM 5.5 files in PHP 8.3. This file contains functions for writing GEDCOM data.
 *
 * php-gedcom is a library for parsing, manipulating, importing, and exporting GEDCOM 5.5 files in PHP 8.3. This file contains functions to convert GEDCOM data to a specific format.
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
     * @param        $gedcom The GEDCOM object
     * @param string $format The format to convert the GEDCOM object to
     *
     * @return string The contents of the document in the converted format
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
        $output .= self::convertHead($head, $format, $formatInformation);

            /*
     * Convert the head section of GEDCOM.
     *
     * @param mixed $head
     * @param string $format
     * @param string $formatInformation
     * @return string
     */
        if ($subn) {
    /**
     * Convert head section of GEDCOM.
     *
     * @param mixed $head
     * @param string $format
     * @param string $formatInformation
     * @return string
     */
    protected static function convertHead($head, string $format, string $formatInformation): string
    {
        $output = '';
        if ($head) {
            $output = $formatInformation . Head::convert($head, $format);
        }
        return $output;
    }
        $output .= self::convertSubms($subms);
        $output .= self::convertSours($sours);

        // indis
        if (!empty($indis) && $indis !== []) {
        /*
     * Convert the subn section of GEDCOM.
     *
     * @param mixed $subn
     * @return string
     */
    protected static function convertSubn($subn): string
    {
        $output = '';
        if ($subn) {
            $output .= Subn::convert($subn);
        }
        return $output;
    }

    /*
     * Convert the subms section of GEDCOM.
     *
     * @param array $subms
     * @return string
     */
    protected static function convertSubms(array $subms): string
    {
        $output = '';
        foreach ($subms as $item) {
            if ($item) {
                $output .= Subm::convert($item);
            }
        }
        return $output;
    }

        /*
     * Convert the sours section of GEDCOM.
     *
     * @param array $sours
     * @return string
     */
    protected static function convertSours(array $sours): string
    {
        $output = '';
        foreach ($sours as $item) {
            if ($item) {
                $output .= Sour::convert($item, 0);
            }
        }
        return $output;
    }
        $output .= self::convertFams($fams);
        // notes
        if (!empty($notes) && $notes !== []) {
        /*
     * Convert the indis section of GEDCOM.
     *
     * @param array $indis
     * @return string
     */
    protected static function convertIndis(array $indis): string
    {
        $output = '';
        foreach ($indis as $indi) {
            if ($indi) {
                foreach ($indi->getEven() as $eventType => $events) {
                    foreach ($events as $event) {
                        $output .= Indi::convertEvent($event, $eventType);
                    }
                }
            }
        }
        return $output;
    }

        /*
     * Convert the fams section of GEDCOM.
     *
     * @param array $fams
     * @return string
     */
    protected static function convertFams(array $fams): string
    {
        $output = '';
        foreach ($fams as $item) {
            if ($item) {
                $output .= Fam::convert($item);
            }
        }
        return $output;
    }
        $output .= self::convertRepos($repos);
        $output .= self::convertObjes($objes);
    }
}
        /*
     * Convert the notes section of GEDCOM.
     *
     * @param array $notes
     * @return string
     */
    protected static function convertNotes(array $notes): string
    {
        $output = '';
        foreach ($notes as $item) {
            if ($item) {
                $output .= Note::convert($item);
            }
        }
        return $output;
    }

        /*
     * Convert the repos section of GEDCOM.
     *
     * @param array $repos
     * @return string
     */
    protected static function convertRepos(array $repos): string
    {
        $output = '';
        foreach ($repos as $item) {
            if ($item) {
                $output .= Repo::convert($item);
            }
        }
        return $output;
    }

        /*
     * Convert the objes section of GEDCOM.
     *
     * @param array $objes
     * @return string
     */
    protected static function convertObjes(array $objes): string
    {
        $output = '';
        foreach ($objes as $item) {
            if ($item) {
                $output .= Obje::convert($item);
            }
        }
        return $output;
    }
