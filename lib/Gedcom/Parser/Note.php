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
        $record = $parser->getCurrentLineRecord(4);
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $note = new \Gedcom\Record\Note();
        $note->refId = $identifier;
        
        if(isset($record[3]))
            $note->note = $record[3];
        
        $parser->getGedcom()->addNote($note);
        
        if(isset($record[3]))
            $note->note = $record[3];
        
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
                    $refn = \Gedcom\Parser\Refn::parse($parser);
                    $note->addRefn($refn);
                break;
                
                case 'CHAN':
                    $chan = \Gedcom\Parser\Chan::parse($parser);
                    $note->chan = &$chan;
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Ref'))
                        $note->addSourceCitationRef($citation);
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
