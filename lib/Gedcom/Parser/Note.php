<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Note extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $note = $parser->getGedcom()->createNote($identifier);
        
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
                case 'RIN':
                    $note->rin = trim($record[2]);
                break;
                
                case 'CONT':
                    if(isset($record[2]))
                        $note->note .= "\n" . $record[2];
                break;
                
                case 'CONC':
                    if(isset($record[2]))
                        $note->note .= $record[2];
                break;
               
                case 'REFN':
                    $reference = \Gedcom\Parser\ReferenceNumber::parse($parser);
                    $note->addReferenceNumber($reference);
                break;
                
                case 'CHAN':
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $note->change = &$change;
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Reference'))
                        $note->addSourceCitationReference($citation);
                    else
                        $note->addSourceCitation($citation);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $source;
    }
}
