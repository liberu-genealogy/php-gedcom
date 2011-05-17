<?php

namespace Gedcom\Parser;

require_once __DIR__ . '/Object/Embedded.php';
require_once __DIR__ . '/Object/Reference.php';

/**
 *
 *
 */
class ObjectReference extends \Gedcom\Parser\Component
{
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $object = null;
        
        if(isset($record[2]) && preg_match('/\@([A-Z0-9]*)\@/i', $record[2]) > 0)
            $object = \Gedcom\Parser\Object\Reference::parse($parser);
        else
            $object = \Gedcom\Parser\Object\Embedded::parse($parser);
        
        return $object;
    }
}
