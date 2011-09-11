<?php
/**
 *
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class Slgc extends Lds
{
    /**
     *
     */
    public static function parseFamc(&$parser, &$slgc)
    {
        $record = $parser->getCurrentLineRecord();
        $slgc->famc = $parser->normalizeIdentifier($record[2]);
    }
}
