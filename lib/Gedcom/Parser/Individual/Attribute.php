<?php

namespace Gedcom\Parser\Individual;

/**
 *
 *
 */
class Attribute extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $attribute = new \Gedcom\Record\Event();
        $attribute->type = trim($record[1]);
        $attribute->attribute = isset($record[2]) ? trim($record[2]) : null;
        
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
                case 'TYPE':
                    $attribute->type = trim($record[2]);
                break;
                
                case 'DATE':
                    $attribute->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $place = \Gedcom\Parser\Individual\Event\Place::parse($parser);
                    $attribute->place = &$place;
                break;
                
                case 'ADDR':
                    $attribute->addr = \Gedcom\Parser\Address::parse($parser);
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phone::parse($parser);
                    $attribute->addPhone($phone);
                break;
                
                case 'CAUS':
                    $attribute->caus = trim($record[2]);
                break;
                
                case 'AGE':
                    $attribute->age = trim($record[2]);
                break;
                
                case 'AGNC':
                    $attribute->agnc = trim($record[2]);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Reference'))
                        $attribute->addSourceCitationReference($citation);
                    else
                        $attribute->addSourceCitation($citation);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\Object::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Object\Reference'))
                        $attribute->addObjectReference($object);
                    else
                        $attribute->addObject($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\Note::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Reference'))
                        $attribute->addNoteReference($note);
                    else
                        $attribute->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $attribute;
    }
}
