<?php

namespace Gedcom\Parser;


/**
 *
 *
 */
class Note
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        if(isset($record[2]) && preg_match('/\@N([0-9]*)\@/i', $record[2]) > 0)
            return \Gedcom\Parser\Note\Reference::parse($parser);
        else
            return \Gedcom\Parser\Note\Text::parse($parser);
    }
}
