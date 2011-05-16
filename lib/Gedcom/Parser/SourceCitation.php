<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/SourceCitation/Embedded.php';
require_once __DIR__ . '/SourceCitation/Reference.php';

/**
 *
 *
 */
class SourceCitation extends \Gedcom\Parser\Component
{
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $citation = null;
        
        if(isset($record[2]) && preg_match('/\@([A-Z0-9]*)\@/i', $record[2]) > 0)
            $citation = \Gedcom\Parser\SourceCitation\Reference::parse($parser);
        else
            $citation = \Gedcom\Parser\SourceCitation\Embedded::parse($parser);
        
        return $citation;
    }
}
