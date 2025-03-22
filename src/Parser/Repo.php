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

class Repo extends \Gedcom\Parser\Component
{
    public static function parse(\Gedcom\Parser $parser): ?\Gedcom\Record\Repo
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (isset($record[1])) {
            $identifier = $parser->normalizeIdentifier($record[1]);
        } else {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $repo = new \Gedcom\Record\Repo();
        $repo->setRepo($identifier);

        $parser->getGedcom()->addRepo($repo);

        $parser->forward();

        while (!$parser->eof()) {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int) $record[0];
            $recordType = strtoupper(trim((string) $record[1]));

            if ($currentDepth <= $depth) {
                $parser->back();
                break;
            }

            switch ($recordType) {
                case 'NAME':
                    $repo->setName(trim((string) $record[2]));
                    break;
                case 'ADDR':
                    $repo->setAddr(\Gedcom\Parser\Addr::parse($parser));
                    break;
                case 'PHON':
                    $repo->addPhon(trim((string) $record[2]));
                    break;
                case 'EMAIL':
                    $repo->addEmail(trim((string) $record[2]));
                    break;
                case 'FAX':
                    $repo->addFax(trim((string) $record[2]));
                    break;
                case 'WWW':
                    $repo->addWww(trim((string) $record[2]));
                    break;
                case 'NOTE':
                    match ($recordType) {
                        'NAME' => $repo->setName(trim((string) $record[2])),
                        'ADDR' => $repo->setAddr(\Gedcom\Parser\Addr::parse($parser)),
                        'NOTE' => $repo->addNote(\Gedcom\Parser\NoteRef::parse($parser)),
                        'REFN' => $repo->addRefn(\Gedcom\Parser\Refn::parse($parser)),
                        default => $parser->logUnhandledRecord(self::class.' @ '.__LINE__)
                    };
                    break;
                case 'REFN':
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $repo->addRefn($refn);
                    break;
                case 'RIN':
                    $repo->setRin(trim((string) $record[2]));
                    break;
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $repo->setChan($chan);
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $repo;
    }
}