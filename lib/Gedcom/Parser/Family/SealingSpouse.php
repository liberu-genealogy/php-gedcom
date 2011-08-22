<?php

namespace Gedcom\Parser\Family;

/**
 *
 *
 */
class SealingSpouse extends \Gedcom\Parser\Component
{

    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $slgs = new \Gedcom\Record\Family\SealingSpouse();
        
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
                case 'STAT':
                    $slgs->stat = trim($record[2]);
                break;
                
                case 'DATE':
                    $slgs->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $slgs->plac = trim($record[2]);
                break;
                
                case 'TEMP':
                    $slgs->temp = trim($record[2]);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Ref'))
                        $slgs->addSourceCitationRef($citation);
                    else
                        $slgs->addSourceCitation($citation);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $slgs->addNoteRef($note);
                    else
                        $slgs->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $slgs;
    }
}
