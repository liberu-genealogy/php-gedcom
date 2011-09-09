<?php
/**
 *
 */

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class Adop extends \Gedcom\Parser\Indi\Even
{
    public static function parseAdop(&$parser, &$even)
    {
        $record = $parser->getCurrentLineRecord();
        $even->adop = trim($record[2]);
    }
    
    public static function parseFamc(&$parser, &$even)
    {
        $record = $parser->getCurrentLineRecord();
        $even->famc = trim($record[2]);
    }
}
