<?php

namespace Gedcom\Parser\Individual;

/**
 *
 *
 */
class Name extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $name = new \Gedcom\Record\Individual\Name();
        $name->name = trim($record[2]);
        
        $parser->forward();
        
        while($parser->getCurrentLine() < $parser->getFileLength())
        {
            $record = $parser->getCurrentLineRecord();
            $recordType = strtoupper(trim($record[1]));
            $currentDepth = (int)$record[0];
            
            if($currentDepth <= $depth)
            {
                $parser->back();
                break;
            }
            
            switch($recordType)
            {
                case 'NPFX':
                    $name->npfx = trim($record[2]);
                break;
                
                case 'GIVN':
                    $name->givn = trim($record[2]);
                break;
                
                case 'NICK':
                    $name->nick = trim($record[2]);
                break;
                
                case 'SPFX':
                    $name->spfx = trim($record[2]);
                break;
                
                case 'SURN':
                    $name->surn = trim($record[2]);
                break;
                
                case 'NSFX':
                    $name->nsfx = trim($record[2]);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Reference'))
                        $name->addSourceCitationReference($citation);
                    else
                        $name->addSourceCitation($citation);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteReference::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $name->addNoteReference($note);
                    else
                        $name->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $name;
    }
}
