<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/Note/Ref.php';

/**
 *
 *
 */
class NoteRef extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        if(isset($record[2]) && preg_match('/\@N([0-9]*)\@/i', $record[2]) > 0)
            return \Gedcom\Parser\Note\Ref::parse($parser);
        else
            return \Gedcom\Parser\Note\Text::parse($parser);
    }
}
