<?php

namespace Gedcom\Parser;


/**
 *
 *
 */
class SourceRepositoryCitation
{
    public static function parse(&$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = str_replace('@', '', $record[2]);
        
        $depth = (int)$record[0];
        
        $citation = $parser->getGedcom()->createSourceRepositoryCitation($identifier);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $lineDepth = (int)$record[0];
            
            if($lineDepth <= $depth)
            {
                $parser->back();
                break;
            }
            else if($lineDepth == $depth + 1 && trim($record[1]) == 'CALN')
            {
                $citation->caln[] = \Gedcom\Parser\SourceCallNumber::parse($parser);
            }
            else if($lineDepth == $depth + 1 && trim($record[1]) == 'NOTE')
            {
                $note = \Gedcom\Parser\Note::parse($parser);
                
                if(is_a($note, '\Gedcom\Record\Note\Reference'))
                    $citation->addNoteReference($note);
                else
                    $citation->addNote($note);
            }
            else
            {
                $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $citation;
    }
}
