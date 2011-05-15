<?php

namespace Gedcom\Parser\Object;


/**
 *
 *
 */
class Embedded
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        
        $depth = (int)$record[0];
        
        $embedded = new \Gedcom\Record\Object\Embedded();
        
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
                case 'TITL':
                    $embedded->title = trim($record[2]);
                break;
                
                case 'FILE':
                    $embedded->file = trim($record[2]);
                break;
                
                case 'FORM':
                    $embedded->form = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $embedded->addNoteReference($note);
                    else
                        $embedded->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $embedded;
    }
}
