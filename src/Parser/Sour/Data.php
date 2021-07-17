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

namespace Gedcom\Parser\Sour;

class Data extends \Gedcom\Parser\Component
{
    public static function parse(Gedcom\Parser $parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];

        $data = new \Record\Sour\Data();

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int) $record[0];

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'EVEN':
                    $data->addEven(\Parser\Sour\Data\Even::parse($parser));
                    break;
                case 'DATE': // not in 5.5.1
                    $data->setDate(trim($record[2]));
                    break;
                case 'AGNC':
                    $data->setAgnc(trim($record[2]));
                    break;
                case 'NOTE':
                    $note = \Parser\NoteRef::parse($parser);
                    if ($note) {
                        $data->addNote($note);
                    }
                    break;
                case 'TEXT': // not in 5.5.1
                    $data->setText($parser->parseMultiLineRecord());
                    break;
                default:
                    $parser->logUnhandledRecord(get_class().' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $data;
    }
}
