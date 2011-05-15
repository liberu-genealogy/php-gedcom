<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class ReferenceNumber
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $depth = (int)$record[0];
        
        $referenceNumber = new \Gedcom\Record\ReferenceNumber();
        $referenceNumber->number = trim($record[2]);
        
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
                case 'TYPE':
                    $referenceNumber->type = trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $referenceNumber;
    }
}
