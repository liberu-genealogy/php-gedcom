<?php

declare(strict_types=1);

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

final class RepoRef extends Component
{
    public static function parse(\Gedcom\Parser $parser): ?\Gedcom\Record\RepoRef
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int) $record[0];
        if (!isset($record[2])) {
            $parser->skipToNextLevel($depth);

            return null;
        }

        $repo = new \Gedcom\Record\RepoRef();
        $repo->setRepo($parser->normalizeIdentifier($record[2]));

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
                case 'CALN':
                    $repo->addCaln(\Parser\Caln::parse($parser));
                    break;
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    if ($note) {
                        $repo->addNote($note);
                    }
                    break;
                default:
                    $parser->logUnhandledRecord(self::class.' @ '.__LINE__);
            }

            $parser->forward();
        }

        return $repo;
    }
}