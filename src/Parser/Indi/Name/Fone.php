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

namespace Gedcom\Parser\Indi\Name;

class Fone extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[2])) {
            $fone = new \Gedcom\Record\Indi\Name\Fone();
            $fone->setFone(trim($record[2]));
        } else {
            return null;
        }

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            if (!isset($record[2])) {
                $record[2] = '';
            }

            switch ($recordType) {
                case 'TYPE':
                    $fone->setType(trim($record[2]));
                    break;
                case 'NPFX':
                    $fone->setNpfx(trim($record[2]));
                    break;
                case 'GIVN':
                    $fone->setGivn(trim($record[2]));
                    break;
                case 'NICK':
                    $fone->setNick(trim($record[2]));
                    break;
                case 'SPFX':
                    $fone->setSpfx(trim($record[2]));
                    break;
                case 'SURN':
                    $fone->setSurn(trim($record[2]));
                    break;
                case 'NSFX':
                    $fone->setNsfx(trim($record[2]));
                    break;
                default:
                    $parser->logUnhandledRecord(get_class().' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $fone;
    }
}
