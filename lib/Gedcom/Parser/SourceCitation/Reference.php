<?php

namespace Gedcom\Parser\SourceCitation;

/**
 *
 *
 */
class Reference extends \Gedcom\Parser\Component
{

/*
  n SOUR @<XREF:SOUR>@    {1:1}
    +1 EVEN <EVENT_TYPE_CITED_FROM>  {0:1}
      +2 ROLE <ROLE_IN_EVENT>  {0:1}
    +1 DATA        {0:1}
      +2 DATE <ENTRY_RECORDING_DATE>  {0:1}
      +2 TEXT <TEXT_FROM_SOURCE>  {0:M}
        +3 [ CONC | CONT ] <TEXT_FROM_SOURCE>  {0:M}

*/
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $reference = new \Gedcom\Record\SourceCitation\Reference();
        $reference->sourceId = $parser->normalizeIdentifier($record[1]);
        
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
                case 'QUAY':
                    $reference->quay = trim($record[2]);
                break;
                
                case 'PAGE':
                    $reference->page = trim($record[2]);
                break;
                
                case 'EVEN':
                    $even = \Gedcom\Parser\SourceCitation\Event::parse($parser);
                    $note->even = &$even;
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\ObjectReference::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $reference->addObjectReference($object);
                    else
                        $reference->addObject($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteReference::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $reference->addNoteReference($note);
                    else
                        $reference->addNote($note);
                break;
                
                case 'DATA':
                    $reference->data = \Gedcom\Parser\Source\Data::parse($parser);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $reference;
    }
}
