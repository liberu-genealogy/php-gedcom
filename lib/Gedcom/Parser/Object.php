<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Object extends \Gedcom\Parser\Component
{

/*
  n @<XREF:OBJE>@ OBJE  {1:1}
    +1 FORM <MULTIMEDIA_FORMAT>  {1:1}
    +1 TITL <DESCRIPTIVE_TITLE>  {0:1}
    +1 <<NOTE_STRUCTURE>>  {0:M}
    +1 <<SOURCE_CITATION>>  {0:M}
    +1 BLOB        {1:1}
      +2 CONT <ENCODED_MULTIMEDIA_LINE>  {1:M}
    +1 OBJE @<XREF:OBJE>@     // chain to continued object //  {0:1}
    +1 REFN <USER_REFERENCE_NUMBER>  {0:M}
      +2 TYPE <USER_REFERENCE_TYPE>  {0:1}
    +1 RIN <AUTOMATED_RECORD_ID>  {0:1}
    +1 <<CHANGE_DATE>>  {0:1}
*/


    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $identifier = $parser->normalizeIdentifier($record[1]);
        $depth = (int)$record[0];
        
        $object = new \Gedcom\Record\Object();
        $object->refId = $identifier;
        
        $parser->getGedcom()->addObject($object);
        
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
                case 'FORM':
                    $object->form = trim($record[2]);
                break;
                
                case 'TITL':
                    $object->titl = trim($record[2]);
                break;
                
                case 'OBJE':
                    $object->form = $this->normalizeIdentifier($record[2]);
                break;
                
                case 'RIN':
                    $object->rin = trim($record[2]);
                break;
                
                case 'REFN':
                    $referenceNumber = \Gedcom\Parser\ReferenceNumber::parse($parser);
                    $object->addReferenceNumber($referenceNumber);
                break;
                
                case 'BLOB':
                    $object->blob = $parser->parseMultiLineRecord();
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteReference::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $object->addNoteReference($note);
                    else
                        $object->addNote($note);
                break;
                
                case 'CHAN':
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $object->change = &$change;
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $object;
    }
}
