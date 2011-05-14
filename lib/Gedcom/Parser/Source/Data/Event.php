<?php

namespace Gedcom\Parser\Source\Data;


/**
 *
 *
 */
class Event
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $depth = (int)$record[0];
        
        $event = new \Gedcom\Record\Source\Data\Event();
        
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
                case 'DATE':
                    $event->date = trim($record[2]);
                break;
            
                case 'PLAC':
                    $event->place = trim($record[2]);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $event;
    }
}
