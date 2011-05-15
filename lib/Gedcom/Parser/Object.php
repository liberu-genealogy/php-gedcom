<?php

namespace Gedcom\Parser;


/**
 *
 *
 */
class Object
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        if(isset($record[2]) && preg_match('/\@([A-Z0-9]*)\@/i', $record[2]) > 0)
            return \Gedcom\Parser\Object\Reference::parse($parser);
        else
            return \Gedcom\Parser\Object\Embedded::parse($parser);
    }
}
