<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class ObjeRef extends \Gedcom\Parser\Component
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
            $object = \Gedcom\Parser\Obje\Ref::parse($parser);
        else
            $object = \Gedcom\Parser\Obje\Embe::parse($parser);
        
        return $object;
    }
}
