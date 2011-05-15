<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Change extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $parser->forward();
        
        $change = new \Gedcom\Record\Change();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = trim($record[1]);
            $currentDepth = (int)$record[0];
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'DATE':
                    $change->date = trim($record[2]);
                break;
                
                case 'TIME':
                    $change->time = trim($record[2]);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $change->addNoteReference($note);
                    else
                        $change->addNote($note);
                break;
            
                default:
                    $parser->logUnhandledRecord(__LINE__);
            }
            
            $parser->forward();
        }
        
        return $change;
    }
}
