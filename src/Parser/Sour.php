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

class Sour extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $sour = new \Gedcom\Record\Sour();
        $sour->setSour($identifier);

        $parser->getGedcom()->addSour($sour);

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim($record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'DATA':
                    $sour->setData(\Gedcom\Parser\Sour\Data::parse($parser));
                    break;
                case 'AUTH':
                    $sour->setAuth($parser->parseMultilineRecord());
                    break;
                case 'TITL':
                    $sour->setTitl($parser->parseMultilineRecord());
                    break;
                case 'ABBR':
                    $sour->setAbbr(trim($record[2]));
                    break;
                case 'PUBL':
                    $sour->setPubl($parser->parseMultilineRecord());
                    break;
                case 'TEXT':
                    $sour->setText($parser->parseMultilineRecord());
                    break;
                case 'REPO':
                    $sour->setRepo(\Gedcom\Parser\Sour\Repo::parse($parser));
                    break;
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $sour->addRefn($refn);
                    break;
                case 'RIN':
                    $sour->setRin(trim($record[2]));
                    break;
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $sour->setChan($chan);
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $sour->addNote($note);
                    }
                    break;
                case 'OBJE':
                    $obje = \Gedcom\Parser\ObjeRef::parse($parser);
                    $sour->addObje($obje);
                    break;
                default:
                    $parser->logUnhandledRecord(get_class().' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $sour;
    }
}
