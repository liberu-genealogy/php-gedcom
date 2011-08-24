<?php

namespace Gedcom\Parser\Indi;

/**
 *
 *
 */
class Attr extends \Gedcom\Parser\Component
{
    
    /**
     *
     *
     */
    public static function &parse(\Gedcom\Parser &$parser)
    {
        $record = $parser->getCurrentLineRecord();
        $depth = (int)$record[0];
        
        $attr = new \Gedcom\Record\Indi\Attr();
        $attr->type = trim($record[1]);
        $attr->attr = isset($record[2]) ? trim($record[2]) : null;
        
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
                    $attr->type = trim($record[2]);
                break;
                
                case 'DATE':
                    $attr->date = trim($record[2]);
                break;
                
                case 'PLAC':
                    $place = \Gedcom\Parser\Indi\Even\Place::parse($parser);
                    $attr->place = &$place;
                break;
                
                case 'ADDR':
                    $attr->addr = \Gedcom\Parser\Addr::parse($parser);
                break;
                
                case 'PHON':
                    $phone = \Gedcom\Parser\Phon::parse($parser);
                    $attr->addPhon($phone);
                break;
                
                case 'CAUS':
                    $attr->caus = trim($record[2]);
                break;
                
                case 'AGE':
                    $attr->age = trim($record[2]);
                break;
                
                case 'AGNC':
                    $attr->agnc = trim($record[2]);
                break;
                
                case 'SOUR':
                    $citation = \Gedcom\Parser\SourceCitation::parse($parser);
                    
                    if(is_a($citation, '\Gedcom\Record\SourceCitation\Ref'))
                        $attr->addSourceCitationRef($citation);
                    else
                        $attr->addSourceCitation($citation);
                break;
                
                case 'OBJE':
                    $object = \Gedcom\Parser\ObjeRef::parse($parser);
                    
                    if(is_a($object, '\Gedcom\Record\Obje\Ref'))
                        $attr->addObjeRef($object);
                    else
                        $attr->addObje($object);
                break;
                
                case 'NOTE':
                    $note = \Gedcom\Parser\NoteRef::parse($parser);
                    
                    if(is_a($note, '\Gedcom\Record\Note\Ref'))
                        $attr->addNoteRef($note);
                    else
                        $attr->addNote($note);
                break;
                
                default:
                    $parser->logUnhandledRecord(get_class() . ' @ ' . __LINE__);
            }
            
            $parser->forward();
        }
        
        return $attr;
    }
}
