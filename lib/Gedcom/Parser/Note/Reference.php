<?php

namespace Gedcom\Parser\Note;


/**
 *
 *
 */
class Reference
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $depth = (int)$record[0];
        
        $identifier = str_replace('@', '', $record[2]);
        
        $reference = new \Gedcom\Record\Note\Reference();
        $reference->refId = $identifier;
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            
            if((int)$record[0] <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($record[1])
            {
                case 'SOUR':
                    // FIXME
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $reference;
    }
}
