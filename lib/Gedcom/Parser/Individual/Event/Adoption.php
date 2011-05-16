<?php

namespace Gedcom\Parser\Individual\Event;

/**
 *
 *
 */
class Adoption extends \Gedcom\Parser\Individual\Event
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $event = new \Gedcom\Record\Individual\Event\Adoption();
        
        if(isset($record[1]) && strtoupper(trim($record[1])) != 'EVEN')
            $event->type = trim($record[1]);
        
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
                case 'FAMC':
                    $event->famc = $parser->normalizeIdentifier($record[2]);
                break;
                
                case 'TYPE':
                    $event->type = trim($record[2]);
                break;
                
                case 'DATE':
                    $event->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $place = \Gedcom\Parser\Individual\Event\Place::parse($parser);
                    $event->place = &$place;
                break;
                
                case 'ADDR':
                    $event->addr = \Gedcom\Parser\Address::parse($parser);
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phone::parse($parser);
                    $event->addPhone($phone);
                break;
                
                case 'CAUS':
                    $event->caus = trim($record[2]);
                break;
                
                case 'AGE':
                    $event->age = trim($record[2]);
                break;
                
                case 'AGNC':
                    $event->agnc = trim($record[2]);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Reference'))
                        $event->addSourceCitationReference($citation);
                    else
                        $event->addSourceCitation($citation);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\Object::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $event->addObjectReference($object);
                    else
                        $event->addObject($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $event->addNoteReference($note);
                    else
                        $event->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $event;
    }
}
