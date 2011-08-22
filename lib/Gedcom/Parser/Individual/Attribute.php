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
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $attribute->addPhon($phone);
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
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Ref'))
                        $attribute->addSourceCitationRef($citation);
                    else
                        $attribute->addSourceCitation($citation);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\ObjeRef::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Obje\Ref'))
                        $attribute->addObjeRef($object);
                    else
                        $attribute->addObje($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $attribute->addNoteRef($note);
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
