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

namespace Gedcom\Parser;

class Deat extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $parser->forward();

        $deat = new \Gedcom\Record\Deat();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = trim($record[1]);
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'DATE':
                    $deat->setDate(trim($record[2]));
                    break;
                case '_DATI':
                    $deat->setDati(trim($record[2]));
                    break;
                case 'PLAC':
                    $deat->setPlac(trim($record[2]));
                    break;
                case 'CAUS':
                    $deat->setCaus(trim($record[2]));
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $deat;
    }
}
