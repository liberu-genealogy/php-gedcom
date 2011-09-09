<?php
/**
 *
 */

namespace Gedcom\Parser;

/**
 *
 *
 */
class Caln extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[2]);
        $depth = (int)$record[0];
        
        $caln = new \Gedcom\Record\Caln();
        $caln->caln = $identifier;
        
        $parser->forward();
        
        while(!$parser->eof())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtolower(trim($record[1]));
            $lineDepth = (int)$record[0];
            
            if($lineDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            if($caln->hasAttribute($recordType))
                $caln->$recordType = trim($record[2]);
            else
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            
            $parser->forward();
        }
        
        return $caln;
    }
}
