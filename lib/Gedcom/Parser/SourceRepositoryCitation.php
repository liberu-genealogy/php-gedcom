<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class SourceRepositoryCitation extends \Gedcom\Parser\Component
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
        
        $citation = $parser->getGedcom()->createSourceRepositoryCitation($identifier);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $currentDepth = (int)$record[0];
            $recordType = strtoupper(trim($record[1]));
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'CALN': 
                    $citation->caln[] = \Gedcom\Parser\SourceCallNumber::parse($parser);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteReference::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $citation->addNoteReference($note);
                    else
                        $citation->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $citation;
    }
}
