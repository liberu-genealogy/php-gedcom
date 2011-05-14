<?php

namespace Gedcom\Parser\Source;

/**
 *
 *
 */
class Data
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $depth = (int)$record[0];
        
        $data = new \Gedcom\Record\Source\Data();
        
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
                case 'EVEN':
                    $data->events[] = \Gedcom\Parser\Source\Data\Event::parse($parser);
                break;
                
                case 'AGNC':
                    $data->agnc = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $data->addNoteReference($note);
                    else
                        $data->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $data;
    }
}
