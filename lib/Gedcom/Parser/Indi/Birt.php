<?php
/**
 *
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class Birt extends \Gedcom\Parser\Indi\Even
{
    public static function parseFamc(&$parser, &$even)
    {
        $record = $parser->getCurrentLineRecord();
        $even->famc = trim($record[2]);
    }
}
