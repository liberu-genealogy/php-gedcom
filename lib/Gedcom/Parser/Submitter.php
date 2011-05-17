<?php

namespace Gedcom\Parser;

/**
 *
 *
 */
class Submitter extends \Gedcom\Parser\Component
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
        
        $subm = new \Gedcom\Record\Submitter();
        $subm->refId = $identifier;
        
        $parser->getGedcom()->addSubmitter($subm);
        
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
                case 'NAME':
                    $subm->name = trim($record[2]);
                break;
                
                case 'ADDR':
                    $addr = \Gedcom\Parser\Address::parse($parser);
                    $subm->addr = &$addr;
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phone::parse($parser);
                    $subm->addPhone($phone);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteReference::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $subm->addNoteReference($note);
                    else
                        $subm->addNote($note);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\ObjectReference::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $subm->addObjectReference($object);
                    else
                        $subm->addObject($object);
                break;
                
                case 'CHAN':
                    $change = \Gedcom\Parser\Change::parse($parser);
                    $subm->change = &$change;
                break;
                
                case 'RIN':
                    $subm->rin = trim($record[2]);
                break;
                
                case 'RFN':
                    $subm->rfn = trim($record[2]);
                break;
                
                case 'LANG':
                    $subm->addLanguage(trim($record[2]));
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $subm;
    }
}
